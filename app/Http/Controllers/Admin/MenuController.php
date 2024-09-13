<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMenuRequest;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\Project;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('menu_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Menu::with(['project', 'parent', 'team'])->select(sprintf('%s.*', (new Menu)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'menu_show';
                $editGate      = 'menu_edit';
                $deleteGate    = 'menu_delete';
                $crudRoutePart = 'menus';

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
            $table->editColumn('type', function ($row) {
                return $row->type ? Menu::TYPE_SELECT[$row->type] : '';
            });
            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->editColumn('model_name', function ($row) {
                return $row->model_name ? $row->model_name : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->addColumn('parent_model_name', function ($row) {
                return $row->parent ? $row->parent->model_name : '';
            });

            $table->editColumn('sort_order', function ($row) {
                return $row->sort_order ? $row->sort_order : '';
            });
            $table->editColumn('soft_delete', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->soft_delete ? 'checked' : null) . '>';
            });
            $table->editColumn('create', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->create ? 'checked' : null) . '>';
            });
            $table->editColumn('edit', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->edit ? 'checked' : null) . '>';
            });
            $table->editColumn('show', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->show ? 'checked' : null) . '>';
            });
            $table->editColumn('delete', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->delete ? 'checked' : null) . '>';
            });
            $table->editColumn('column_search', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->column_search ? 'checked' : null) . '>';
            });
            $table->editColumn('entries_per_page', function ($row) {
                return $row->entries_per_page ? Menu::ENTRIES_PER_PAGE_SELECT[$row->entries_per_page] : '';
            });
            $table->editColumn('order_by_field_name', function ($row) {
                return $row->order_by_field_name ? $row->order_by_field_name : '';
            });
            $table->editColumn('order_by_desc', function ($row) {
                return $row->order_by_desc ? Menu::ORDER_BY_DESC_SELECT[$row->order_by_desc] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'project', 'parent', 'soft_delete', 'create', 'edit', 'show', 'delete', 'column_search']);

            return $table->make(true);
        }

        $projects = Project::get();
        $menus    = Menu::get();
        $teams    = Team::get();

        return view('admin.menus.index', compact('projects', 'menus', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('menu_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parents = Menu::pluck('model_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.menus.create', compact('parents', 'projects'));
    }

    public function store(StoreMenuRequest $request)
    {
        $menu = Menu::create($request->all());

        return redirect()->route('admin.menus.index');
    }

    public function edit(Menu $menu)
    {
        abort_if(Gate::denies('menu_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parents = Menu::pluck('model_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $menu->load('project', 'parent', 'team');

        return view('admin.menus.edit', compact('menu', 'parents', 'projects'));
    }

    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $menu->update($request->all());

        return redirect()->route('admin.menus.index');
    }

    public function show(Menu $menu)
    {
        abort_if(Gate::denies('menu_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menu->load('project', 'parent', 'team', 'parentMenus', 'menuTables');

        return view('admin.menus.show', compact('menu'));
    }

    public function destroy(Menu $menu)
    {
        abort_if(Gate::denies('menu_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $menu->delete();

        return back();
    }

    public function massDestroy(MassDestroyMenuRequest $request)
    {
        $menus = Menu::find(request('ids'));

        foreach ($menus as $menu) {
            $menu->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
