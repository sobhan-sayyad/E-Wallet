<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;


class WalletController extends Controller
{
    //SHOW HISTORY
    public function history(Request $request){
        $user_id = auth()->user()->id;
        $history = Wallet::where('user_id', $user_id)->paginate(10);

        return response()->json($history);
    }


    //ADD FUND
    public function addfund(Request $request){
        $rules =[
            'amount' => 'required|numeric|min:10000',
        ];
        $customMessages =[
            'amount.required' => 'مبلغ را وارد کنید',
            'amount.numeric' => 'مبلغ نامعتبر است',
            'amount.min' => 'حداقل مبلغ واریزی 10,000 ریال میباشد',
        ];
        // $this->validate($request, $rules, $customMessages);

        $validate = Validator::make($request->all(), $rules, $customMessages);
        if($validate->fails()){
            return response()->json($validate->errors());
        }

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['state'] = 1;
        Wallet::create($data);

        User::updateBalance($data);
        return response()->json(['msg'=>'کیف پول شما با موفقیت شارژ شد']);
    }


    //WITHDRAW
    public function withdraw(Request $request){
        $rules =[
            'amount' => 'required|numeric|min:100000',
        ];
        $customMessages =[
            'amount.required' => 'مبلغ را وارد کنید',
            'amount.numeric' => 'مبلغ نامعتبر است',
            'amount.min' => 'حداقل مبلغ برداشتی 100,000 ریال میباشد',
        ];
         // $this->validate($request, $rules, $customMessages);

        $validate = Validator::make($request->all(), $rules, $customMessages);
        if($validate->fails()){
            return response()->json($validate->errors());
        }

        $user_id = auth()->user()->id;
        $user = User::findUserById($user_id);
        
        if($user['balance'] >= $request->amount){
            $data = $request->all();
            $data['user_id'] = $user_id;
            $data['state'] = 0;
            Wallet::create($data);

            User::updateBalance($data);

            return response()->json(['msg'=>'برداشت انجام شد']);
        }
        return response()->json(['msg'=>'موجودی حساب شما کافی نمی باشد']);
    }
}
