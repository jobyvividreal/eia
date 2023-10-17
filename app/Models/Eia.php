<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\FunctionHelper;

class Eia extends Model
{
    use HasFactory;
    use RevisionableTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function stage()
    {
        return $this->belongsTo(EiaStage::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class)->where('parent_id', 0)->orderBy('id', 'DESC');
    }

    public function getFormattedDateOfEntryAttribute()
    {
        return FunctionHelper::dateToTimeZone($this->date_of_entry, 'd/m/Y h:i');
    }
}