<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;
use Auth;

class UseController extends Controller
{
    // function to add user... 
    public function addUser(Request $request){
        // Validation
        $Valid = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|min:5'
        ]);

        if($Valid->fails()){
            $result = array('status'=> false, 'message'=> 'Validation Error Occred', 'erroe_message' => $Valid->errors());
            return response()->json($result, 400); // For Bad Request..
        }

        $user = User::create([
            'name'=> $request->name,
            'phone'=> $request->phone,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
        ]);

        if($user->id){
            $result = array('status'=> true, 'message'=> 'User Successfully Created', 'data'=> $user);
            $responseCode = 200; // For Success...
        }else{
            $result = array('status'=> false, 'message'=> 'Something is wrong');
            $responseCode =400;
        }
        return response()->json($result, $responseCode);
    }


    // function to get All user... 
    public function allUser(){
        
        $users = User::all();
        $result = array('status'=> true, 'message'=> count($users).' Users Fetched', 'data'=> $users);
        $responseCode = 200; // For Success...

        return response()->json($result, $responseCode);
    }


    // function to get Indivisual user...
    public function indivisualUser($id){
        
        $users = User::find($id);
        if(!$users){
            return response()->json(['status'=> false, 'message'=> 'Users Not Found'], 404);
        }
        $result = array('status'=> true, 'message'=> 'Users Found', 'data'=> $users);
        $responseCode = 200; // For Success...

        return response()->json($result, $responseCode);
    }

    // function to update user... 
    public function updateUser(Request $request ,$id){
        $users = User::find($id);
        if(!$users){
            return response()->json(['status'=> false, 'message'=> 'Users Not Found'], 404);
        }
        // Validation
        $Valid = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'phone' => 'required|numeric|digits:11'
        ]);

        if($Valid->fails()){
            $result = array('status'=> false, 'message'=> 'Validation Error Occred', 'erroe_message' => $Valid->errors());
            return response()->json($result, 400); // For Bad Request..
        }
        // Update User
        $users->name = $request->name;
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->save();
        $result = array('status'=> true, 'message'=> 'User Update Successfully Done', 'data' => $users);
        return response()->json($result, 200);
    }

    // function to delete user...
    public function deleteUser(Request $request ,$id){
        $users = User::find($id);
        if(!$users){
            return response()->json(['status'=> false, 'message'=> 'Users Not Found'], 404);
        }
        $users->delete();
        $result = array('status'=> true, 'message'=> 'User deleted');
        return response()->json($result, 200);
    }

    // Function For Login ... 
    public function userLogin(Request $request){
        $Valid = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        if($Valid->fails()){
            $result = array('status'=> false, 'message'=> 'Validation Error Occred', 'erroe_message' => $Valid->errors());
            return response()->json($result, 400); // For Bad Request..
        }

        $credential = $request->only('email', 'password');
        if(Auth::attempt($credential)){
            $user = Auth::user();
            $token = $user->createToken('MyToken')->accessToken;
            return response()->json(['status'=> true, 'message'=> 'login Successfully Done', 'token' =>$token], 200);
        }
        return response()->json(['status'=> false, 'message'=> 'Invalid login credential'], 401);
    }

    // Function Unauthorized user ...
    public function unauthentic(){
        return response()->json(['status'=> false, 'message'=> 'Only can access Authentic Users', 'error'=> 'Unauthentic'], 401);
    }

    // Function For logout ...
    public function logout(){
        $user =Auth::user();
        $user->$tokens->each(function ($token, $key){
            $token->delete();
        });
        return response()->json(['status'=> true, 'message'=> 'logout Successfully Done'], 200);
    }
}

