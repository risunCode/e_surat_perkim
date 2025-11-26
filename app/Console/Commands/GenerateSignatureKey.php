<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSignatureKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'signature:generate {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new signature key for document digital signatures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->hasExistingSignatureKey() && !$this->option('force')) {
            $this->error('SIGNATURE_KEY already exists in .env file.');
            $this->line('Use --force option to overwrite the existing key.');
            return;
        }

        if ($this->laravel->environment('production') && !$this->option('force')) {
            $this->error('Cannot generate signature key in production without --force flag.');
            return;
        }

        $key = $this->generateKey();
        $this->setSignatureKeyInEnvironmentFile($key);

        $this->info('SIGNATURE_KEY generated successfully.');
        $this->line('New signature key: ' . $key);
        
        if ($this->laravel->environment('production')) {
            $this->warn('Make sure to backup your .env file and restart your web server.');
        }
    }

    /**
     * Generate a random signature key
     */
    protected function generateKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }

    /**
     * Check if signature key already exists
     */
    protected function hasExistingSignatureKey(): bool
    {
        $envPath = $this->laravel->environmentFilePath();
        
        if (!file_exists($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath);
        return preg_match('/^SIGNATURE_KEY=.+$/m', $content);
    }

    /**
     * Set the signature key in the environment file
     */
    protected function setSignatureKeyInEnvironmentFile(string $key): void
    {
        $envPath = $this->laravel->environmentFilePath();
        $content = file_get_contents($envPath);

        if (preg_match('/^SIGNATURE_KEY=.*$/m', $content)) {
            // Update existing key
            $content = preg_replace('/^SIGNATURE_KEY=.*$/m', 'SIGNATURE_KEY=' . $key, $content);
        } else {
            // Add new key
            if (preg_match('/^APP_KEY=.*$/m', $content)) {
                $content = preg_replace('/^(APP_KEY=.*$)/m', "$1\n\n# Digital Signature\nSIGNATURE_KEY=" . $key, $content);
            } else {
                $content .= "\n\n# Digital Signature\nSIGNATURE_KEY=" . $key . "\n";
            }
        }

        file_put_contents($envPath, $content);
    }
}
