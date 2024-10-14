<?php declare(strict_types=1);
/**
 * IPv4/IPv6 Class
 *
 * Inspired by IPCalc
 *
 * @see     https://github.com/luzrain/ipcalc
 * @package wp-fail2ban
 * @since   5.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

use          org\lecklider\charles\wordpress\wp_fail2ban\InvalidIpException;

require_once __DIR__.'/invalid-ip-exception.php';
require_once __DIR__.'/ip-range-list.php';

/**
 * IP address.
 *
 * @since 5.0.0
 */
class IP
{
    /**
     * @var    bool     Valid IP?
     * @since  5.0.0
     */
    protected bool $valid = false;

    /**
     * @var    string   Bare IP address
     * @since  5.0.0
     */
    protected string $ip;

    /**
     * @var    int      CIDR
     * @since  5.0.0
     */
    protected int $cidr;

    /**
     * @var    int      IP version
     * @since  5.0.0
     */
    protected int $version;

    /**
     * @var    string   IP address in network order
     * @since  5.0.0
     */
    protected string $ip_long;

    /**
     * @var    string   Netmask in network order
     * @since  5.0.0
     */
    protected string $netmask_long;

    /**
     * Constructor.
     *
     * @since  5.0.0
     *
     * @param  string|null  $ip
     * @param  bool         $throw
     * @param  string       $msg
     *
     * @throw  InvalidIpException
     * @throw  \RuntimeException
     */
    public function __construct(?string $ip, bool $throw = false, string $msg = '')
    {
        if (is_null($ip)) {
            // noop
        } else {
            switch (count($p = explode('/', $ip))) {
                case 2:
                    if (is_numeric($p[1])) {
                        $this->cidr = intval($p[1]);
                    } elseif ($throw) {
                        throw new InvalidIpException($msg);
                    } else {
                        return;
                    }
                    // fallthrough
                case 1:
                    $this->ip = $p[0];

                    if (false !== ($bin = inet_pton($p[0]))) {
                        $this->ip_long = $bin;

                        switch (strlen($bin)) {
                            case 4:
                                if (!isset($this->cidr) || $this->cidr < 0 || $this->cidr > 32) {
                                    $this->cidr = 32;
                                }
                                $this->version = 4;
                                $this->netmask_v4();
                                break;
                            case 16:
                                if (!isset($this->cidr) || $this->cidr < 0 || $this->cidr > 128) {
                                    $this->cidr = 128;
                                }
                                $this->version = 6;
                                $this->netmask_v6();
                                break;
                            default:
                                // @codeCoverageIgnoreStart
                                // Something somewhere has gone desperately wrong if we get here
                                if ($throw) {
                                    throw new \RuntimeException('inet_pton');
                                } else {
                                    return;
                                }
                                // @codeCoverageIgnoreEnd
                        }

                        $this->valid = true;

                    } elseif ($throw) {
                        throw new InvalidIpException($msg);
                    }
                    break;
                default:
                    // completely bogus
                    if ($throw) {
                        throw new InvalidIpException($msg);
                    }
                    break;
            }
        }
    }

    /**
     * @since  5.0.0
     *
     * @codeCoverageIgnore
     */
    protected function netmask_v4(): void
    {
        $netmask = 0xFFFFFFFF << (32 - $this->cidr);
        $this->netmask_long = pack('N', $netmask);
    }

    /**
     * @since  5.0.0
     *
     * @codeCoverageIgnore
     */
    protected function netmask_v6(): void
    {
        $hosts = (128 - $this->cidr);
        $networks = 128 - $hosts;
        $_m = str_repeat('1', $networks) . str_repeat('0', $hosts);

        $netmask = array_reduce(str_split($_m, 4), function ($carry, $elem) {
            return $carry.base_convert($elem, 2, 16);
        });
        $netmask = join(':', str_split($netmask, 4));

        $this->netmask_long = inet_pton($netmask);
    }

    /**
     * Magic __toString
     *
     * @since  5.0.0
     *
     * @return string
     */
    public function __toString()
    {
        return ($this->valid)
            ? $this->ip
            : '';
    }

    /**
     * Valid?
     *
     * @since  5.0.0
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Return IP version
     *
     * @since  5.0.0
     *
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return ($this->valid)
            ? $this->version
            : null;
    }

    /**
     * Return IP address in printable format
     *
     * @since  5.0.0
     *
     * @return string|null
     */
    public function getIpP(): ?string
    {
        return ($this->valid)
            ? $this->ip
            : null;
    }

    /**
     * Return IP address in network order
     *
     * @since  5.0.0
     *
     * @throws \RuntimeException
     *
     * @return string|null
     */
    public function getIpN(): ?string
    {
        return ($this->valid)
            ? $this->ip_long
            : null;
    }

    /**
     * Return CIDR
     *
     * @since  5.0.0
     *
     * @return int|null
     */
    public function getCidr(): ?int
    {
        return ($this->valid)
            ? $this->cidr
            : null;
    }

    /**
     * Return Netmask in printable format
     *
     * @since  5.0.0
     *
     * @throws \RuntimeException
     *
     * @return string|null
     */
    public function getNetmaskP(): ?string
    {
        return ($this->valid)
            ? inet_ntop($this->netmask_long)
            : null;
    }

    /**
     * Return Netmask in network order
     *
     * @since  5.0.0
     *
     * @throws \RuntimeException
     *
     * @return string|null
     */
    public function getNetmaskN(): ?string
    {
        return ($this->valid)
            ? $this->netmask_long
            : null;
    }

    /**
     * Is IP in ranges?
     *
     * @since  5.0.0
     *
     * @param  array        $ranges     Array of IP Ranges to check
     * @param  string|null  $cacheTag   Cache tag
     *
     * @return bool
     */
    public function inRanges(array $ranges, string $cacheTag = null): bool
    {
        $rv = false;

        if ($this->valid && !empty($ranges)) {
            $irl = new IpRangeList($ranges, $cacheTag);
            $rv = $irl->containsBinaryIP($this->ip_long, $this->version);
        }

        return $rv;
    }
}

