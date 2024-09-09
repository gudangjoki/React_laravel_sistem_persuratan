<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterKeyword extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->setTable('view_letter_keywords_with_id');
    }
}
