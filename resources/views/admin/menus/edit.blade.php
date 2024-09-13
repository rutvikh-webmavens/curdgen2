@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.menu.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.menus.update", [$menu->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.menu.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Menu::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $menu->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.menu.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ (old('project_id') ? old('project_id') : $menu->project->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('project'))
                    <div class="invalid-feedback">
                        {{ $errors->first('project') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="model_name">{{ trans('cruds.menu.fields.model_name') }}</label>
                <input class="form-control {{ $errors->has('model_name') ? 'is-invalid' : '' }}" type="text" name="model_name" id="model_name" value="{{ old('model_name', $menu->model_name) }}" required>
                @if($errors->has('model_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('model_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.model_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.menu.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $menu->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="parent_id">{{ trans('cruds.menu.fields.parent') }}</label>
                <select class="form-control select2 {{ $errors->has('parent') ? 'is-invalid' : '' }}" name="parent_id" id="parent_id">
                    @foreach($parents as $id => $entry)
                        <option value="{{ $id }}" {{ (old('parent_id') ? old('parent_id') : $menu->parent->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('parent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.parent_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sort_order">{{ trans('cruds.menu.fields.sort_order') }}</label>
                <input class="form-control {{ $errors->has('sort_order') ? 'is-invalid' : '' }}" type="text" name="sort_order" id="sort_order" value="{{ old('sort_order', $menu->sort_order) }}">
                @if($errors->has('sort_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sort_order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.sort_order_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('soft_delete') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="soft_delete" value="0">
                    <input class="form-check-input" type="checkbox" name="soft_delete" id="soft_delete" value="1" {{ $menu->soft_delete || old('soft_delete', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="soft_delete">{{ trans('cruds.menu.fields.soft_delete') }}</label>
                </div>
                @if($errors->has('soft_delete'))
                    <div class="invalid-feedback">
                        {{ $errors->first('soft_delete') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.soft_delete_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('create') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="create" value="0">
                    <input class="form-check-input" type="checkbox" name="create" id="create" value="1" {{ $menu->create || old('create', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="create">{{ trans('cruds.menu.fields.create') }}</label>
                </div>
                @if($errors->has('create'))
                    <div class="invalid-feedback">
                        {{ $errors->first('create') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.create_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('edit') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="edit" value="0">
                    <input class="form-check-input" type="checkbox" name="edit" id="edit" value="1" {{ $menu->edit || old('edit', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="edit">{{ trans('cruds.menu.fields.edit') }}</label>
                </div>
                @if($errors->has('edit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('edit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.edit_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('show') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="show" value="0">
                    <input class="form-check-input" type="checkbox" name="show" id="show" value="1" {{ $menu->show || old('show', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="show">{{ trans('cruds.menu.fields.show') }}</label>
                </div>
                @if($errors->has('show'))
                    <div class="invalid-feedback">
                        {{ $errors->first('show') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.show_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('delete') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="delete" value="0">
                    <input class="form-check-input" type="checkbox" name="delete" id="delete" value="1" {{ $menu->delete || old('delete', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="delete">{{ trans('cruds.menu.fields.delete') }}</label>
                </div>
                @if($errors->has('delete'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delete') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.delete_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('column_search') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="column_search" value="0">
                    <input class="form-check-input" type="checkbox" name="column_search" id="column_search" value="1" {{ $menu->column_search || old('column_search', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="column_search">{{ trans('cruds.menu.fields.column_search') }}</label>
                </div>
                @if($errors->has('column_search'))
                    <div class="invalid-feedback">
                        {{ $errors->first('column_search') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.column_search_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.menu.fields.entries_per_page') }}</label>
                <select class="form-control {{ $errors->has('entries_per_page') ? 'is-invalid' : '' }}" name="entries_per_page" id="entries_per_page" required>
                    <option value disabled {{ old('entries_per_page', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Menu::ENTRIES_PER_PAGE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('entries_per_page', $menu->entries_per_page) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('entries_per_page'))
                    <div class="invalid-feedback">
                        {{ $errors->first('entries_per_page') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.entries_per_page_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="order_by_field_name">{{ trans('cruds.menu.fields.order_by_field_name') }}</label>
                <input class="form-control {{ $errors->has('order_by_field_name') ? 'is-invalid' : '' }}" type="text" name="order_by_field_name" id="order_by_field_name" value="{{ old('order_by_field_name', $menu->order_by_field_name) }}" required>
                @if($errors->has('order_by_field_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_by_field_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.order_by_field_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.menu.fields.order_by_desc') }}</label>
                <select class="form-control {{ $errors->has('order_by_desc') ? 'is-invalid' : '' }}" name="order_by_desc" id="order_by_desc" required>
                    <option value disabled {{ old('order_by_desc', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Menu::ORDER_BY_DESC_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('order_by_desc', $menu->order_by_desc) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('order_by_desc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_by_desc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.menu.fields.order_by_desc_helper') }}</span>
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