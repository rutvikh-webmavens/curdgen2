@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.table.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tables.update", [$table->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="menu_id">{{ trans('cruds.table.fields.menu') }}</label>
                <select class="form-control select2 {{ $errors->has('menu') ? 'is-invalid' : '' }}" name="menu_id" id="menu_id" required>
                    @foreach($menus as $id => $entry)
                        <option value="{{ $id }}" {{ (old('menu_id') ? old('menu_id') : $table->menu->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('menu'))
                    <div class="invalid-feedback">
                        {{ $errors->first('menu') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.menu_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.table.fields.field_type') }}</label>
                <select class="form-control {{ $errors->has('field_type') ? 'is-invalid' : '' }}" name="field_type" id="field_type" required>
                    <option value disabled {{ old('field_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Table::FIELD_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('field_type', $table->field_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('field_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('field_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.field_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="field_name">{{ trans('cruds.table.fields.field_name') }}</label>
                <input class="form-control {{ $errors->has('field_name') ? 'is-invalid' : '' }}" type="text" name="field_name" id="field_name" value="{{ old('field_name', $table->field_name) }}" required>
                @if($errors->has('field_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('field_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.field_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="field_title">{{ trans('cruds.table.fields.field_title') }}</label>
                <input class="form-control {{ $errors->has('field_title') ? 'is-invalid' : '' }}" type="text" name="field_title" id="field_title" value="{{ old('field_title', $table->field_title) }}" required>
                @if($errors->has('field_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('field_title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.field_title_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('in_list') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="in_list" value="0">
                    <input class="form-check-input" type="checkbox" name="in_list" id="in_list" value="1" {{ $table->in_list || old('in_list', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="in_list">{{ trans('cruds.table.fields.in_list') }}</label>
                </div>
                @if($errors->has('in_list'))
                    <div class="invalid-feedback">
                        {{ $errors->first('in_list') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.in_list_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('in_create') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="in_create" value="0">
                    <input class="form-check-input" type="checkbox" name="in_create" id="in_create" value="1" {{ $table->in_create || old('in_create', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="in_create">{{ trans('cruds.table.fields.in_create') }}</label>
                </div>
                @if($errors->has('in_create'))
                    <div class="invalid-feedback">
                        {{ $errors->first('in_create') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.in_create_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('in_edit') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="in_edit" value="0">
                    <input class="form-check-input" type="checkbox" name="in_edit" id="in_edit" value="1" {{ $table->in_edit || old('in_edit', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="in_edit">{{ trans('cruds.table.fields.in_edit') }}</label>
                </div>
                @if($errors->has('in_edit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('in_edit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.in_edit_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_required') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_required" value="0">
                    <input class="form-check-input" type="checkbox" name="is_required" id="is_required" value="1" {{ $table->is_required || old('is_required', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_required">{{ trans('cruds.table.fields.is_required') }}</label>
                </div>
                @if($errors->has('is_required'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_required') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.is_required_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sort_order">{{ trans('cruds.table.fields.sort_order') }}</label>
                <input class="form-control {{ $errors->has('sort_order') ? 'is-invalid' : '' }}" type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $table->sort_order) }}" step="1">
                @if($errors->has('sort_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sort_order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.table.fields.sort_order_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection