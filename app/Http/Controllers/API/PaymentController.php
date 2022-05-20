<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;


class PaymentController extends BaseController
{

    public function store(Request $request)
    {
        $user = authUser();
        $input = $request->all();
        if(isset($user) && $user->type=='admin'){
            $validator = Validator::make($input, [
                'amount' => 'required',
                'transaction_id' => 'required|exists:transactions,id',
            ]);
            if($validator->fails()){
                return $this->handleError($validator->errors());
            }

            $transaction = Transaction::whereId($request->transaction_id)
                ->with('payments')->first();
            if($request->amount < 0 || $request->amount > $transaction->amount ){
                return $this->handleError("Invalid amount value",[],"401");
            }

            if(isset($transaction->payments) && sizeof($transaction->payments) > 0) {
                if(($transaction->payments->sum('amount') + $request->amount) > $transaction->amount)
                    return $this->handleError("Invalid amount value",[],"401");
            }

            if(Carbon::now() > Carbon::parse($transaction->due_on) ){
                if(isset($transaction->payments) && sizeof($transaction->payments) > 0) {
                    if($transaction->payments->sum('amount') < $transaction->amount)
                        Transaction::whereId($request->transaction_id)
                            ->update(['status' => 'overdue']);
                }
                return $this->handleError("Transaction due on date expired",[],"401");
            }

            $data = Payment::create($input);

            $transaction = Transaction::whereId($request->transaction_id)
                ->with('payments')->first();
            if(isset($transaction->payments) && sizeof($transaction->payments) > 0) {
                if($transaction->payments->sum('amount') == $transaction->amount)
                    Transaction::whereId($request->transaction_id)
                        ->update(['status' => 'paid']);
            }

            return $this->handleResponse(new PaymentResource($data), 'Payment created!');
        }
        return $this->handleError("Not authorize",[],"401");
    }

}
