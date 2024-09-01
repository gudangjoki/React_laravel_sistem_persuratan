<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        return $this->belongsToMany(Keyword::class, 'letter_keywords', 'letter_id', 'keyword_id');
    }

    public function letters() {
        return $this->belongsToMany(Letter::class);
    }

    public function updateLetterKeywords($validate, $uuid)
    {
        foreach ($validate as $keyword_id) {
            $this->keywords()->updateExistingPivot($keyword_id, ['letter_id' => $uuid]);
        }
    }

    public function deleteKeywordFromLetter($validate, $uuid) {
        DB::table('letter_keywords')->where('letter_id', $uuid)
            ->chunkById(100, function (Collection $letters) use ($validate) {
                foreach ($letters as $letter) {
                    if (!in_array($letter->keyword_id, $validate)) {
                        DB::table('letter_keywords')
                        ->where('letter_id', $letter->letter_id)
                        ->where('keyword_id', $letter->keyword_id)
                        ->delete();
                    }
                }
            }, 'keyword_id');
    }
}
