<?php

namespace App\Console\Commands;

use App\Services\OtpService;
use Illuminate\Console\Command;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP tokens from the database';

    protected $otpService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OtpService $otpService)
    {
        parent::__construct();
        $this->otpService = $otpService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deletedCount = $this->otpService->cleanupExpiredOtps();
        
        $this->info("Cleaned up {$deletedCount} expired OTP tokens.");
        
        return 0;
    }
} 