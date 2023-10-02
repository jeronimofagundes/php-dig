<?php
/**
 * Runs the unix command dig.
 * @author Jerônimo Fagundes da Silva <jeronimo.fs@protonmail.com>
 */

namespace JeronimoFagundes\PhpDig;

use \InvalidArgumentException;
use \RuntimeException;

class Dig {
    protected $config = null;

    /**
     * Dig constructor.
     * @param DigConfig $config The config for the command dig.
     */
    public function __construct($config = null){
        if (!$config instanceof DigConfig) {
            $config = new DigConfig();
        }
        $this->config = $config;
    }

    /**
     * Gets a list of all supported types
     * @return array
     */
    protected function supportedTypes(){
        // The following list of supported types was obtained in https://en.wikipedia.org/wiki/List_of_DNS_record_types
        // The obsolete types are not supported.
        return array(
            'A', 'AAAA', 'AFSDB', 'APL', 'AXFR',
            'CAA', 'CDNSKEY', 'CDS', 'CERT', 'CNAME',
            'DHCID', 'DLV', 'DNAME', 'DNSKEY', 'DS',
            'HIP',
            'IPSECKEY', 'IXFR',
            'KEY', 'KX',
            'LOC',
            'MX',
            'NAPTR', 'NS', 'NSEC', 'NSEC3', 'NSEC3PARAM',
            'OPENPGPKEY', 'OPT',
            'PTR',
            'RRSIG', 'RP',
            'SIG', 'SOA', 'SRV', 'SSHFP',
            'TA', 'TKEY', 'TLSA', 'TSIG', 'TXT',
            'URI'
        );
    }

    /**
     * Tells if a type is supported or not.
     * @param $type string The type to test.
     * @return bool
     */
    protected function typeIsSupported($type){
        return is_string($type) && in_array($type, $this->supportedTypes());
    }

    /**
     * Builds the command line to run.
     * @param string $name The name to query.
     * @param string $type The DNS record type to query. Default is A.
     * @return string
     */
    protected function buildCommandLine($name = null, $type = 'A'){
        return
            "dig " .
            ($this->config->getServer()) .
            " " .
            ($this->config->getTimeout()) .
            " " .
            ($this->config->getTries()) .
            " " .
            $this->config->getShort() .
            " " .
            " {$name} {$type}";
    }

    /**
     * Query the server for the pair name + type
     * @param string $name The name to query.
     * @param string $type The DNS record type to query. Default is A.
     * @return array The return of dig execution
     * @throws \InvalidArgumentException When parameters are unacceptable
     */
    public function query($name = null, $type = 'A'){
        $type = trim($type);
        if (!$this->typeIsSupported($type)) {
            throw new InvalidArgumentException(
                "type {$type} not supported. Supported types are: " .
                implode(',', $this->supportedTypes())
            );
        }

        if (is_string($name)) { $name = trim($name); }
        if (empty($name) || !is_string($name)) { //TODO: Really check if it is a hostname
            throw new InvalidArgumentException(
                "name needs to be a hostname"
            );
        }

        $commandLine = $this->buildCommandLine($name, $type);
        $return = array();
        $intReturn = 0;
        exec($commandLine, $return, $intReturn);

        if ($intReturn != 0){
            throw new RuntimeException('dig execution returned a code different from 0');
        }

        return $return;
    }

}