<?php

namespace Abiturma\LaravelFints\Console;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;

/**
 * Class EncryptPinCommand
 * @package Abiturma\LaravelFints
 */
class EncryptPinCommand extends Command
{
    protected $signature = 'fints:encrypt_pin';
    
    protected $description = 'Encrypts your entered banking pin';

    /**
     * @param Encrypter $encrypter
     */
    public function handle(Encrypter $encrypter)
    {
        $pin = $this->ask('Please enter your banking pin');
        $this->line('Your encrypted pin is:');
        $this->info($encrypter->encrypt($pin));
        $this->line('Copy this string to your .env');
        return;
    }
}
