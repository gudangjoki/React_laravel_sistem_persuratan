<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LetterManagementController extends Controller
{
    public function create(Request $request) {
        $validate = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'letter_title' => 'required|string',
            'letter_type' => 'required|email',
            'letter_keywords' => 'required|array',
            'date' => 'required|date',
            'letter_path' => 'required|string'
        ]);
    }
}
