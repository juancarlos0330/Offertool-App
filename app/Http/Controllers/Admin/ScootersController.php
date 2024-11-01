<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Price;
use App\Pricetype;
use App\LogHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PriceImport;
use App\Services\SMSGatewayService;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class ScootersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scooter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prices = DB::table('prices')->leftJoin('pricetypes', 'prices.pricetype_id', '=', 'pricetypes.id')->whereNull('prices.deleted_at')->select('prices.*', 'pricetypes.name', 'pricetypes.id as ptid')->get();
        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Price List View";
        $loghistory->save();

        return view('admin.prices.index', compact('prices', 'pricetypes'));
    }
    
    public function filterList($id) {
        $prices = DB::table('prices')->leftJoin('pricetypes', 'prices.pricetype_id', '=', 'pricetypes.id')->whereNull('prices.deleted_at')->where('prices.pricetype_id', $id)->select('prices.*', 'pricetypes.name', 'pricetypes.id as ptid')->get();
        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Price List by type (".$id.") View";
        $loghistory->save();

        return view('admin.prices.index', compact('prices', 'pricetypes'));
    }

    public function create()
    {
        abort_if(Gate::denies('scooter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "New Item Creating Page";
        $loghistory->save();

        return view('admin.prices.create', compact('pricetypes'));
    }

    public function store(StorePriceRequest $request, Price $price)
    {
        $check = $request->validate([
            'itemcode' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'pricetype_id' => ['required', 'numeric'],
        ]);

        $price = Price::create($request->all());
        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.prices.show', compact('price', 'pricetypes'));
    }

    public function edit(Price $price, $id)
    {
        abort_if(Gate::denies('scooter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price = Price::findOrFail($id);

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Editing Page";
        $loghistory->save();

        return view('admin.prices.edit', compact('price', 'pricetypes'));
    }

    public function update(UpdatePriceRequest $request, Price $price, SMSGatewayService $SMSGateway, $id)
    {
        $check = $request->validate([
            'itemcode' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'pricetype_id' => ['required', 'numeric'],
        ]);

        $price = Price::findOrFail($id);
        $price->itemcode = $request->itemcode;
        $price->description = $request->description;
        $price->price = $request->price;
        $price->pricetype_id = $request->pricetype_id;
        $price->save();

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.prices.show', compact('price', 'pricetypes'));

    }

    public function show(Price $price, $id)
    {
        abort_if(Gate::denies('scooter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price = Price::findOrFail($id);

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Showing Page";
        $loghistory->save();

        return view('admin.prices.show', compact('price', 'pricetypes'));
    }

    public function destroy(Price $price, $id)
    {
        abort_if(Gate::denies('scooter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price = Price::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Deleted";
        $loghistory->save();

        $price->delete();
        return back();
    }

    public function deletebytype(Request $request) {
        $check = $request->validate([
            'pricetype_id' => ['required', 'numeric'],
        ]);

        Price::where('pricetype_id', $request->pricetype_id)->delete();
        return back();
    }

    public function deleteAll() {
        Price::truncate();
        return back();
    }

    public function excelimport()
    {
        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Excel Import Showing Page";
        $loghistory->save();

        return view('admin.prices.import', compact('pricetypes'));
    }

    public function import(Request $request)
    {
        if($request->hasFile('file')) {
            try {
                Session::put('pricetype_id', $request->pricetype_id);
                Excel::import(new PriceImport, $request->file);
                return back();
            } catch (\Exception $exp) {
                return back();
            }
        }
    }
}
