<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LetterInformation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function checkCountEachLetterType() {
        try {
            $count_letter_type = LetterInformation::groupBy('letter_type')->select('letter_type', DB::raw('count(*) as total_letter_type'))->get();
            $sum_all = $sum_all_type = 0;
            foreach ($count_letter_type as $count) {
                $sum_all += $count->total_letter_type;
                $sum_all_type += 1;
            }
    
            return response()->json(['success' => true, 'data' => $count_letter_type, 'total_letter_type' => $sum_all_type, 'total_letter' => $sum_all], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLetterByTypeParameterUrl(Request $request, string $letter_id_type) {
        //
    }

    public function getSelfAccountInformation(Request $request) {
        $payload = $request->get('jwt_payload');

        $email_current_login = $payload['sub'];
        $user_information = User::where('email', $email_current_login)->first();

        return $user_information;
    }
}
