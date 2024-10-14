<?php declare(strict_types=1);
/**
 * IPv4/IPv6 Ranges Class
 *
 * @package wp-fail2ban
 * @since   5.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

use          org\lecklider\charles\wordpress\wp_fail2ban\premium\WPf2b;

/**
 * List of IP ranges.
 *
 * @since 5.0.0
 */
class IpRangeList implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var   int   Iterator position.
     * @since 5.0.0
     */
    protected int $position = 0;

    /**
     * @var   array|bool    IP Ranges.
     * @since 5.0.0
     */
    protected $ranges = false;

    /**
     * Constructor.
     *
     * @since  5.0.0
     *
     * @param  array        $ranges
     * @param  string|null  $cacheTag
     */
    public function __construct(array $ranges, string $cacheTag = null)
    {
        $canCache = (!is_null($cacheTag) &&
                     class_exists(__NAMESPACE__.'\\premium\\WPf2b') &&
                     WPf2b::can_use_premium_code());
        if ($canCache) {
            $this->ranges = wp_cache_get($cacheTag, WP_FAIL2BAN_CACHE);
        }
        if (false === $this->ranges) {
            $this->ranges = [];
            foreach ($ranges as $range) {
                $range = trim($range);
                if (empty($range) || '#' == $range[0]) {
                    continue;
                } else {
                    $this->ranges[] = new IP($range);
                }
            }
            if ($canCache) {
                wp_cache_set($cacheTag, $this->ranges, WP_FAIL2BAN_CACHE);
            }
        }
    }

    /**
     * Does the range contain the IP?
     *
     * @since  5.0.0
     *
     * @param  string|IP   $ip
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function containsIP($ip): bool
    {
        if (is_string($ip)) {
            $bin = inet_pton($ip);
            $ver = null;

        } elseif (is_a($ip, __NAMESPACE__.'\IP')) {
            $bin = $ip->getIpN();
            $ver = $ip->getVersion();

        } else {
            throw new \InvalidArgumentException('Must be string or IP.');
        }

        return self::containsBinaryIP($bin, $ver);
    }

    /**
     * Does the range contain the IP? (Binary)
     *
     * @since  5.0.0
     *
     * @param  string   $binIP
     * @param  int|null $version
     *
     * @return bool
     */
    public function containsBinaryIP($binIP, ?int $version = null): bool
    {
        if (is_null($version)) {
            $version = (4 == strlen($binIP)) ? 4 : 6;
        }
        foreach ($this->ranges as $ipRange) {
            if ($version == $ipRange->getVersion() &&
                $ipRange->getIpN() == ($binIP & $ipRange->getNetmaskN()))
            {
                return true;
            }
        }
        return false;
    }


    //
    // ArrayAccess
    //

    /**
     * ArrayAccess: offsetSet.
     *
     * @since  5.0.0
     *
     * @param  mixed    $offset Key.
     * @param  mixed    $value  Value.
     *
     * @throw \RuntimeException
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function offsetSet($offset, $value): void
    {
        throw new \RuntimeException('Lists are read-only');
    }

    /**
     * ArrayAccess: offsetExists.
     *
     * @since  5.0.0
     *
     * @param  mixed    $offset Key.
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function offsetExists($offset): bool
    {
        return isset($this->ranges[$offset]);
    }

    /**
     * ArrayAccess: offsetUnset.
     *
     * @since  5.0.0
     *
     * @param mixed     $offset Key.
     *
     * @throw \RuntimeException
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function offsetUnset($offset): void
    {
        throw new \RuntimeException('Lists are read-only');
    }

    /**
     * ArrayAccess: offsetGet.
     *
     * @since  5.0.0
     *
     * @param  mixed    $offset Key.
     * @return bool
     *
     * @codeCoverageIgnore
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset): bool
    {
        return isset($this->ranges[$offset]) ? $this->ranges[$offset] : null;
    }


    //
    // Countable
    //

    /**
     * Countable: count.
     *
     * @since  5.0.0
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function count(): int
    {
        return count($this->ranges);
    }


    //
    // Iterator
    //

    /**
     * Iterator: rewind.
     *
     * @since  5.0.0
     *
     * @codeCoverageIgnore
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Iterator: current.
     *
     * @since  5.0.0
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->ranges[$this->position];
    }

    /**
     * Iterator: key.
     *
     * @since  5.0.0
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->position;
    }

    /**
     * Iterator: next.
     *
     * @since  5.0.0
     *
     * @codeCoverageIgnore
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Iterator: valid.
     *
     * @since  5.0.0
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function valid(): bool
    {
        return isset($this->ranges[$this->position]);
    }
}

