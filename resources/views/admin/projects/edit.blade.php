@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.project.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.projects.update", [$project->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.project.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.project.fields.css') }}</label>
                <select class="form-control {{ $errors->has('css') ? 'is-invalid' : '' }}" name="css" id="css" required>
                    <option value disabled {{ old('css', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Project::CSS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('css', $project->css) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('css'))
                    <div class="invalid-feedback">
                        {{ $errors->first('css') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.css_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.project.fields.date_format') }}</label>
                <select class="form-control {{ $errors->has('date_format') ? 'is-invalid' : '' }}" name="date_format" id="date_format" required>
                    <option value disabled {{ old('date_format', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Project::DATE_FORMAT_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('date_format', $project->date_format) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('date_format'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_format') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.date_format_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.project.fields.language') }}</label>
                <select class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" name="language" id="language" required>
                    <option value disabled {{ old('language', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Project::LANGUAGE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('language', $project->language) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.project.fields.model_location') }}</label>
                <select class="form-control {{ $errors->has('model_location') ? 'is-invalid' : '' }}" name="model_location" id="model_location" required>
                    <option value disabled {{ old('model_location', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Project::MODEL_LOCATION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('model_location', $project->model_location) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('model_location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('model_location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.model_location_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.project.fields.timezone') }}</label>
                <select class="form-control {{ $errors->has('timezone') ? 'is-invalid' : '' }}" name="timezone" id="timezone" required>
                    <option value disabled {{ old('timezone', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Project::TIMEZONE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('timezone', $project->timezone) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('timezone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('timezone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.project.fields.timezone_helper') }}</span>
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