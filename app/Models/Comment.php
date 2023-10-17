<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['document_id','comment', 'commented_by'];

    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
