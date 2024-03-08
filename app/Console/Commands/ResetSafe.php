<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;


class ResetSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:safeTickect';

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
        \Log::info("reset safeTickect");
        
        //重置前可以考虑保存一下 TODO
        Redis::set('safe:numbers', json_encode([]));
        Redis::set('safe:count', 0);
        return 0;
    }
}