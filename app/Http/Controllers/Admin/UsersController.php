<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use App\Pricerole;
use App\Pricetype;
use App\LogHistory;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();

        $priceroles = DB::table('priceroles')->leftJoin('pricetypes', 'priceroles.pricetype_id', '=', 'pricetypes.id')->whereNull('priceroles.deleted_at')->select('priceroles.*', 'pricetypes.name', 'pricetypes.id as ptid')->get();

        return view('admin.users.index', compact('users', 'priceroles'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request, User $user)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function priceroleupdate(Request $request, $id) {
        Pricerole::where('user_id', $id)->delete();
        
        if($request->priceroles != "") {
            foreach($request->priceroles as $item) {
                $priceroles = new Pricerole();
                $priceroles->user_id = $id;
                $priceroles->pricetype_id = $item;
                $priceroles->save();
            }
        }

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "users";
        $loghistory->action = "Added Pricetype by user";
        $loghistory->save();

        return back();
    }

    public function pricerole($seluserid) {
        $pricetypes = Pricetype::all();
        
        return view('admin.users.priceroles', compact('pricetypes', 'seluserid'));
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
