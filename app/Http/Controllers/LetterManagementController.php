<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\Letter;
use App\Models\LetterInformation;
use App\Models\LetterKeyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LetterManagementController extends Controller
{
    public function get_all_keywords(Request $request) {
        $index = $request->query('index') ?? 0;
        $search = $request->query('search') ?? '';
    
        $keywords = Keyword::query();
    
        if (!empty($search)) {
            $keywords->where('keyword_name', 'like', $search . '%');
        }
    
        if ($index >= 0) {
            $keywords = $keywords->offset($index * 10)->limit(10)->get();
        } else {
            return response()->json([
                'success' => false,
                'error' => "Index query param can't be less than zero or a negative value"
            ], 400);
        }
    
        return response()->json([
            'index' => $index,
            'keywords' => $keywords
        ], 200);
    }
    

    public function create_letter(Request $request) {
        $payload = $request->get('jwt_payload');

        $validate = $request->validate([
            'letter_no' => 'required|string|min:16|max:16',
            'letter_title' => 'required|string',
            'letter_id_type' => 'required|integer',
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

            $author = $payload['sub'];

            $uuid_letter = Str::uuid();

            $new_letter = new Letter();
            $new_letter->letter_id = $uuid_letter;
            $new_letter->letter_id_type = $request->letter_id_type;
            $new_letter->letter_no = $request->letter_no;
            $new_letter->letter_title = $request->letter_title;
            $new_letter->letter_path = $request->letter_path;
            $new_letter->email = $author;
            $new_letter->save();

            $new_letter->keywords()->attach($validate['letter_keywords'], ['letter_id' => $new_letter->letter_id]);
    
            return response()->json(['success' => true, 'message' => 'Success create letter'], 200);

        } catch(\Exception $e) {
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function view_detail(Request $request, string $uuid) {
        $letter = Letter::where('letter_id', $uuid)->first();

        $keywordsLetter = LetterKeyword::where('letter_id', $uuid)->get(['id', 'keyword_name']);

        return response()->json(['data' => $letter, 'keywords_data' => $keywordsLetter], 200);
    }

    public function edit_detail(Request $request, string $uuid) {
        $validator = Validator::make($request->all(), [
            'letter_title' => 'required|string',
            'letter_id_type' => 'required|integer',
            'letter_keywords' => 'required|array',
            'letter_path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // return response()->json(['message' => $request->all()], 200);

        Letter::where('letter_id', $uuid)->update([
            'letter_title' => $request->letter_title,
            'letter_id_type' => $request->letter_id_type,
            'letter_path' => $request->letter_path
        ]);
        
        $new_letter = new Letter();

        // $new_letter->keywords()->attach($request->letter_keywords, ['letter_id' => $uuid]);

        foreach ($request->letter_keywords as $keyword) {
            if (!DB::table('letter_keywords')->where('keyword_id', $keyword)->where('letter_id', $uuid)->first()) {
                DB::table('letter_keywords')->insert(['keyword_id' => $keyword, 'letter_id' => $uuid]);
            }
        }

        $new_letter->deleteKeywordFromLetter($request->letter_keywords, $uuid);

        return response()->json(['message' => 'successfully edit letter'], 200);
    }

    public function get_all_letters(Request $request) {
        $keyword = $request->query('keyword') ?? '';
        $type = $request->query('type') ?? '';
        $author = $request->query('author') ?? '';
        $index = $request->query('index') ?? 0;
    
        $query = LetterInformation::query();
    
        if ($keyword != '') {
            $query->where(function($q) use ($keyword) {
                $q->where('letter_title', 'like', '%' . $keyword . '%')
                    ->orWhere('letter_no', 'like', '%' . $keyword . '%')
                    ->orWhere('author_email', 'like', '%' . $keyword . '%')
                    ->orWhere('author_name', 'like', '%' . $keyword . '%')
                    ->orWhere('author_username', 'like', '%' . $keyword . '%');
          });
        }
    
        if ($type != '') {
            $query->where('letter_type', $type);
        }

       if ($index > 0) {
            $query->offset($index . '0')->limit(10);
        } else if ($index == 0) {
            $query->offset(0)->limit(10);
        } else {
            return response()->json([
                'success' => false,
                'error' => "index query param can't be less than zero or minus value"
            ], 400);
        }
    
        $keywords = $query->get();

        $letterDetails = [];

        foreach ($keywords as $key) {
            $letter_keys = LetterKeyword::where('letter_id', $key->letter_id)->pluck('keyword_name');
            $letterDetail = new \App\LetterDetail(
                $key->letter_id,
                $key->letter_no,
                $key->letter_title,
                $key->letter_path,
                $key->letter_type,
                $key->author_email,
                $key->author_name,
                $key->author_username,
                $letter_keys,
                $key->letter_created_at
            );
        
            $letterDetails[] = $letterDetail;
        }
    
        return response()->json(['index' => $index, 'data' => $letterDetails], 200);
    }
    
    function getAllLetterTypes() {
        $types = DB::table('letter_types')->get();

        return response()->json([
            'success' => true,
            'types' => $types
        ], 200);
    }

    function uploadFile(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        if ($request->hasFile('file')) {
            // Get the uploaded file
            $file = $request->file('file');

            // Define the file name and store it in the "uploads" directory
            $filePath = $file->storeAs('uploads', time() . '_' . $file->getClientOriginalName(), 'public');

            // Return success response with file path
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_path' => Storage::url($filePath),
            ]);
        }

        // If file is not provided
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
