<?php

namespace App\Http\Requests;

use App\Models\Project;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('project_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'css' => [
                'required',
            ],
            'date_format' => [
                'required',
            ],
            'language' => [
                'required',
            ],
            'model_location' => [
                'required',
            ],
            'timezone' => [
                'required',
            ],
        ];
    }
}
