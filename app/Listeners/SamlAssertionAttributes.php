<?php

namespace App\Listeners;

use LightSaml\ClaimTypes;
use LightSaml\Model\Assertion\Attribute;
use CodeGreenCreative\SamlIdp\Events\Assertion;

class SamlAssertionAttributes
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Assertion $event)
    {
        // $jabatan_struktural_id = auth()->user()->V2Profile->jabatan_struktural_id;
        // $jabatan_fungsional_id = auth()->user()->V2Profile->jabatan_fungsional_id;
        // $jabatan_fungsional_umum_id = auth()->user()->V2Profile->jabatan_fungsional_umum_id;

        // $is_struktural = false;
        // $jabatan_id = null;

        // if(!empty($jabatan_struktural_id) && $jabatan_struktural_id != '')
        // {
        //     $is_struktural = true;
        // }

        // if(!empty($jabatan_struktural_id) && $jabatan_struktural_id != ''){
        //     $jabatan_id = $jabatan_struktural_id;
        // }else if(!empty($jabatan_fungsional_id) && $jabatan_fungsional_id != ''){
        //     $jabatan_id = $jabatan_fungsional_id;
        // }else{
        //     $jabatan_id = $jabatan_fungsional_umum_id;
        // }

        $event->attribute_statement

        // ->addAttribute(new Attribute(ClaimTypes::PPID, auth()->user()->id))
        // ->addAttribute(new Attribute(ClaimTypes::NAME, auth()->user()->username));

        ->addAttribute(new Attribute('PNS_ID', auth()->user()->V2Profile->id))
        
        ->addAttribute(new Attribute('NIP_BARU', auth()->user()->V2Profile->nip_baru))
        
        ->addAttribute(new Attribute('NAMA', auth()->user()->V2Profile->nama))
        
        // ->addAttribute(new Attribute('JABATAN_ID', $jabatan_id))

        ->addAttribute(new Attribute('JABATAN_NAMA', auth()->user()->V2Profile->jabatan_nama))

        // ->addAttribute(new Attribute('IS_STRUKTURAL', $is_struktural))
        
        ->addAttribute(new Attribute('UNOR_ID', auth()->user()->V2Profile->unor_id))
        
        ->addAttribute(new Attribute('UNOR_NAMA', auth()->user()->V2Profile->unor_nama))
        
        ->addAttribute(new Attribute('UNOR_INDUK_ID', auth()->user()->V2Profile->unor_induk_id))

        ->addAttribute(new Attribute('UNOR_INDUK_NAMA', auth()->user()->V2Profile->unor_induk_nama));
    }
}
