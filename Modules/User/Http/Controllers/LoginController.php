<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponseTrait;
use Session;

class LoginController extends Controller
{
    use ApiResponseTrait;
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */

    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        if($request->email && $request->password){
            $user= User::with('roleInfo', 'userAssignedRoles', 'department','userSource')->where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->invalidResponse(422,'Wrong Username or Password');
            } else if ($user->user_status == 0) { // Check if user status is not active
                return $this->invalidResponse(422,'User is inactive. Please contact the administrator.');
            }
            else {
                
                $token = $user->createToken('my-app-token')->plainTextToken;
                
                $response = [
                    'user' => $user,
                    'token' => $token,
                ];
            
                return $this->successResponse(200,'Data retrieved Successfully', $response);
            }
        }
    }

    public function logout(Request $request) 
    {
        try {
            if (!isset(auth('sanctum')->user()->id) || User::find(auth('sanctum')->user()->id) == null) {
                return $this->invalidResponse(422,'Something went wrong.');
            }

            $userId = (int) auth('sanctum')->user()->id;
            $user = User::findOrFail($userId);

            if (!$user) {
                return $this->invalidResponse(422,'User not found.');
            }
    
            // Remove all tokens...
            $user->tokens()->delete();

            return $this->successResponse(200,'Logged Out Successfully');
        } catch (\Exception $th) {
            $error = \Log::error($th->getMessage());
            return $this->successResponse('Logged Out Un-Successfully',[]);
        }
    }
}