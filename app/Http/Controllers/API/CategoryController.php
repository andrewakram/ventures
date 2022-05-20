<?php

namespace App\Http\Controllers\API;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;


class CategoryController extends BaseController
{

    public function store(Request $request)
    {
        $user = authUser();
        $input = $request->all();
        if(isset($user) && $user->type=='admin'){
            $validator = Validator::make($input, [
                'name' => 'required',
            ]);
            if($validator->fails()){
                return $this->handleError($validator->errors());
            }
            $data = Category::create($input);
            return $this->handleResponse(new CategoryResource($data), 'Category created!');
        }
        return $this->handleError("Not authorize",[],"401");
    }

}
