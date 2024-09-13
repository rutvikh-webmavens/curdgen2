<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class TablesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('table_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Table::with(['menu', 'team'])->select(sprintf('%s.*', (new Table)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'table_show';
                $editGate      = 'table_edit';
                $deleteGate    = 'table_delete';
                $crudRoutePart = 'tables';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('menu_model_name', function ($row) {
                return $row->menu ? $row->menu->model_name : '';
            });

            $table->editColumn('field_type', function ($row) {
                return $row->field_type ? Table::FIELD_TYPE_SELECT[$row->field_type] : '';
            });
            $table->editColumn('field_name', function ($row) {
                return $row->field_name ? $row->field_name : '';
            });
            $table->editColumn('field_title', function ($row) {
                return $row->field_title ? $row->field_title : '';
            });
            $table->editColumn('in_list', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->in_list ? 'checked' : null) . '>';
            });
            $table->editColumn('in_create', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->in_create ? 'checked' : null) . '>';
            });
            $table->editColumn('in_edit', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->in_edit ? 'checked' : null) . '>';
            });
            $table->editColumn('is_required', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_required ? 'checked' : null) . '>';
            });
            $table->editColumn('sort_order', function ($row) {
                return $row->sort_order ? $row->sort_order : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'menu', 'in_list', 'in_create', 'in_edit', 'is_required']);

            return $table->make(true);
        }

        $menus = Menu::get();
        $teams = Team::get();

        return view('admin.tables.index', compact('menus', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('table_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menus = Menu::pluck('model_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tables.create', compact('menus'));
    }

    public function store(StoreTableRequest $request)
    {
        $table = Table::create($request->all());

        return redirect()->route('admin.tables.index');
    }

    public function edit(Table $table)
    {
        abort_if(Gate::denies('table_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menus = Menu::pluck('model_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $table->load('menu', 'team');

        return view('admin.tables.edit', compact('menus', 'table'));
    }

    public function update(UpdateTableRequest $request, Table $table)
    {
        $table->update($request->all());

        return redirect()->route('admin.tables.index');
    }

    public function show(Table $table)
    {
        abort_if(Gate::denies('table_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $table->load('menu', 'team');

        return view('admin.tables.show', compact('table'));
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
