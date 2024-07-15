<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Uuid\Uuid;

class Layanan extends Model
{
    // use HasFactory;

    protected $fillable = ['layanan', 'tglakses','feedback','media'];

    // public function getRouteKeyName(): string
    // {
    //     return 'layanan';
    // }


    // protected static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         $model->uuid = (string) Uuid::uuid4();
    //     });
    // }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'layanan_id');
    }

    public function hasils(): HasMany
    {
        return $this->hasMany(Hasil::class, 'layanan_id');
    }

//     public function feedback(): HasOne
//     {
//         return $this->hasOne(Feedback::class);
//     }

//     public function village(): BelongsTo
//     {
//         return $this->belongsTo(Village::class);
//     }
}
