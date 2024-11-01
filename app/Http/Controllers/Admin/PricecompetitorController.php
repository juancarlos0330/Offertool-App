<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricecompetitorRequest;
use App\Http\Requests\UpdatePricecompetitorRequest;
use App\Pricecompetitor;
use App\LogHistory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PricecompetitorController extends Controller
{
    public function index()
    {
        $pricetypes = Pricecompetitor::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Price Type List View";
        $loghistory->save();

        return view('admin.pricecompetitor.index', compact('pricetypes'));
    }

    public function create()
    {
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "New Item Create Page";
        $loghistory->save();

        return view('admin.pricecompetitor.create');
    }

    public function store(StorePricecompetitorRequest $request)
    {
        $check = $request->validate([
            'name' => ['required', 'string']
        ]);

        $pricetype = Pricecompetitor::create($request->all());

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.pricecompetitor.show', compact('pricetype'));
    }

    public function edit($id)
    {
        $pricetype = Pricecompetitor::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item Edit Page";
        $loghistory->save();

        return view('admin.pricecompetitor.edit', compact('pricetype'));
    }

    public function update(UpdatePricecompetitorRequest $request, $id)
    {
        $check = $request->validate([
            'name' => ['required', 'string']
        ]);

        $pricetype = Pricecompetitor::findOrFail($id);
        $pricetype->name = $request->name;
        $pricetype->save();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.pricecompetitor.show', compact('pricetype'));
    }

    public function show($id)
    {
        $pricetype = Pricecompetitor::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item View";
        $loghistory->save();

        return view('admin.pricecompetitor.show', compact('pricetype'));
    }

    public function destroy($id)
    {
        $pricetypes = Pricecompetitor::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item deleted";
        $loghistory->save();

        $pricetypes->delete();

        return back();
    }
}
