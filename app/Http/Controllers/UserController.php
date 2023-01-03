<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService){
      $this->userService=$userService;
    } 
    public function createRequest(Request $request){
        $this->userService->createRequest($request);
    }
}
