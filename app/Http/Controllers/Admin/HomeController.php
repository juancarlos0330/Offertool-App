<?php

namespace App\Http\Controllers\Admin;

use App\Scooter;
use App\ScooterStatus;

class HomeController
{
    public function index()
    {
        $ready_status = ScooterStatus::where('name', 'FINALIZAT')->first();
        $working_status = ScooterStatus::where('name', 'IN LUCRU')->first();
        $ready_scooters =  Scooter::where('status_id', isset($ready_status->id) ? $ready_status->id : 0)->orderBy('created_at', 'asc')->get();
        $working_scooters =  Scooter::where('status_id', isset($working_status->id) ? $working_status->id : 0)->orderBy('created_at', 'asc')->get();

        return view('home', compact('ready_scooters', 'working_scooters'));
    }
}
