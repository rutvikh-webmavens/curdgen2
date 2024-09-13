<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMenuRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('menu_edit');
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
            ],
            'project_id' => [
                'required',
                'integer',
            ],
            'model_name' => [
                'string',
                'required',
            ],
            'title' => [
                'string',
                'required',
            ],
            'sort_order' => [
                'string',
                'nullable',
            ],
            'entries_per_page' => [
                'required',
            ],
            'order_by_field_name' => [
                'string',
                'required',
            ],
            'order_by_desc' => [
                'required',
            ],
        ];
    }
}
