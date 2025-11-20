<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MasterDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $db;
    
    private $table;
    
    private $data;

    public $timeout = 90;

    public $retryAfter = 120;

    private $bot;

    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($table, $data, $id, $bot)
    {
        $this->db = 'db_simpeg_v2';
        $this->table = $table;
        $this->data = $data;
        $this->bot = $bot;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if(!empty($this->table) && !empty($this->data))
        {
            $startMessage = 'Memeriksa '.$this->table.' ...';

            $endMessage = null;

            $this->bot->sendMessage([
                'chat_id' => '5906672662',
                'text' => $startMessage
            ]);

            $jumlah_data = count($this->data);

            $index = 0;

            foreach($this->data as $x)
            {
                $update = DB::table($this->db.'.'.$this->table)->updateOrInsert([$this->id => $x[$this->id]],$x);

                if($update)
                {
                    $index++;
                }

            }

            if($jumlah_data>0)
            {
                $endMessage = $index > 0 ? $index.' data telah diperbarui pada tabel '.$this->table.' jam '.date('h:i d-M-Y').' waktu server.' : 'Data pada '.$this->table.' sudah data terbaru.';
            }else{
                $endMessage = $this->table.' gagal diperbarui pada jam '.date('h:i d-M-Y').' waktu server.';
            }

            $this->bot->sendMessage([
                'chat_id' => '5906672662',
                'text' => $endMessage
            ]);
        }else{
            if(empty($this->table)){
                $this->bot->sendMessage([
                    'chat_id' => '5906672662',
                    'text' => 'Pembuatan data master gagal. Nama tabel kosong atau tidak tersedia!'
                ]);
            }else{
                $this->bot->sendMessage([
                    'chat_id' => '5906672662',
                    'text' => 'Pembuatan data master gagal. Data kosong atau tidak tersedia!'
                ]);
            }
        }
    }

    public function retryAfter()
    {
        return 120;
    }
}
