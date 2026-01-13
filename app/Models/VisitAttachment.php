<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitAttachment extends Model
{
    protected $fillable = [
        'visit_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];
}
