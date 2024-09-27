<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'relevance',
        'approval_date',
        'upload_date',
        'pdf_path',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at'    => 'datetime:d-m-Y H:i:s',
            'updated_at'    => 'datetime:d-m-Y H:i:s',
            'approval_date' => 'datetime:d-m-Y H:i:s',
            'upload_date'   => 'datetime:d-m-Y H:i:s',
        ];
    }

    /**
     * Cast approval date time.
     */
    protected function approvalDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value instanceof DateTime ? $value : Carbon::createFromFormat('d-m-Y H:i:s', $value),
        );
    }

    /**
     * Cast approval date time.
     */
    protected function uploadDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value instanceof DateTime ? $value : Carbon::createFromFormat('d-m-Y H:i:s', $value),
        );
    }

    public function scopeStartsBefore(Builder $query, $date): Builder
    {
        return $query->whereDate('approval_date', '<=', Carbon::parse($date));
    }

    public function scopeStartsAfter(Builder $query, $date): Builder
    {
        return $query->whereDate('approval_date', '>=', Carbon::parse($date));
    }
}
