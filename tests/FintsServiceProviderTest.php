<?php

namespace Abiturma\LaravelFints\Tests;

use Abiturma\LaravelFints\FintsServiceProvider;

class FintsServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [FintsServiceProvider::class];
    }
    
    /** @test */
    public function it_provides_a_config()
    {
        $this->assertEquals(443, config('laravel-fints.credentials.port'));
    }
}
