<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class Kuisioner extends Model
{
  use HasFactory;

  protected $fillable = ['question','aktif'];

  // public function getRouteKeyName(): string
  // {
  //   return 'uuid';
  // }

  // protected static function boot()
  // {
  //   parent::boot();
  //   self::creating(function ($model) {
  //     $model->uuid = (string) Uuid::uuid4();
  //   });
  // }

  public function hasils(): HasMany
  {
    return $this->hasMany(Hasil::class, 'kuesioner_id');
  }

  // public function unsur(): BelongsTo
  //   {
  //       return $this->belongsTo(Unsur::class);
  //   }
}
