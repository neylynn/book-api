<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected function sendResponse($message, $data = [], $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function sendError($error, $data = [], $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error'   => $error,
            'data'    => $data,
        ], $status);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        Log::debug($input);
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('book-order-api')->plainTextToken;
        $success['name']  =  $user->name;
        $success['role']  =  $user->role;
   
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('story-api')->plainTextToken; 
            $success['name']  =  $user->name;
            $success['role']  =  $user->role;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function getUserProfile($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $orderHistory = $user->orders()->with('books')->get();

        $userProfile = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'order_history' => $orderHistory,
        ];

        return response()->json(['user_profile' => $userProfile]);
    }
}
