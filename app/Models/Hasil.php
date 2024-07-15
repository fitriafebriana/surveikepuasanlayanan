<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Ramsey\Uuid\Uuid;

class Hasil extends Model
{
    use HasFactory;

    protected $fillable = ['answer', 'kuesioner_id', 'layanan_id'];

    // public function getRouteKeyName(): string
    // {
    //     return 'uuid';
    // }

    // protected static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         $model->uuid = (string) Uuid::uuid4();
    //     });
    // }

    public function responden(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function kuisioner(): BelongsTo
    {
        return $this->belongsTo(Kuesioner::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }
}
