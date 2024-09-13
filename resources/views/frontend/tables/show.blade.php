@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.table.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.tables.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $table->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.menu') }}
                                    </th>
                                    <td>
                                        {{ $table->menu->model_name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.field_type') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Table::FIELD_TYPE_SELECT[$table->field_type] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.field_name') }}
                                    </th>
                                    <td>
                                        {{ $table->field_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.field_title') }}
                                    </th>
                                    <td>
                                        {{ $table->field_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.in_list') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $table->in_list ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.in_create') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $table->in_create ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.in_edit') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $table->in_edit ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.is_required') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $table->is_required ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.table.fields.sort_order') }}
                                    </th>
                                    <td>
                                        {{ $table->sort_order }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.tables.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection