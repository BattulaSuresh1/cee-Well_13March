<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = User::with('role')->orderBy('id', 'DESC')->where('status','1');

        // if(!empty($search)){
        //     $query->where('name', 'LIKE', "%$search%");
        //     $query->orWhere('email', 'LIKE', "%$search%");
        //      $query->orWhere('role.name', 'LIKE', "%$search%");
        //     $query->orWhereHas('role', function ($subQuery ) use ($search) {
        //         $subQuery->where('name', 'like', "%$search%");
        //     });
        // }


        if(!empty($search)){
            $query->where('name', 'LIKE', "%$search%");
            $query->orWhere('email', 'LIKE', "%$search%");
            // $query->orWhere('role.name', 'LIKE', "%$search%");
            $query->orWhereHas('role', function ($subQuery ) use ($search) {
                $subQuery->where('name', 'like', "%$search%");
            });
        }

        $data = $query->paginate($pageSize,$columns,$pageName,$page);    

        return response()->json($data);
    }

    public function store(StoreUserRequest $request)
    {
        try{

            $role = new User();

            $role->name = $request->name;
            $role->email = $request->email;
            $role->role_id = $request->role_id;
            $role->password = bcrypt($request->password);
            $role->save();
            
            $res = [
                'success' => true,
                'message' => 'User created successfully.'
            ];

            return response()->json($res);

        }catch(\Exception $e){
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }

    }

    public function show($id){
        try{

            $data['user'] = User::find($id);

            $data['roles'] = Role::orderBy('id', 'DESC')->where('status','1')->get();

            $res = [
                'success' => true,
                'message' => 'User details.',
                'data' => $data
            ];

            return response()->json($res);

        }catch(\Exception $e){
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    public function update(StoreUserRequest $request, $id)
    {
        try{

            $user =  User::find($id);
            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->role_id;
            // $user->password = bcrypt($request->password);
            $user->save();
            
            $res = [
                'success' => true,
                'message' => 'User updated successfully.'
            ];

            return response()->json($res);

        }catch(\Exception $e){
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }

    }

    public function delete(Request $request)
    {
        try{
            $id = $request->id;
            $user = User::where('id',$id)->where('status','1')->first();
            if(!empty($user)){
             $user->status = '0';
                $user->delete();
                $res = [
                    'success' => true,
                    'message' => 'User deleted successfully.',
                    'data' => $user
                ];
            }else{
                $res = [
                    'success' => false,
                    'data' => 'User details not found.',
                    'message' =>  $id
                ];
            }

            return response()->json($res);

        }catch(\Exception $e){
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }

    }
}
