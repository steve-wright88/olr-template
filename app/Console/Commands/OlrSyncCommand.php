<?php

namespace App\Console\Commands;

use App\Services\SyncService;
use Illuminate\Console\Command;

class OlrSyncCommand extends Command
{
    protected $signature = 'olr:sync';

    protected $description = 'Sync race data from oneloftrace.live for this loft';

    public function handle(SyncService $service): int
    {
        $loftId = config('olr.loft_id');
        $this->info("Syncing loft {$loftId} (".config('olr.site_name').')...');

        $stats = $service->onProgress(fn ($msg) => $this->line("  {$msg}"))->sync();

        $this->newLine();
        $this->info('Sync complete:');
        $this->table(
            ['Metric', 'Count'],
            collect($stats)->map(fn ($v, $k) => [ucfirst($k), number_format($v)])->values()->all()
        );

        return Command::SUCCESS;
    }
}
