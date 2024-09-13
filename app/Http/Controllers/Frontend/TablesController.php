<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTableRequest;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TablesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('table_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tables = Table::with(['menu', 'team'])->get();

        $menus = Menu::get();

        $teams = Team::get();

        return view('frontend.tables.index', compact('menus', 'tables', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('table_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menus = Menu::pluck('model_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.tables.create', compact('menus'));
    }

    public function store(StoreTableRequest $request)
    {
        $table = Table::create($request->all());

        return redirect()->route('frontend.tables.index');
    }

    public function edit(Table $table)
    {
        abort_if(Gate::denies('table_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menus = Menu::pluck('model_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $table->load('menu', 'team');

        return view('frontend.tables.edit', compact('menus', 'table'));
    }

    public function update(UpdateTableRequest $request, Table $table)
    {
        $table->update($request->all());

        return redirect()->route('frontend.tables.index');
    }

    public function show(Table $table)
    {
        abort_if(Gate::denies('table_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $table->load('menu', 'team');

        return view('frontend.tables.show', compact('table'));
    }

    public function destroy(Table $table)
    {
        abort_if(Gate::denies('table_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $table->delete();

        return back();
    }

    public function massDestroy(MassDestroyTableRequest $request)
    {
        $tables = Table::find(request('ids'));

        foreach ($tables as $table) {
            $table->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
