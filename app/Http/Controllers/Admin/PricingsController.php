<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricingRequest;
use App\Http\Requests\UpdatePricingRequest;
use App\Pricing;
use App\LogHistory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PricingsController extends Controller
{
    public function index()
    {
        $priceclasses =  Pricing::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "Pricing Class View";
        $loghistory->save();

        return view('admin.priceclass.index', compact('priceclasses'));
    }

    public function create()
    {
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "New Item Creating Page";
        $loghistory->save();

        return view('admin.priceclass.create');
    }

    public function store(StorePricingRequest $request, Pricing $pricing)
    {
        $check = $request->validate([
            'description' => ['required', 'string'],
            'multiplier' => ['required', 'numeric']
        ]);
            
        $pricing = Pricing::create($request->all());

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.priceclass.show', compact('pricing'));
    }

    public function edit($id)
    {
        $pricing = Pricing::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "Existing Item Editing Page";
        $loghistory->save();

        return view('admin.priceclass.edit', compact('pricing'));
    }

    public function update(UpdatePricingRequest $request, $id)
    {
        $check = $request->validate([
            'description' => ['required', 'string'],
            'multiplier' => ['required', 'numeric']
        ]);

        $pricing = Pricing::findOrFail($id);
        $pricing->description = $request->description;
        $pricing->multiplier = $request->multiplier;
        $pricing->save();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.priceclass.show', compact('pricing'));
    }

    public function show($id)
    {
        $pricing = Pricing::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "Existing Item Showing Page";
        $loghistory->save();

        return view('admin.priceclass.show', compact('pricing'));
    }

    public function destroy($id)
    {
        $pricing = Pricing::findOrFail($id);

        $pricing->delete();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricings";
        $loghistory->action = "Existing Item Deleted";
        $loghistory->save();

        return back();
    }
}
