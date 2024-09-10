<?php

namespace App;

class LetterType
{
    public $id;
    public $letter_type_name;

    public function __construct($id, $letter_type_name)
    {
        $this->id = $id;
        $this->letter_type_name = $letter_type_name;
    }
}
