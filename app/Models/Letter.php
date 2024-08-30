<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $table = 'letters';

    protected $fillable = [
        'letter_id',
        'letter_title',
        'letter_type',
        'letter_keywords',
        'letter_path'
    ];

    public function keywords() {
        return $this->belongsToMany(Keyword::class);
    }

    public function letters() {
        return $this->belongsToMany(Letter::class);
    }
}
