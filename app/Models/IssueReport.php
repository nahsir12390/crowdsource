<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueReport extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'road' => 'Road damage',
        'drainage' => 'Drainage / flooding',
        'electricity' => 'Electricity',
        'water' => 'Water supply',
        'waste' => 'Waste management',
        'public_safety' => 'Public safety',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'pending' => 'Pending review',
        'verified' => 'Verified',
        'in_progress' => 'In progress',
        'resolved' => 'Resolved',
        'rejected' => 'Rejected',
    ];

    public const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    protected $fillable = [
        'reference',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'title',
        'category',
        'description',
        'location',
        'latitude',
        'longitude',
        'photo_path',
        'status',
        'priority',
        'admin_note',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'resolved_at' => 'datetime',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', '!=', 'rejected');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst((string) $this->category);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function priorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst((string) $this->priority);
    }
}
