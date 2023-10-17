<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\FunctionHelper;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class);
    }
    
    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'project_type');
    }

    /**
     * Get the EIAS of the project.
     */
    public function eias()
    {
        return $this->hasMany(Eia::class);
    }

    public function getFormattedDateOfCreationAttribute($date)
    {
        return FunctionHelper::dateToTimeZone($this->date_of_creation, 'M d, Y h:i');
    }
}