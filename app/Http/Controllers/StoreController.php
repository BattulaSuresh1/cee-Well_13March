<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Products;
use App\Models\Store;
use Illuminate\Http\Request;


class StoreController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = Store::orderBy('id', 'DESC')->where('status','1');
       
        if(!empty($search)){
            $query->where('name', 'LIKE', "%$search%");
            $query->orWhere('address', 'LIKE', "%$search%");
        }

        $data = $query->paginate($pageSize,$columns,$pageName,$page);    

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try{

            $store = new Store();
            $store->name =  $request->name;
            $store->address =  $request->address;
            $store->save();

            $res = [
                'success' => true,
                'message' => 'Store created successfully.',
                'data' => $store
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

    public function create(){
        try{

            $data = [];
            $res = [
                'success' => true,
                'message' => 'Store Create.',
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

    public function show($id){
        try{

            $data['store'] = Store::find($id);
            $res = [
                'success' => true,
                'message' => 'Store details.',
                'data' => $data,
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

    public function update(Request $request,$id)
    {
        try{

            $storeToUpdate = Store::find($id);
            $storeToUpdate->name = $request->name;
            $storeToUpdate->address = $request->address;
            $storeToUpdate->updated_at = date('Y-m-d H:i:s');
            $storeToUpdate->save();
           
            $res = [
                'success' => true,
                'message' => 'Store updated successfully.'
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
            $Store = Store::where('id',$id)->where('status','1')->first();
            
            if(!empty($Store)){

                $Store->status = '0';
                $Store->save();
                $res = [
                    'success' => true,
                    'message' => 'Store deleted successfully.',
                    'data' => $Store
                ];
            }else{
                $res = [
                    'success' => false,
                    'data' => 'Store details not found.',
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
