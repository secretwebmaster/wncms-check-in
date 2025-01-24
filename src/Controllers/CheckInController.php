<?php

namespace Wncms\CheckIn\Controllers;

use Wncms\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Wncms\CheckIn\Models\CheckIn;


class CheckInController extends FrontendController
{
    public function index()
    {
        dd('fetch check in records');
    }

    public function store(Request $request)
    {
        // $userModel = $this->getUserModelClass();

        if(!auth()->check()){
            $status = 'error';
            $message = __('You need to login to check in.');
        }elseif (auth()->user()->has_checked_in()) {
            $status = 'error';
            $message = __('You have already checked in today.');
        }else{
            auth()->user()->check_in_now();
            $status = 'success';
            $message = __('You have successfully checked in.');
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }else{
            return redirect()->back()->with($status, $message);
        }
    }
}
