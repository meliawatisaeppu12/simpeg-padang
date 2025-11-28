<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckQueueResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $retryAfter = 120;

    private $bot;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($bot)
    {
        $this->bot = $bot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $jumlah_queue_gagal = DB::table('failed_jobs')->select('*')->get()->count();

        $this->bot->sendMessage([
            'chat_id' => '5906672662',
            'text' => $jumlah_queue_gagal > 0 ? $jumlah_queue_gagal.' Queue gagal diproses.':'Semua Queue berhasil diproses.'
        ]);
    }

    public function retryAfter()
    {
        return 120;
    }
}
