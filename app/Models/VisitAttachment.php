<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Get the visit that owns this attachment.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the user who uploaded this attachment.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
