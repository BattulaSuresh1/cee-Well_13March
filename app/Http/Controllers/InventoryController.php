<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Products;
use App\Models\Store;
use App\Models\StockRequest;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = StockRequest::with(['inventories.product','store'])
        ->orderBy('id', 'DESC')->where('status','1');

        if(!empty($search)){
            $query->whereHas('inventories' , function($subQuery) use ($search){
                $subQuery->whereHas('product' , function($inventryQuery) use ($search){
                    $inventryQuery->where('name', 'LIKE', "%$search%");
                });
                $subQuery->orWhere('available', 'LIKE', "%$search%");
             });

            $query->orWhereHas('store' , function($storeQuery) use ($search){
                $storeQuery->where('name', 'LIKE', "%$search%");
            });
        }

        $data = $query->paginate($pageSize,$columns,$pageName,$page);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try{

            $stockRequest = new StockRequest();
            $stockRequest->store_id = $request->storeId;
            $stockRequest->save();

            $products = $request->products;

            foreach ($products as $key => $value) {

                // $product =  Inventory::where('product_id',$value['productId'])->where('status','1')->first();

                if(!empty($value)){
                    $inventory = new Inventory();
                    $inventory->available =  $value['available'];
                    $inventory->product_id =  $value['productId'];
                    $inventory->request_id =  $stockRequest->id;
                    $inventory->save();
                }

            }


            if(!empty($stockRequest)){

                $res = [
                    'success' => true,
                    'message' => 'Inventory created successfully.',
                    'data' => $inventory
                ];

            } else {
                $res = [
                    'success' => false,
                    'data' => $inventory,
                    'message' => 'Something went wrong.'
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

    public function create(){
        try{

            $data['products'] = Products::orderBy('id', 'DESC')->where('status','1')->get();
            $data['stores'] = Store::orderBy('id', 'DESC')->where('status','1')->get();

            $res = [
                'success' => true,
                'message' => 'Inventory Create.',
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

            $data['Inventory'] = StockRequest::with('store')
            ->with(['inventories.product'])
            ->find($id);
            $res = [
                'success' => true,
                'message' => 'Inventory details.',
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

            $InventoryToUpdate = Inventory::find($id);
            $InventoryToUpdate->available = (int)($InventoryToUpdate->available) + (int)($request->available);
            $InventoryToUpdate->updated_at = date('Y-m-d H:i:s');
            $InventoryToUpdate->save();

            $res = [
                'success' => true,
                'message' => 'Inventory updated successfully.'
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
            $Inventory = Inventory::where('id',$id)->where('status','1')->first();

            if(!empty($Inventory)){

                $Inventory->status = '0';
                $Inventory->save();
                $res = [
                    'success' => true,
                    'message' => 'Inventory deleted successfully.',
                    'data' => $Inventory
                ];
            }else{
                $res = [
                    'success' => false,
                    'data' => 'Inventory details not found.',
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
