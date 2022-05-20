<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\BaisicReportResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MonthlyReportResource;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;
use DB;


class ReportController extends BaseController
{

    public function showBaisicReport(Request $request)
    {
        $user = authUser();
        if(isset($user) && $user->type=='admin'){
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d',
            ]);
            if($validator->fails()){
                return $this->handleError($validator->errors());
            }

            if(Carbon::parse($request->start_date) > Carbon::parse($request->end_date) ){
                return $this->handleError("start_date field value must be before the end_date field value",[],"401");
            }

            $transactions = Transaction::whereBetween(DB::raw('DATE(due_on)'), array($request->start_date,$request->end_date))
                ->get();
            $data = (object)[];
            $data->period = $request->start_date . ' : ' .$request->end_date;
            $data->paid_amount = $transactions->where('status','paid')->sum('amount');
            $data->outstanding_amount = $transactions->where('status','outstanding')->sum('amount');
            $data->overdue_amount = $transactions->where('status','overdue')->sum('amount');
            return $this->handleResponse(new BaisicReportResource($data), 'Baisic Report retrieved!');
        }
        return $this->handleError("Not authorize",[],"401");
    }

    public function showMonthlyReport(Request $request)
    {
        $user = authUser();
        if(isset($user) && $user->type=='admin'){
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'start_date' => 'required|date_format:Y-m',
                'end_date' => 'required|date_format:Y-m',
            ]);
            if($validator->fails()){
                return $this->handleError($validator->errors());
            }

            if(Carbon::parse($request->start_date) > Carbon::parse($request->end_date) ){
                return $this->handleError("start_date field value must be before the end_date field value",[],"401");
            }

            $data = [];
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);
            while(Carbon::parse($start_date) <= Carbon::parse($end_date) ){
                $transactions = Transaction::whereBetween(DB::raw('DATE(due_on)'), array($start_date,$end_date))
                    ->get();

                $item = (object)[];
                $item->month = Carbon::parse($start_date)->format('m');
                $item->year = Carbon::parse($start_date)->format('Y');
                $item->paid_amount = $transactions->where('status','paid')->sum('amount');
                $item->outstanding_amount = $transactions->where('status','outstanding')->sum('amount');
                $item->overdue_amount = $transactions->where('status','overdue')->sum('amount');
                array_push($data,$item);
                $start_date = Carbon::parse($start_date)->day(01)->addMonths(1);
            }


            return $this->handleResponse(MonthlyReportResource::collection($data), 'Monthly Report retrieved!');
        }
        return $this->handleError("Not authorize",[],"401");
    }

}
