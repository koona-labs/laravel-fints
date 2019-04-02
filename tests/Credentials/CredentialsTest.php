<?php

namespace Abiturma\LaravelFints\Tests\Credentials;

use Abiturma\LaravelFints\Credentials\Credentials;
use Abiturma\LaravelFints\FintsServiceProvider;
use Abiturma\LaravelFints\Tests\TestCase;
use Illuminate\Config\Repository;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class CredentialsTest extends TestCase
{
    protected $instance;

    protected function getPackageProviders($app)
    {
        return [FintsServiceProvider::class];
    }
    
    
    public function setUp(): void
    {
        parent::setup();

        $this->mergeConfig([
            'credentials.host' => 'configHost',
            'credentials.port' => 'configPort',
            'credentials.bank_code' => 'configBankCode',
            'credentials.username' => 'configUsername',
            'credentials.pin' => 'configPin',
            'encrypt_pin' => false
        ]);
    }


    /** @test */
    public function it_returns_the_given_host()
    {
        $this->assertGivenValue('host', 'givenHost');
    }

    /** @test */
    public function if_no_host_is_given_it_returns_the_config_value()
    {
        $this->assertEquals('configHost', $this->make()->host());
    }



    /** @test */
    public function it_returns_the_given_port()
    {
        $this->assertGivenValue('port', 'givenPort');
    }

    /** @test */
    public function if_no_port_is_given_it_returns_the_config_value()
    {
        $this->assertEquals('configPort', $this->make()->port());
    }


    /** @test */
    public function it_returns_the_given_bank_code()
    {
        $this->assertGivenValue('bankCode', 'givenBankCode');
    }

    /** @test */
    public function if_no_bank_code_is_given_it_returns_the_config_value()
    {
        $this->assertEquals('configBankCode', $this->make()->bankCode());
    }


    /** @test */
    public function it_returns_the_given_username()
    {
        $this->assertGivenValue('username', 'givenUsername');
    }

    /** @test */
    public function if_no_username_is_given_it_returns_the_config_value()
    {
        $this->assertEquals('configUsername', $this->make()->username());
    }


    /** @test */
    public function it_returns_the_given_pin()
    {
        $this->assertGivenValue('pin', 'givenPin');
    }

    /** @test */
    public function if_no_pin_is_given_it_returns_the_config_value()
    {
        $this->assertEquals('configPin', $this->make()->pin());
    }

    /** @test */
    public function optionally_the_pin_can_be_encrypted()
    {
        $encrypter = $this->app->make(Encrypter::class);
        $this->mergeConfig(['encrypt_pin' => true]);
        $this->mergeConfig(['credentials.pin' => $encrypter->encrypt('myEncryptedPin') ]);
        $this->assertEquals('myEncryptedPin', $this->make()->pin());
    }


    protected function assertGivenValue($key, $value)
    {
        $method = Str::studly('set_' . $key);
        $getter = Str::studly($key);
        $instance = $this->make()->$method($value);
        $this->assertEquals($value, $instance->$getter());
    }

    protected function assertConfigFallback($key, $value)
    {
        $getter = Str::studly($key);
        $this->config->expects($this->once())
            ->method('get')
            ->with('laravel-hbci.credentials.' . Str::snake($key))
            ->will($this->returnValue($value));

        $this->assertEquals($value, $this->make()->$getter());
    }

    protected function mergeConfig(array $values = [])
    {
        $config = $this->app->make(Repository::class);
        
        foreach ($values as $key => $value) {
            $config->set('laravel-fints.' . $key, $value);
        }
        
        return $this;
    }


    public function make()
    {
        return $this->app->make(Credentials::class);
    }
}
