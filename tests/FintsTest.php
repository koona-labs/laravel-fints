<?php

namespace Abiturma\LaravelFints\Tests;

use Abiturma\LaravelFints\Fints;
use Abiturma\LaravelFints\FintsServiceProvider;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FintsTest extends TestCase
{
    protected $fints;

    
    public function setUp() :void
    {
        parent::setUp();
        $this->fints = $this->createMock(\Abiturma\PhpFints\BaseFints::class);
        $this->fints->method('getAccounts')->willReturn([1,2,3]);
    }
    

    protected function getPackageProviders($app)
    {
        return [FintsServiceProvider::class];
    }

    /** @test */
    public function it_returns_collections()
    {
        $accounts = $this->make()->getAccounts();
        $this->assertInstanceOf(Collection::class, $accounts);
        $this->assertEquals([1,2,3], $accounts->toArray());
    }
    
    
    /** @test */
    public function it_proxies_methods_to_set_credentials_to_the_wrapped_class()
    {
        $this->fints
            ->expects($this->once())
            ->method('__call')->with('username', ['testUsername'])
            ->will($this->returnSelf());
        $fints = $this->make()->username('testUsername');
        $this->assertInstanceOf(Fints::class, $fints);
    }
    

    protected function make()
    {
        return new Fints($this->fints);
    }
}
