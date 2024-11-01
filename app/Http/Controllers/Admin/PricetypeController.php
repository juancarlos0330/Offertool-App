<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricetypeRequest;
use App\Http\Requests\UpdatePricetypeRequest;
use App\Pricetype;
use App\LogHistory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PricetypeController extends Controller
{
    public function index()
    {
        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "Price Type List View";
        $loghistory->save();

        return view('admin.pricetype.index', compact('pricetypes'));
    }

    public function create()
    {
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "New Item Create Page";
        $loghistory->save();

        return view('admin.pricetype.create');
    }

    public function store(StorePricetypeRequest $request)
    {
        $check = $request->validate([
            'name' => ['required', 'string']
        ]);

        $pricetype = Pricetype::create($request->all());

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.pricetype.show', compact('pricetype'));
    }

    public function edit($id)
    {
        $pricetype = Pricetype::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "Existing Item Edit Page";
        $loghistory->save();

        return view('admin.pricetype.edit', compact('pricetype'));
    }

    public function update(UpdatePricetypeRequest $request, $id)
    {
        $check = $request->validate([
            'name' => ['required', 'string']
        ]);

        $pricetype = Pricetype::findOrFail($id);
        $pricetype->name = $request->name;
        $pricetype->save();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.pricetype.show', compact('pricetype'));
    }

    public function show($id)
    {
        $pricetype = Pricetype::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "Existing Item View";
        $loghistory->save();

        return view('admin.pricetype.show', compact('pricetype'));
    }

    public function destroy($id)
    {
        $pricetypes = Pricetype::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricetypes";
        $loghistory->action = "Existing Item deleted";
        $loghistory->save();

        $pricetypes->delete();

        return back();
    }
}
