<?php

namespace App\Http\Requests;

use App\Models\Table;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTableRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('table_edit');
    }

    public function rules()
    {
        return [
            'menu_id' => [
                'required',
                'integer',
            ],
            'field_type' => [
                'required',
            ],
            'field_name' => [
                'string',
                'required',
            ],
            'field_title' => [
                'string',
                'required',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
