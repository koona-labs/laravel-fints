<?php

namespace Tests\Integration;

use Abiturma\LaravelFints\FintsServiceProvider;
use Abiturma\LaravelFints\Tests\TestCase;

class EncryptPinCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setup();
    }

    protected function getPackageProviders($app)
    {
        return [FintsServiceProvider::class];
    }
    
    /** @test */
    public function it_encrypts_a_pin()
    {
        $this->artisan('fints:encrypt_pin')->expectsQuestion('Please enter your banking pin', 1234)
        ->expectsOutput('Your encrypted pin is:')
        ->assertExitCode(0);
    }
}
