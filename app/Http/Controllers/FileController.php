<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    function downloadLetterPdf($fileName) {
        // Path to the file in the 'uploads' directory
        $filePath = public_path("uploads/{$fileName}");
    
        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName);
        } else {
            abort(404, 'File not found');
        }
    }
}
