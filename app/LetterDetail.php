<?php

namespace App;

class LetterDetail
{
    public $letter_id;
    public $letter_no;
    public $letter_title;
    public $letter_path;
    public $letter_type;
    public $author_email;
    public $author_name;
    public $author_username;
    public $letter_keywords;
    public $letter_created_at;

    public function __construct($letter_id, $letter_no, $letter_title, $letter_path, $letter_type, $author_email, $author_name, $author_username, $letter_keywords, $letter_created_at) {
        $this->letter_id = $letter_id;
        $this->letter_no = $letter_no;
        $this->letter_title = $letter_title;
        $this->letter_path = $letter_path;
        $this->letter_type = $letter_type;
        $this->author_email = $author_email;
        $this->author_name = $author_name;
        $this->author_username = $author_username;
        $this->letter_keywords = $letter_keywords;
        $this->letter_created_at = $letter_created_at;
    }
}
