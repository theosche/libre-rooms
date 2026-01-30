<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    protected $fillable = [
        'path',
        'original_name',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    /**
     * Get the parent imageable model.
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the public URL for this image.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Delete the image file from storage before deleting the model.
     */
    public function delete(): ?bool
    {
        Storage::disk('public')->delete($this->path);

        return parent::delete();
    }
}
