<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function profile(Request $request)
    {
        return $this->sendResponse(Auth::user(), "User profile");
    }
}
