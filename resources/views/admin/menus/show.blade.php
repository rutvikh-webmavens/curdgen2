@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.menu.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.menus.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.id') }}
                        </th>
                        <td>
                            {{ $menu->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Menu::TYPE_SELECT[$menu->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.project') }}
                        </th>
                        <td>
                            {{ $menu->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.model_name') }}
                        </th>
                        <td>
                            {{ $menu->model_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.title') }}
                        </th>
                        <td>
                            {{ $menu->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.parent') }}
                        </th>
                        <td>
                            {{ $menu->parent->model_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.sort_order') }}
                        </th>
                        <td>
                            {{ $menu->sort_order }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.soft_delete') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->soft_delete ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.create') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->create ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.edit') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->edit ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.show') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->show ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.delete') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->delete ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.column_search') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $menu->column_search ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.entries_per_page') }}
                        </th>
                        <td>
                            {{ App\Models\Menu::ENTRIES_PER_PAGE_SELECT[$menu->entries_per_page] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.order_by_field_name') }}
                        </th>
                        <td>
                            {{ $menu->order_by_field_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.menu.fields.order_by_desc') }}
                        </th>
                        <td>
                            {{ App\Models\Menu::ORDER_BY_DESC_SELECT[$menu->order_by_desc] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.menus.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#parent_menus" role="tab" data-toggle="tab">
                {{ trans('cruds.menu.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#menu_tables" role="tab" data-toggle="tab">
                {{ trans('cruds.table.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="parent_menus">
            @includeIf('admin.menus.relationships.parentMenus', ['menus' => $menu->parentMenus])
        </div>
        <div class="tab-pane" role="tabpanel" id="menu_tables">
            @includeIf('admin.menus.relationships.menuTables', ['tables' => $menu->menuTables])
        </div>
    </div>
</div>

@endsection