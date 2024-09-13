<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, MultiTenantModelTrait, HasFactory;

    public $table = 'projects';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const LANGUAGE_SELECT = [
        'english' => 'english',
        'french'  => 'french',
    ];

    public const CSS_SELECT = [
        'tailwind'  => 'tailwind',
        'bootstrap' => 'bootstrap',
    ];

    public const MODEL_LOCATION_SELECT = [
        'app/Models' => 'app/Models',
        'app'        => 'app',
    ];

    public const TIMEZONE_SELECT = [
        'America/Chicago' => 'America/Chicago',
        'Asia/Kolkata'    => 'Asia/Kolkata',
    ];

    public const DATE_FORMAT_SELECT = [
        'Y-m-d' => 'Y-m-d',
        'm/d/Y' => 'm/d/Y',
        'd-m-Y' => 'd-m-Y',
        'd/m/Y' => 'd/m/Y',
        'd.m.Y' => 'd.m.Y',
    ];

    protected $fillable = [
        'name',
        'css',
        'date_format',
        'language',
        'model_location',
        'timezone',
        'created_at',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function projectMenus()
    {
        return $this->hasMany(Menu::class, 'project_id', 'id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
