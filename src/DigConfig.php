<?php
/**
 * Configuration for the dig command.
 * @author Jeronimo Fagundes <jeronimo.fs@protonmail.com>
 */

namespace JeronimoFagundes\PhpDig;
use \InvalidArgumentException;


class DigConfig
{
    protected $tries = 3;
    protected $timeout = 5;
    protected $server = null;
    protected $short = true; // Allowing only short for now.

    public function __construct(){
    }

    /**
     * Sets the number of times to try UDP queries to server to $tries instead of the default, 3.
     * If $tries is less than or equal to zero, the number of tries is silently rounded up to 1.
     * @param int $tries The number of times to try UDP queries to server
     * @throws \InvalidArgumentException If $tries is not an integer
     * @return DigConfig The caller object.
     */
    public function setTries($tries = 3){
        if(!is_int($tries)) {
            throw new \http\Exception\InvalidArgumentException('tries needs to be an integer')
        }

        $this->tries = $tries;
        return $this;
    }

    /**
     * Sets the timeout for a query to $timeout seconds. The default timeout is 5 seconds.
     * An attempt to set $timeout to less than 1 will result in a query timeout of 1 second being applied.
     * @param int $timeout The timeout in seconds for a query
     * @throws \InvalidArgumentException If $timeout is not an integer
     * @return DigConfig The caller object.
     */
    public function setTimeout($timeout = 5){
        if(!is_int($timeout)) {
            throw new \http\Exception\InvalidArgumentException('timeout needs to be an integer')
        }

        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Sets the name or IP address of the name server to query.
     * @param string $server The name or IP address of the name server to query
     * @throws \InvalidArgumentException If $server is not a string
     * @return DigConfig The caller object.
     * @todo Validate if the string passed in $server is a hostname or IP.
     */
    public function setServer($server = null){
        if(!is_string($server) && !is_null($server)) {
            throw new \http\Exception\InvalidArgumentException('server needs to be an ip or a hostname or null')
        }

        $this->server = $server;
        return $this;
    }

    /**
     * Gets the string to be passed to dig to configure tries.
     * @return string
     */
    public function getTries(){
        return "+tries={$this->tries}";
    }

    /**
     * Gets the string to be passed to dig to configure timeout.
     * @return string
     */
    public function getTimeout(){
        return "+timeout={$this->timeout}";
    }

    /**
     * Gets the string to be passed to dig to configure server.
     * @return string
     */
    public function getServer(){
        return empty($this->server) ? "" : "@{$this->server}";
    }

    /**
     * Gets the string to be passed to dig to configure the shortness.
     * @return string
     */
    public function getShort(){
        return $this->short ? "+short" : "";
    }
}