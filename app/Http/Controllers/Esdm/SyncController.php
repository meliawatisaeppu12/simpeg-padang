<?php

namespace App\Http\Controllers\Esdm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Carbon;
use App\Jobs\MasterDataJob;
use App\Jobs\RequestDataJob;
use App\Jobs\CheckQueueResultJob;

class SyncController extends Controller
{
    private $bot;

    public function __construct()
    {
        $this->bot = Telegram::bot('mybot');
    }

    public function requestDataPegawaiJobs()
    {
        // $data = app(DataController::class)->dataUnitOrganisasi()->all();

        $data_nip = DB::table('db_simpeg_v2.data_nip_pegawai')
                        ->select('nip')
                        ->where('status',1)
                        ->limit(10000)
                        ->orderBy('nip','DESC')
                        ->get();

        $data_uri = DB::table('db_simpeg_v2.data_uri')
                        ->whereNotNull('prefix')
                        ->whereNotNull('uri')
                        ->whereNotNull('target_table')
                        ->where('prefix','!=','')
                        ->where('uri','!=','')
                        ->where('target_table','!=','')
                        ->get();

        $jumlah_nip = $data_nip->count();

        $index = 0;

        $jumlah_data = 0;

        $minutes = 1;

        $separator = 50;

        foreach($data_nip as $x){

            // if($index > 49 && (($index % $separator) == 0))
            // {
            //     $minutes = $minutes+3;
            // }

            foreach($data_uri as $y)
            {
                // Queue::later(Carbon::now()->addMinutes($minutes),new RequestDataJob($x->nip,$this->parseUrl($y->prefix,$y->uri,$x->nip),$y->target_table,$this->getParameter($y->uri)));
                Queue::later(Carbon::now(),new RequestDataJob($x->nip,$this->parseUrl($y->prefix,$y->uri,$x->nip),$y->target_table,$this->getParameter($y->uri)));
                $jumlah_data++;
            }
            $index++;
        }

        if($jumlah_nip==$index)
        {
            
            Queue::later(Carbon::now(),new CheckQueueResultJob($this->bot));
            
            Queue::later(Carbon::now(),new MasterDataJob('m_organisasi',app(DataController::class)->dataOrganisasi(),'unor_induk_id',$this->bot));
            
            Queue::later(Carbon::now(),new MasterDataJob('m_unit_organisasi',app(DataController::class)->dataUnitOrganisasi(),'unor_id',$this->bot));
            
            $this->sendMessage(5906672662,$jumlah_data.' Queue baru telah dibuat. Mulai memproses Queue ...');
            
            return 'success';
        }

        return 'Failed!';

    }

    private function parseUrl($prefix,$uri,$nip)
    {
        $bracketStartPosition = strpos($uri,'{');
        
        return $prefix.substr($uri,0,$bracketStartPosition).$nip;
    }

    private function getParameter($uri)
    {
        $bracketStartPosition = strpos($uri,'{');

        $bracketEndPosition = strpos($uri,'}');

        $strLen = $bracketEndPosition-$bracketEndPosition;

        return substr($uri,$bracketStartPosition+1,$strLen-1);
    }

    private function splitter($string)
    {
        $split_string = preg_split('/(?=[A-Z])/',$string);

        return strtolower(implode('_',$split_string));
    }

    private function sendMessage($chat_id = null, $text = null, $reply_to_message_id = null)
    {
        if(!empty($text) && !empty($chat_id))
        {
            $this->bot->sendMessage([
                'reply_to_message_id' => $reply_to_message_id,
                'chat_id' => $chat_id,
                // 'chat_id' => '5906672662',
                'text' => $text
            ]);
        }
    }
}
