<?php
/**
 * @author  Frank van Hest <frank@frankvanhest.nl>
 * @license MIT
 */

namespace FrankVanHest\DnsLookup;

/**
 * Class DnsLookup
 *
 * @package FrankVanHest\DnsLookup
 */
class DnsLookup
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var null|string
     */
    private $nameserver;

    /**
     * DnsLookup constructor.
     *
     * @param string $domain
     * @param string|null $nameserver
     */
    public function __construct(string $domain, string $nameserver = null)
    {
        $this->domain = $domain;
        $this->nameserver = $nameserver;
    }

    /**
     * Get all the DNS records of a certain type
     *
     * @param string $type
     *
     * @return DnsRecord[]
     * @throws \OutOfBoundsException When no records of the type can be found
     */
    public function getRecordsByType(string $type): array
    {
        return $this->convertRawDigOutputToDnsRecords($this->digForRecords($type));
    }

    /**
     * Get all the DNS records available
     *
     * @param string $type
     *
     * @return DnsRecord[]
     * @throws \OutOfBoundsException When no records of the type can be found
     */
    public function getAllRecords(): array
    {
        return $this->convertRawDigOutputToDnsRecords($this->digForRecords());
    }

    /**
     * Run dig and retrieve the records
     *
     * @param string $type
     * @return string
     */
    protected function digForRecords(string $type = 'ANY'): string
    {
        $nameserver = $this->nameserver ? '@' . $this->nameserver . ' ' : '';
        $rawDigOutput = shell_exec(
            'dig +nocmd ' . $nameserver . $this->domain . ' ' . $type . ' +multiline +noall +answer'
        );

        return $rawDigOutput;
    }

    /**
     * Build an instance of DnsRecord of the parsed raw dig output
     *
     * @param array $record
     * @return DnsRecord
     */
    private function buildDnsRecordFromParsedRawDigOutput(array $record): DnsRecord
    {
        $prio = null;
        if ($record[3] === 'MX' || $record[3] === 'SRV') {
            list($prio, $record[4]) = explode(' ', $record[4], 2);
        }
        return new DnsRecord($record[0], $record[3], $record[4], (int)$record[1], $prio);
    }

    /**
     * Convert the dig raw output to DnsRecord instances
     *
     * @param string $rawDigOutput
     * @return DnsRecord[]
     * @todo For now it works, but it needs refactoring
     */
    private function convertRawDigOutputToDnsRecords(string $rawDigOutput): array
    {
        $rawDigOutput = rtrim($rawDigOutput, PHP_EOL);
        $digLines = explode(PHP_EOL, $rawDigOutput);
        $recordStorage = [];
        $dnsRecords = [];
        foreach ($digLines as $index => $digLine) {
            $digLine = str_replace("\t\t", ' ', $digLine);
            $nextDigLine = $digLines[$index + 1] ?? null;
            $record = explode(' ', $digLine, 5);
            if ($nextDigLine !== null) {
                $nextDigLine = str_replace("\t\t", ' ', $nextDigLine);
                $recordNext = explode(' ', $nextDigLine, 5);
                if (strstr($recordNext[0], $this->domain) === false && $recordNext[3] !== 'PTR') {
                    if (strstr($record[0], $this->domain) !== false) {
                        $recordStorage = $record;
                        continue;
                    }
                    $recordStorage[4] .= ' ' . $this->cleanUpValue($record[4]);
                    continue;
                }
            } else {
                if (!empty($recordStorage)) {
                    $recordStorage[4] .= ' ' . $this->cleanUpValue($record[4]);
                }
            }

            if (!empty($recordStorage)) {
                $record = $recordStorage;
                $recordStorage = [];
            }
            $dnsRecords[] = $this->buildDnsRecordFromParsedRawDigOutput($record);
        }
        return $dnsRecords;
    }

    /**
     * Cleanup the record value
     *
     * Multiple spaces and comments are removed
     *
     * @param string $value
     * @return string
     */
    private function cleanUpValue(string $value): string
    {
        $value = preg_replace('/[\s]{2,}/', '', $value);
        if (strstr($value, ';') !== false) {
            list($value, ) = explode(';', $value);
        }

        return $value;
    }
}
