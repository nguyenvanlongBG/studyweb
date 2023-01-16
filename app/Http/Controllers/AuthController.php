<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\BaseService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
      protected BaseService $baseService;
      private UserService $userService;
    public function __construct(BaseService $baseService, UserService $userService){
        // $this->middleware('auth:api', ['expect'=>['login']]);
        $this->baseService=$baseService;
        $this->userService=$userService;
    }
  
    public function login(LoginRequest $request){
         $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials)) {
            $user = User::whereEmail($credentials['email'])->first();
            if (!Hash::check($credentials['password'], $user->password, [])) {
                throw new \Exception('Error in Login');
            }
            $tokenResult = $user->createToken('App')->plainTextToken;
            return $this->baseService->sendResponse($tokenResult, "Login Successfully");
        } else {
            return $this->baseService->sendError("Fail", "Login Fail", 401);
        }
    }
       public function register(RegisterRequest $request){
        
        $credentials = $request->all();
        $user=$this->userService->create(['name'=>$credentials['name'],'email'=>$credentials['email'],'password'=>Hash::make($credentials['password']), 'role_id' => $credentials['role'], 'point' => 0, 'asset' => 2500]);
        return $this->baseService->sendResponse($user, "Create user successfully");
    }
    public function me(){
        $user = Auth::user();
        return $this->baseService->sendResponse(['user' => $user], "Sucessfully");
    }
    public function respondWithToken($token){
        $user=Auth::user();
        return $this->baseService->sendResponse([
            'access_token'=>$token,
            'user'=>$user,
            'token_type'=>'bearer'  
        ],"Login Successful");
    }
}
