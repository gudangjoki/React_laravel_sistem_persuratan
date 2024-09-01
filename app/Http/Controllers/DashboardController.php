<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LetterInformation;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function checkCountEachLetterType() {
        try {
            $count_letter_type = LetterInformation::groupBy('letter_type')->select('letter_type', DB::raw('count(*) as total_letter_type'))->get();
            $sum_all = 0;
            foreach ($count_letter_type as $count) {
                $sum_all += $count->total_letter_type;
            }
    
            return response()->json(['success' => true, 'data' => $count_letter_type, 'total_letter' => $sum_all], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLetterByTypeParameterUrl(Request $request, string $letter_id_type) {
        //
    }
}
