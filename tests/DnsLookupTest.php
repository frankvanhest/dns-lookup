<?php
declare(strict_types=1);
/**
 * @author  Frank van Hest <frank@frankvanhest.nl>
 * @license MIT
 */

namespace FrankVanHest\DnsLookup\Tests;

use FrankVanHest\DnsLookup\DnsLookup;
use FrankVanHest\DnsLookup\DnsRecord;
use PHPUnit\Framework\TestCase;

/**
 * Class DnsLookupTest
 * @package FrankVanHest\DnsLookup\Tests
 * @todo Write tests for each record type if it is parsed correctly
 */
class DnsLookupTest extends TestCase
{
    /**
     * @var int
     */
    private $numberOfRecordTypes;

    /**
     * Dataprovider for record types
     *
     * @return array
     */
    public function recordTypeProvider(): array
    {
        return [
            ['A'],
            ['AAAA'],
            ['CAA'],
            ['CDS'],
            ['CERT'],
            ['CERT'],
            ['CNAME'],
            ['DNSKEY'],
            ['DS'],
            ['HINFO'],
            ['LOC'],
            ['MX'],
            ['NAPTR'],
            ['NS'],
            ['PTR'],
            ['SOA'],
            ['SRV'],
            ['SSHFP'],
            ['TLSA'],
            ['TXT'],
        ];
    }

    /**
     * An instance should be able to be created without a nameserver
     *
     * @return void
     */
    public function testCanBeConstructedWithoutNameserver(): void
    {
        $dnsLookup = new DnsLookup($GLOBALS['lookupDomain']);

        $this->assertInstanceOf(DnsLookup::class, $dnsLookup);
    }

    /**
     * An instance should be able to be created without a nameserver
     *
     * @return DnsLookup
     */
    public function testCanBeConstructedWithNameserver(): DnsLookup
    {
        $dnsLookup = new DnsLookup($GLOBALS['lookupDomain'], $GLOBALS['lookupInNameserver']);

        $this->assertInstanceOf(DnsLookup::class, $dnsLookup);

        return $dnsLookup;
    }

    /**
     * Records should be able to be retrieved by type
     *
     * @dataProvider recordTypeProvider
     *
     * @param string $type
     * @return void
     */
    public function testGetRecordsByType(string $type): void
    {
        $dnsLookup = $this->buildMockedForType($type);
        $dnsRecords = $dnsLookup->getRecordsByType($type);
        array_walk($dnsRecords, function (DnsRecord $dnsRecord) use ($type) {
            $this->assertSame($type, $dnsRecord->getType());
        });
        $this->assertCount($this->numberOfRecordTypes, $dnsRecords);
    }

    /**
     * Get records of any type
     *
     * @return void
     */
    public function testGetAllRecords(): void
    {
        $dnsLookup = $this->buildMockedForType();
        $dnsRecords = $dnsLookup->getAllRecords();
        $this->assertCount($this->numberOfRecordTypes, $dnsRecords);
    }

    /**
     * Build a mocked object of DnsLookup
     *
     * @param string|null $type
     * @return DnsLookup
     */
    private function buildMockedForType(string $type = null): DnsLookup
    {
        $this->numberOfRecordTypes = 0;
        $rawDigOutput = '';
        foreach(scandir(__DIR__ . '/TestData/') as $file) {
            if ($file === '.' || $file === '..' || ($type !== null && $file !== 'rawDigOutput' . $type . '.txt')) {
                continue;
            }
            $rawDigOutput .= file_get_contents(__DIR__ . '/TestData/' . $file);
            if (stristr($rawDigOutput, PHP_EOL) === false) {
                $rawDigOutput .= PHP_EOL;
            }
            $this->numberOfRecordTypes++;
        }
        $mockedDnsLookup = $this->getMockBuilder(DnsLookup::class)
            ->setMethods(['digForRecords'])
            ->setConstructorArgs(['domain.com'])
            ->getMock();
        $mockedDnsLookup->method('digForRecords')->willReturn($rawDigOutput);

        return $mockedDnsLookup;
    }
}
