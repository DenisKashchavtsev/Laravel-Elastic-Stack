<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:log-command';

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
        Log::info('Это тестовое сообщение для Logstash', ['context' => 'test']);
        Log::channel('logstash')->warning('Это тестовое сообщение для Logstash warning', ['context' => 'test warning']);
        Log::channel('logstash')->error('Это тестовое сообщение для Logstash error', ['context' => 'test error']);

        $this->info('Сообщение залогировано!');
    }
}
