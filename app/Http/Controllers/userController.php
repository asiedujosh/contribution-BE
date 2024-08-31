<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\HttpResponses;
use Hash;

class userController extends Controller
{
    use HttpResponses;
    //
    public function index (Request $request){
        $pageNo = $request->input('page');
        $perPage = $request->input('perPage');
        $users = User::orderBy('id', 'DESC')->paginate($perPage, ['*'], 'page', $pageNo);
        return $this->success([
            'data' => $users,
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage()  
            ]
        ]); 
    }


    public function searchUser(Request $request){
        $results = User::latest()->filter(request(['keyword']))->get();
        return $this->success([
            'data' => $results
           ]);
    }


    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if(!$user || !Hash::check($credentials['password'], $user->password)){
            return $this->error('', 'Credentials do not match', 401);
        } else {
            return $this->success([
                'data' => $user,
                'token' => $user->createToken('accessToken'.$user->userId)->plainTextToken
            ]);
        }
    }

    
    public function store(Request $request){
        try {
            $user = new User;
            $user->firstName = trim($request->firstName);
            $user->lastName = trim($request->lastName);
            $user->gender = trim($request->gender);
            $user->email = trim($request->email);
            $user->password = trim($request->password);
            $user->type = $request->type;
            $user->branchId = $request->branchId;
            $res = $user->save();
    
            if ($res) {
                return $this->success([
                    'user' => $user,
                    'token' => $user->createToken('accessToken' . $user->username)->plainTextToken
                ]);
            } else {
                return $this->error('error', 'Error registering', 400);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            return $errorCode;
            if ($errorCode == 1062) { // MySQL duplicate key error code
                return $this->error('error', 'Username is already taken.', 400);
            } else {
                return $this->error('Database error.');
            }
        }
     }


     public function updateUser(Request $request, $id){
        $formField = [
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'gender' => $request->gender,
            'type' => $request->branchId,
            'email' => $request->email,
            'branchId' => $request->branchId,
        ];

        $res = User::where('id', $id)->update($formField);
        if($res){
            return $this->success([
            'data' => $res
            ]);
        }
    }


    public function getUserDetails(){
        $user = Auth::User();
        return response()->json(['data' => $user]);
    }


    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->success([
            'data' => 'Tokens revoked successfully'
        ]);
    }
}
