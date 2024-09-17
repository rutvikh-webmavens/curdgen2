<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes, MultiTenantModelTrait, HasFactory;

    public $table = 'tables';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const FIELD_TYPE_SELECT = [
        'text'     => 'Text',
        'email'    => 'Email',
        'textarea' => 'textarea',
        'password' => 'password',
        'radio' =>  'radio',
        'select' => 'select',
        'checkbox' => 'checkbox',
        'integer' => 'integer',
        'float' => 'float',
        'decimal' => 'decimal',
        'date' => 'date',
        'date_time' => 'date time',
        'time' => 'time',
    ];

    protected $fillable = [
        'menu_id',
        'field_type',
        'field_name',
        'field_title',
        'in_list',
        'in_create',
        'in_edit',
        'is_required',
        'sort_order',
        'key_value_set',
        'default_value',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
