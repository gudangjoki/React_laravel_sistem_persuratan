<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterInformation extends Model
{
    use HasFactory;

    // protected $table = 'view_letter_combined;
    // protected $primaryKey = 'letter_id';

    public function __construct()
    {
        $this->setTable('view_letter_combined');
    }
}
