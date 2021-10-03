<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //CREATE USER ACOUNT
    public function createAccount(Request $request){
        $rules =[
            'email' => 'required|unique:users,email|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        $customMessages =[
            'email.required' => 'ایمیل خود را وارد کنید',
            'email.unique' => 'ایمیل وارد شده دارای حساب می باشد',
            'email.email' => 'ایمیل وارد شده نامعتبر است',
            'password.required' => 'گذرواژه خود را انتخاب کنید',
            'password.confirmed' => 'تکرار گذرواژه اشتباه است',
            'password_confirmation.required' => 'گذرواژه خود را تکرار کنید',
        ];
        // $this->validate($request, $rules, $customMessages);

        $validate = Validator::make($request->all(), $rules, $customMessages);
        if($validate->fails()){
            return response()->json($validate->errors());
        }


        $data= $request->all();
        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        
        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response);
    }


    //LOGIN USER
    public function login(Request $request){
        $rules =[
            'email' => 'required|email',
            'password' => 'required',
        ];
        $customMessages =[
            'email.required' => 'ایمیل خود را وارد کنید',
            'email.email' => 'ایمیل وارد شده نامعتبر است',
            'password.required' => 'گذرواژه خود را وارد کنید',
        ];
        // $this->validate($request, $rules, $customMessages);

        $validate = Validator::make($request->all(), $rules, $customMessages);

        if($validate->fails()){
            return response()->json($validate->errors());
        }


        $data= $request->all();
        $user= User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response()->json(['msg'=>'اطلاعات ورود اشتباه است']);
        }
        
        $token = $user->createToken('userToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response);
    }


    //LOGOUT USER
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response()->json(['msg' => 'از حساب کاربری خارج شدید']);
    }


    //SHOW USER BALANCE
    public function balance(Request $request){
        $id = auth()->user()->id;
        $user = User::findUserById($id);

        return response()->json(['balance'=>$user['balance']]);
    }


}
