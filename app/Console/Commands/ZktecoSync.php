<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ZktecoSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zkteco:sync';

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
        $this->info('Starting attendance sync...');
        $path = base_path('zkteco/zkteco-sync-auto.cjs');

        $process = new Process(['node', $path]);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Sync failed!');
            $this->error($process->getErrorOutput());
            return;
        }

        $this->info('Sync output: ' . $process->getOutput());
        $this->info('Attendance synced successfully!');
    }
}
