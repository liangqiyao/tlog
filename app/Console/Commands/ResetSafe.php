<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset safe pools';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //重置前可以考虑保存一下 TODO
        Redis::set('safe:numbers', json_encode([]));
        Redis::set('safe:count', 0);
        return 0;
    }
}
