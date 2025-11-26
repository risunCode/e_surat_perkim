<?php

namespace App\Console\Commands;

use App\Models\Letter;
use App\Services\SignatureService;
use Illuminate\Console\Command;

class SyncLetterSignatures extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'letters:sync-signatures {--force : Force regenerate all signatures}';

    /**
     * The console command description.
     */
    protected $description = 'Generate digital signatures for all letters that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        if ($force) {
            $this->warn('Force mode: Will regenerate ALL signatures.');
            if (!$this->confirm('Are you sure?')) {
                return;
            }
        }
        
        // Get letters without valid signatures (or all if force)
        $query = Letter::with(['user', 'classification', 'attachments']);
        
        if (!$force) {
            $query->whereDoesntHave('signatures', function ($q) {
                $q->where('is_valid', true);
            });
        }
        
        $letters = $query->get();
        
        if ($letters->isEmpty()) {
            $this->info('All letters already have valid signatures.');
            return;
        }
        
        $this->info("Found {$letters->count()} letters to process...");
        $bar = $this->output->createProgressBar($letters->count());
        $bar->start();
        
        $signatureService = app(SignatureService::class);
        $success = 0;
        $failed = 0;
        
        foreach ($letters as $letter) {
            try {
                // Generate new signature using SignatureService
                $signatureService->generateLetterSignature($letter, $letter->user_id);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed for letter #{$letter->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Completed! Success: {$success}, Failed: {$failed}");
    }
}
