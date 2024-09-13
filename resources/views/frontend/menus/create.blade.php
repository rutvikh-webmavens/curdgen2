@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.menu.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.menus.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.menu.fields.type') }}</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Menu::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                            <select class="form-control select2" name="project_id" id="project_id" required>
                                @foreach($projects as $id => $entry)
                                    <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                            <input class="form-control" type="text" name="model_name" id="model_name" value="{{ old('model_name', '') }}" required>
                            @if($errors->has('model_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('model_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.model_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="title">{{ trans('cruds.menu.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="parent_id">{{ trans('cruds.menu.fields.parent') }}</label>
                            <select class="form-control select2" name="parent_id" id="parent_id">
                                @foreach($parents as $id => $entry)
                                    <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                            <input class="form-control" type="text" name="sort_order" id="sort_order" value="{{ old('sort_order', '') }}">
                            @if($errors->has('sort_order'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sort_order') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.sort_order_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="soft_delete" value="0">
                                <input type="checkbox" name="soft_delete" id="soft_delete" value="1" {{ old('soft_delete', 0) == 1 || old('soft_delete') === null ? 'checked' : '' }}>
                                <label for="soft_delete">{{ trans('cruds.menu.fields.soft_delete') }}</label>
                            </div>
                            @if($errors->has('soft_delete'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('soft_delete') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.soft_delete_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="create" value="0">
                                <input type="checkbox" name="create" id="create" value="1" {{ old('create', 0) == 1 || old('create') === null ? 'checked' : '' }}>
                                <label for="create">{{ trans('cruds.menu.fields.create') }}</label>
                            </div>
                            @if($errors->has('create'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('create') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.create_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="edit" value="0">
                                <input type="checkbox" name="edit" id="edit" value="1" {{ old('edit', 0) == 1 || old('edit') === null ? 'checked' : '' }}>
                                <label for="edit">{{ trans('cruds.menu.fields.edit') }}</label>
                            </div>
                            @if($errors->has('edit'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('edit') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.edit_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="show" value="0">
                                <input type="checkbox" name="show" id="show" value="1" {{ old('show', 0) == 1 || old('show') === null ? 'checked' : '' }}>
                                <label for="show">{{ trans('cruds.menu.fields.show') }}</label>
                            </div>
                            @if($errors->has('show'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('show') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.show_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="delete" value="0">
                                <input type="checkbox" name="delete" id="delete" value="1" {{ old('delete', 0) == 1 || old('delete') === null ? 'checked' : '' }}>
                                <label for="delete">{{ trans('cruds.menu.fields.delete') }}</label>
                            </div>
                            @if($errors->has('delete'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('delete') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.delete_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="column_search" value="0">
                                <input type="checkbox" name="column_search" id="column_search" value="1" {{ old('column_search', 0) == 1 ? 'checked' : '' }}>
                                <label for="column_search">{{ trans('cruds.menu.fields.column_search') }}</label>
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
                            <select class="form-control" name="entries_per_page" id="entries_per_page" required>
                                <option value disabled {{ old('entries_per_page', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Menu::ENTRIES_PER_PAGE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('entries_per_page', '100') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                            <input class="form-control" type="text" name="order_by_field_name" id="order_by_field_name" value="{{ old('order_by_field_name', 'id') }}" required>
                            @if($errors->has('order_by_field_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order_by_field_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.menu.fields.order_by_field_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.menu.fields.order_by_desc') }}</label>
                            <select class="form-control" name="order_by_desc" id="order_by_desc" required>
                                <option value disabled {{ old('order_by_desc', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Menu::ORDER_BY_DESC_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('order_by_desc', 'DESC') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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

        </div>
    </div>
</div>
@endsection