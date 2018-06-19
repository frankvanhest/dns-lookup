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

class DnsLookupTest extends TestCase
{
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
     * All the properties should be the same as given when constructing the instance
     *
     * @depends testCanBeConstructedWithNameserver
     *
     * @param DnsLookup $dnsLookup
     *
     * @return void
     */
    public function testGetRecordsByType(DnsLookup $dnsLookup): void
    {
        $dnsRecords = $dnsLookup->getRecordsByType('A');
        array_walk($dnsRecords, function (DnsRecord $dnsRecord) {
            $this->assertSame('A', $dnsRecord->getValue());
        });
    }
}
