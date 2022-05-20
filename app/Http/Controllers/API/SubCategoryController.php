<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;


class SubCategoryController extends BaseController
{

    public function store(Request $request)
    {
        $user = authUser();
        $input = $request->all();
        if(isset($user) && $user->type=='admin'){
            $validator = Validator::make($input, [
                'name' => 'required',
                'category_id' => 'required|exists:categories,id',
            ]);
            if($validator->fails()){
                return $this->handleError($validator->errors());
            }
            $data = SubCategory::create($input);
            return $this->handleResponse(new SubCategoryResource($data), 'SubCategory created!');
        }
        return $this->handleError("Not authorize",[],"401");
    }

}
