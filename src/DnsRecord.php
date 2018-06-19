<?php
declare(strict_types=1);
/**
 * @author  Frank van Hest <frank@frankvanhest.nl>
 * @license MIT
 */

namespace FrankVanHest\DnsLookup;

/**
 * Class DnsRecord
 *
 * @package FrankVanHest\DnsLookup
 */
class DnsRecord
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int|null
     */
    private $prio;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * DnsRecord constructor
     *
     * @param string   $name
     * @param string   $type
     * @param string   $value
     * @param int|null $prio
     */
    public function __construct(string $name, string $type, string $value, int $prio = null)
    {
        $this->name  = $name;
        $this->type  = $type;
        $this->value = $value;
        $this->prio  = $prio;
    }

    /**
     * This object should be immutable so setting a property with magic is not allowed
     *
     * @param $name
     * @param $value
     */
    final public function __set($name, $value)
    {
        throw new \RuntimeException('The state of the object cannot be changed');
    }

    /**
     * This object should be immutable so unsetting a property with magic is not allowed
     *
     * @param $name
     */
    final public function __unset($name)
    {
        throw new \RuntimeException('The state of the object cannot be changed');
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    final public function getPrio(): ?int
    {
        return $this->prio;
    }

    /**
     * @return string
     */
    final public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    final public function getValue(): string
    {
        return $this->value;
    }
}
