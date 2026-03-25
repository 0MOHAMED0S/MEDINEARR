<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'search_type',
        'search_query',
        'medicine_id',
        'lat',
        'lng',
        'results_count',
        'returned_pharmacy_ids',
    ];

    // هذا السطر مهم جداً ليقوم Laravel بتحويل الـ JSON إلى مصفوفة تلقائياً
    protected $casts = [
        'returned_pharmacy_ids' => 'array',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    /**
     * Relationship: The user who made the search (can be null for guests).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: The medicine that was searched for (if search_type is 'medicine').
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}
