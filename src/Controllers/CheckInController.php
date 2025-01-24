<?php

namespace Wncms\CheckIn\Controllers;

use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Wncms\CheckIn\Models\CheckIn;


class CheckInController extends FrontendController
{
    // /**
    //  * Get the model class that this controller works with.
    //  * Uses a setting from config/wncms.php and falls back to User model if not set.
    //  */
    // protected function getUserModelClass()
    // {
    //     // Fetch the model class from the config file, or fall back to Post model
    //     return config('wncms.default_user_model', \Wncms\Models\User::class);
    // }

    public function index()
    {
        dd('fetch check in records');
    }

    public function store(Request $request)
    {
        // $userModel = $this->getUserModelClass();

        $today = Carbon::today();

        $hasCheckedIn = CheckIn::where('user_id', auth()->id())->whereDate('check_in_date', $today)->exists();

        if ($hasCheckedIn) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('You have already checked in today.')
                ]);
            }

            return back()->with('error', __('You have already checked in today.'));
        }else{

            CheckIn::create([
                'user_id' => auth()->id(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => __('Check-in successful! You earned 1 credit.')
                ]);
            }
        
            return redirect()->back()->with('success', __('Check-in successful! You earned 1 credit.'));
        }
    }
}
