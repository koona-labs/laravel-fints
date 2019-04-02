<?php

namespace Abiturma\LaravelFints\Credentials;

use Abiturma\PhpFints\Credentials\CredentialsContainer;
use Illuminate\Config\Repository;
use Illuminate\Encryption\Encrypter;

/**
 * Class Credentials
 * @package Abiturma\LaravelFints
 */
class Credentials extends CredentialsContainer
{
    protected $port = null;


    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Encrypter
     */
    protected $encrypter;

    /**
     * Credentials constructor.
     * @param Repository $config
     * @param Encrypter $encrypter
     */
    public function __construct(Repository $config, Encrypter $encrypter)
    {
        $this->config = $config;
        $this->encrypter = $encrypter;
    }


    /**
     * @return string
     */
    public function host()
    {
        return $this->host ?? $this->config->get('laravel-fints.credentials.host');
    }

    /**
     * @return int|string
     */
    public function port()
    {
        return $this->port ?? $this->config->get('laravel-fints.credentials.port');
    }

    /**
     * @return string
     */
    public function bankCode()
    {
        return $this->bankCode ?? $this->config->get('laravel-fints.credentials.bank_code');
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username ?? $this->config->get('laravel-fints.credentials.username');
    }

    /**
     * @return string
     */
    public function pin()
    {
        if (!is_null($this->pin)) {
            return $this->pin;
        }
        
        $pin = $this->config->get('laravel-fints.credentials.pin');
        return $this->config->get('laravel-fints.encrypt_pin') ? $this->encrypter->decrypt($pin) : $pin;
    }
}
