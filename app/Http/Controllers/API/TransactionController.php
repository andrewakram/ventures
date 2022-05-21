<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;


class TransactionController extends BaseController
{
    public function index()
    {
        $user = authUser();
        if(isset($user) && $user->type=='user'){
            $transactions = Transaction::where('user_id',$user->id)->get();

            foreach ($transactions as $transaction){
                if(Carbon::now() > Carbon::parse($transaction->due_on) ){
                    if(isset($transaction->payments) && sizeof($transaction->payments) > 0) {
                        if($transaction->payments->sum('amount') < $transaction->amount){
                            Transaction::whereId($transaction->id)
                                ->update(['status' => 'overdue']);
                            $transaction->status = "overdue";
                        }
                    }
                }
            }
            return $this->handleResponse(TransactionResource::collection($transactions), 'Transaction retrieved.');
        }
        return $this->handleError("Not authorize",[],"401");

    }

    public function store(Request $request)
    {
        $user = authUser();
        $input = $request->all();
        if(isset($user) && $user->type=='admin'){
            $validator = Validator::make($input, [
                'user_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'sometimes|exists:sub_categories,id',
                'due_on' => 'required|date_format:Y-m-d',
                'amount' => 'required',
                'vat' => 'required',
                'is_vat_inclusive' => 'required',
            ]);
            if($validator->fails()){
                return $this->handleError($validator->errors());
            }

            if($request->amount < 0 ){
                return $this->handleError("Invalid amount value",[],"401");
            }

            $data = Transaction::create($input);
            return $this->handleResponse(new TransactionResource($data), 'Transaction created!');
        }
        return $this->handleError("Not authorize",[],"401");
    }

    public function show($id)
    {
        $user = authUser();
        if(isset($user) && $user->type=='admin'){
            $transaction = Transaction::find($id);
            if (is_null($transaction)) {
                return $this->handleError('Transaction not found!');
            }

            if(Carbon::now() > Carbon::parse($transaction->due_on) ){
                if(isset($transaction->payments) && sizeof($transaction->payments) > 0) {
                    if($transaction->payments->sum('amount') < $transaction->amount){
                        Transaction::whereId($id)
                            ->update(['status' => 'overdue']);
                        $transaction->status = "overdue";
                    }
                }
            }

            return $this->handleResponse(new TransactionResource($transaction), 'Transaction retrieved.');
        }
        return $this->handleError("Not authorize",[],"401");

    }

}
