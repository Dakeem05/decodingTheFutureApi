<?php

namespace App\Console\Commands\Api\V1;

use App\Models\EventRegistration;
use Illuminate\Console\Command;

class DeleteSoftDeletedRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-soft-deleted-records';

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
        EventRegistration::onlyTrashed()->forceDelete();
        $this->info('Soft deleted records have been permanently deleted.');
        // return 0;
    }
}
