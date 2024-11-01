<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Price;
use App\Pricecompare;
use App\Pricecompetitor;
use App\Pricetype;
use App\LogHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PriceImport;
use App\Services\SMSGatewayService;
use Gate;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class PricecompareController extends Controller
{
    public function index($id)
    {
        $prices = DB::table('prices')->leftJoin('pricetypes', 'prices.pricetype_id', '=', 'pricetypes.id')->whereNull('prices.deleted_at')->where('prices.pricetype_id', $id)->select('prices.*', 'pricetypes.name', 'pricetypes.id as ptid')->get();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Price List by type (".$id.") View";
        $loghistory->save();

        return view('admin.pricecompare.index', compact('prices', 'id'));
    }

    public function allcompetitors() {
        $pricecompetitors = Pricecompetitor::all();
        $userid = auth()->user()->id;

        $pricecompares = DB::table('pricecompares')->leftJoin('pricecompetitors', 'pricecompares.competitor_id', '=', 'pricecompetitors.id')->where('pricecompares.user_id', $userid)->whereNull('pricecompares.deleted_at')->select('pricecompetitors.name', 'pricecompares.*')->get();
        
        $prices = Price::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Price All Competitors by ".auth()->user()->name." View";
        $loghistory->save();

        return view('admin.pricecompare.allcompetitor', compact('prices', 'pricecompares', 'pricecompetitors'));
    }

    public function create($pid, $id)
    {
        $price = Price::findOrFail($id);
        $pricecompetitors = Pricecompetitor::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecomparess";
        $loghistory->action = "New Price Competitor Item Creating Page";
        $loghistory->save();

        return view('admin.pricecompare.create', compact('price', 'pid', 'pricecompetitors'));
    }

    public function onestore(Request $request)
    {
        $check = $request->validate([
            'id' => ['required'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'competitor_id' => ['required', 'numeric'],
        ]);

        $pricecompare = Pricecompare::create([
            'user_id' => auth()->user()->id,
            'price_id' => $request->id,
            'competitor_id' => $request->competitor_id,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        $pid = $request->pid;

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "New Price Competitor Item Saved";
        $loghistory->save();

        return back();
    }

    public function store(Request $request)
    {
        $check = $request->validate([
            'id' => ['required'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'competitor_id' => ['required', 'numeric'],
        ]);

        $pricecompare = Pricecompare::create([
            'user_id' => auth()->user()->id,
            'price_id' => $request->id,
            'competitor_id' => $request->competitor_id,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        $pid = $request->pid;

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "New Price Competitor Item Saved";
        $loghistory->save();

        return view('admin.pricecompare.show', compact('pricecompare', 'pid'));
    }

    public function edit($pid, $id)
    {
        $pricecompare = Pricecompare::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Existing Price Competitor Item Editing Page";
        $loghistory->save();

        return view('admin.pricecompare.edit', compact('pricecompare', 'pid'));
    }

    public function update(Request $request)
    {
        $check = $request->validate([
            'id' => ['required'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
        ]);

        $pricecompare = Pricecompare::findOrFail($request->id);
        $pricecompare->description = $request->description;
        $pricecompare->price = $request->price;
        $pricecompare->save();

        $pid = $request->pid;

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Existing Price List Competitor Item Updated";
        $loghistory->save();
        
        return view('admin.pricecompare.show', compact('pricecompare', 'pid'));
    }

    public function show($pid, $id)
    {
        $price = Price::findOrFail($id);

        $userid = auth()->user()->id;

        $pricecompares = DB::table('pricecompares')->leftJoin('pricecompetitors', 'pricecompares.competitor_id', '=', 'pricecompetitors.id')->where('pricecompares.price_id', $id)->where('pricecompares.user_id', $userid)->whereNull('pricecompares.deleted_at')->select('pricecompetitors.name', 'pricecompares.*')->get();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Existing Price List Competitor Item Showing Page";
        $loghistory->save();

        return view('admin.pricecompare.allshow', compact('price', 'pricecompares', 'pid'));
    }

    public function destroy($id)
    {
        $pricecompare = Pricecompare::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Existing List Competitor Item Deleted";
        $loghistory->save();

        $pricecompare->delete();

        return back();
    }

    public function viewForPDF() {
        $userid = auth()->user()->id;
        $pricecompares = DB::table('pricecompares')->leftJoin('pricecompetitors', 'pricecompares.competitor_id', '=', 'pricecompetitors.id')->where('pricecompares.user_id', $userid)->whereNull('pricecompares.deleted_at')->select('pricecompetitors.name', 'pricecompares.*')->get();
        $pricecompetitors = Pricecompetitor::all();
        $prices = Price::all();
        return view('admin.pricecompare.pdf', compact('prices', 'pricecompares', 'pricecompetitors'));
    }

    public function generatePDF(Request $request) {
        $userid = auth()->user()->id;
        $prices = Price::all();
        $pricecompares = DB::table('pricecompares')->leftJoin('pricecompetitors', 'pricecompares.competitor_id', '=', 'pricecompetitors.id')->where('pricecompares.user_id', $userid)->whereNull('pricecompares.deleted_at')->select('pricecompetitors.name', 'pricecompares.*')->get();
        $pricecompetitors = Pricecompetitor::whereIn('id', $request->pricecompetitors)->whereNull('deleted_at')->get();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompares";
        $loghistory->action = "Generated Price List Competitors as PDF by ".auth()->user()->name;
        $loghistory->save();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif', 'images' => true])->loadView('admin.pricecompare.pdf', ['prices' => $prices, 'pricecompares' => $pricecompares, 'pricecompetitors' => $pricecompetitors]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('view_price_list_comparation-' . time() . '.pdf');
    }
}
