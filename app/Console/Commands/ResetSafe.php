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
    protected $signature = 'reset:safeTicket';

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
        \Log::info("reset safeTicket");
        //重置前可以考虑保存一下 
        $js = Redis::get('safe:numbers');
        \Log::info($js);

        //重置前可以考虑保存一下 TODO
        Redis::set('safe:numbers', json_encode([]));
        Redis::set('safe:count', 0);
        return 0;
    }
}
