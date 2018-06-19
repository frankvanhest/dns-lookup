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
     * @var DnsRecord[]
     */
    private $dnsRecords;

    /**
     * DnsLookup constructor.
     *
     * @param string      $domain
     * @param string|null $nameserver
     */
    public function __construct(string $domain, string $nameserver = null)
    {
        $this->domain     = $domain;
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
        $this->findRecords($type);

        return $this->dnsRecords;
    }

    private function findRecords(string $type): void
    {
        $nameserver = $this->nameserver ? '@' . $this->nameserver . ' ' : '';
        $result = shell_exec('dig +nocmd ' . $nameserver . $this->domain . ' ' . $type . ' +multiline +noall +answer');

        var_dump($result);
    }
}
