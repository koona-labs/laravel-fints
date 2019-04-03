<?php

namespace Abiturma\LaravelFints;

use Abiturma\LaravelFints\Console\EncryptPinCommand;
use Abiturma\LaravelFints\Credentials\Credentials;

use Abiturma\PhpFints\Adapter\Curl;
use Abiturma\PhpFints\Adapter\SendsMessages;
use Abiturma\PhpFints\Credentials\HoldsCredentials;
use Abiturma\PhpFints\Dialog\Dialog;
use Abiturma\PhpFints\Encryption\EncryptsASequenceOfSegments;
use Abiturma\PhpFints\Encryption\NullEncrypter;

use Abiturma\PhpFints\Misc\NullLogger;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

/**
 * Class FintsServiceProvider
 * @package Abiturma\LaravelFints
 */
class FintsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-fints.php' => config_path('laravel-fints.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                EncryptPinCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravel-fints.php',
            'laravel-fints'
        );

        $this->app->bind(
            SendsMessages::class,
            Curl::class
        );

        $this->app->bind(
            EncryptsASequenceOfSegments::class,
            NullEncrypter::class
        );

        $this->app->bind(
            HoldsCredentials::class,
            Credentials::class
        );

        if (!config('laravel-fints.logging.enabled')) {
            $this->app->when(Dialog::class)
                ->needs(LoggerInterface::class)
                ->give(NullLogger::class);
        } elseif ($logger = config('laravel-fints.logging.logger')) {
            $this->app->when(Dialog::class)
                ->needs(LoggerInterface::class)
                ->give($logger);
        }

        $this->app->bind('Fints', Fints::class);
    }
}
