<?php

namespace App\Http\Controllers\Admin;

use App\LogHistory;
use Illuminate\Support\Facades\DB;

class HistoryController
{
    public function index()
    {
        $loghistories = DB::table('loghistories')->leftJoin('users', 'loghistories.user_id', '=', 'users.id')->get();

        return view('admin.history', compact('loghistories'));
    }
}
