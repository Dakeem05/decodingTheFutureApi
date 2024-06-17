<?php

namespace App\Console\Commands\ApiV1;

use App\Models\EventRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnverifiedEmailAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-email-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $instance = EventRegistration::where('otp_expires_at', '<=', Carbon::now())->delete();
        $this->info('Old OTPs nullified successfully.');
    }
}
