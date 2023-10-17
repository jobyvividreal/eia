<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;
    protected $fillable = ['eia_id', 'created_by', 'date_of_approval'];

    public function eia()
    {
        return $this->belongsTo(Eia::class);
    }

    public function status()
    {
        return $this->belongsTo(DocumentStatus::class);
    }
}
