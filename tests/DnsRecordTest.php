<?php
declare(strict_types=1);
/**
 * @author  Frank van Hest <frank@frankvanhest.nl>
 * @license MIT
 */

namespace FrankVanHest\DnsLookup\Tests;

use FrankVanHest\DnsLookup\DnsRecord;
use PHPUnit\Framework\TestCase;

class DnsRecordTest extends TestCase
{
    private const RECORD_NAME = 'www.domain.tld';
    private const RECORD_PRIO = 10;
    private const RECORD_TYPE = 'A';
    private const RECORD_VALUE = '127.0.0.1';

    /**
     * An instance should be able to be created with a prio
     *
     * @return DnsRecord
     */
    public function testCanBeContructedWithPrio(): DnsRecord
    {
        $dnsRecord = new DnsRecord(self::RECORD_NAME, self::RECORD_TYPE, self::RECORD_VALUE, self::RECORD_PRIO);

        $this->assertInstanceOf(DnsRecord::class, $dnsRecord);

        return $dnsRecord;
    }

    /**
     * An instance should be able to be created without a prio
     *
     * @return void
     */
    public function testCanBeContructedWithoutPrio(): void
    {
        $dnsRecord = new DnsRecord(self::RECORD_NAME, self::RECORD_TYPE, self::RECORD_VALUE);

        $this->assertInstanceOf(DnsRecord::class, $dnsRecord);
    }

    /**
     * All the properties should be the same as given when constructing the instance
     *
     * @depends testCanBeContructedWithPrio
     *
     * @param DnsRecord $dnsRecord
     *
     * @return void
     */
    public function testPropertiesShouldBeSameAsGivenInConstructor(DnsRecord $dnsRecord): void
    {
        $this->assertSame(self::RECORD_NAME, $dnsRecord->getName());
        $this->assertSame(self::RECORD_TYPE, $dnsRecord->getType());
        $this->assertSame(self::RECORD_VALUE, $dnsRecord->getValue());
        $this->assertSame(self::RECORD_PRIO, $dnsRecord->getPrio());
    }

    /**
     * The class should be immutable
     *
     * @depends testCanBeContructedWithPrio
     * @expectedException \RuntimeException
     *
     * @param DnsRecord $dnsRecord
     *
     * @return void
     */
    public function testObjectIsImmutableWhenSettingProperty(DnsRecord $dnsRecord): void
    {
        $dnsRecord->dynamic = 'magic';
    }

    /**
     * The class should be immutable
     *
     * @depends testCanBeContructedWithPrio
     * @expectedException \RuntimeException
     *
     * @param DnsRecord $dnsRecord
     *
     * @return void
     */
    public function testObjectIsImmutableWhenUnSettingProperty(DnsRecord $dnsRecord): void
    {
        unset($dnsRecord->dynamic);
    }
}
