<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes, MultiTenantModelTrait, HasFactory;

    public $table = 'menus';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ORDER_BY_DESC_SELECT = [
        'ASC'  => 'ASC',
        'DESC' => 'DESC',
    ];

    public const ENTRIES_PER_PAGE_SELECT = [
        '10'  => '10',
        '50'  => '50',
        '100' => '100',
    ];

    public const TYPE_SELECT = [
        'CRUD'        => 'CRUD',
        'non-CRUD'    => 'non-CRUD',
        'Parent only' => 'Parent only',
    ];

    protected $fillable = [
        'type',
        'project_id',
        'model_name',
        'title',
        'parent_id',
        'created_at',
        'sort_order',
        'soft_delete',
        'create',
        'edit',
        'show',
        'delete',
        'column_search',
        'entries_per_page',
        'order_by_field_name',
        'order_by_desc',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function parentMenus()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function menuTables()
    {
        return $this->hasMany(Table::class, 'menu_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
