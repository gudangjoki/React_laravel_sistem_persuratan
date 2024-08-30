<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LetterManagementController extends Controller
{
    public function create(Request $request) {
        $validate = $request->validate([
            'letter_id' => 'required|string|min:16|max:16',
            'letter_title' => 'required|string',
            'letter_id_type' => 'required|string',
            'letter_keywords' => 'required|array',
            // 'letter_date' => 'required|date',
            'letter_path' => 'required|string'
        ]);
    
        try {
            // $new_letter = new Letter();
            // $new_letter->letter_id_type = $validate['letter_id_type'];
            // $new_letter->letter_title = $validate['letter_title'];
            // $new_letter->letter_type = $validate['letter_type'];
            // $new_letter->letter_path = $validate['letter_path'];
            // $new_letter->save();

            $new_letter = new Letter();
            $new_letter->letter_id_type = $request->letter_id_type;
            $new_letter->letter_title = $request->letter_title;
            $new_letter->letter_type = $request->letter_type;
            $new_letter->letter_path = $request->letter_path;
            $new_letter->save();
    
            // Attach keywords (assuming keywords relationship is correctly defined)
            $new_letter->keywords()->attach($validate['letter_keywords']);
    
            return response()->json(['message' => 'Success create letter'], 200);
        } catch(\Exception $e) {
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
