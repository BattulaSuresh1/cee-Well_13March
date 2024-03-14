<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Models\Brands;
use App\Models\ItemType;
use App\Models\CollectionTypes;
use App\Models\FrameWidth;
use App\Models\GlassColors;
use App\Models\Materials;
use App\Models\color;
use App\Models\Order;
use App\Models\PrescriptionTypes;
use App\Models\Products;
use App\Models\Shapes;
use App\Models\Visits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $advaceSearch = $request->advanceFilter ?? "";

        $query = Products::orderBy('id', 'DESC')->where('status','1');
        $query->select(['*', DB::raw('(SELECT name FROM item_types WHERE id = products.item_type) AS item_type_name')]); // for id to displaying 


        if(!empty($search)){
            $query->where('name', 'LIKE', "%$search%");
            $query->orWhere('item_type', 'LIKE', "%$search%");
            $query->orWhere('item_code', 'LIKE', "%$search%");
            $query->orWhere('item_description', 'LIKE', "%$search%");
            $query->orWhere('rim_type', 'LIKE', "%$search%");
        }

        if(!empty($advaceSearch)){
            // $advaceSearch = json_decode($advaceSearch);
            // $item_type = $advaceSearch->item_type;
            // if(!empty($item_type)) {
            //     $query->where('item_type', 'LIKE', $item_type);
            // }

                    // Check if item_type is present in the advanced search
            if(isset($advaceSearch->item_type)) {
                $item_type_id = $advaceSearch->item_type;
                
                // Join the item_types table and filter by its id
                $query->whereHas('itemType', function($q) use ($item_type_id) {
                    $q->where('id', $item_type_id);
                });
            }

            $brand = $advaceSearch->brand;
            if(!empty($brand)) {
                $query->where('brand', 'LIKE', $brand);
            }

            $rim_type = $advaceSearch->rim_type;
            if(!empty($rim_type)) {
                $query->where('rim_type', 'LIKE', $rim_type);
            }

            $collection_type = $advaceSearch->collection_type;
            if(!empty($collection_type)) {
                $query->where('collection_type', 'LIKE', $collection_type);
            }

            $material = $advaceSearch->material;
            if(!empty($material)) {
                $query->where('material', 'LIKE', $material);
            }

            $prescription_type = $advaceSearch->prescription_type;
            if(!empty($prescription_type)) {
                $query->where('prescription_type', 'LIKE', $prescription_type);
            }

            $glass_color = $advaceSearch->glass_color;
            if(!empty($glass_color)) {
                $query->where('glass_color', 'LIKE', $glass_color);
            }

            $frame_width = $advaceSearch->frame_width;
            if(!empty($frame_width)) {
                $query->where('frame_width', 'LIKE', $frame_width);
            }

        }

        $data = $query->paginate($pageSize,$columns,$pageName,$page);

        return response()->json($data);
    }

    public function store(StoreProductsRequest $request)
    {
        try{

            $product = new Products();
            $product->name = $request->name;
            $product->item_type = $request->item_type;
            $product->item_code = $request->item_code ? $request->item_code : null;
            $product->item_description = $request->item_description;
            $product->price = $request->price;
            $product->discount = $request->discount;
            $product->images = $request->images;
            $product->brand = $request->brand;
            $product->model = $request->model;
            $product->color = $request->color;
            $product->size = $request->size;
            $product->rim_type = $request->rim_type;
            $product->collection_type = $request->collection_type;
            $product->material = $request->material;
            $product->prescription_type = $request->prescription_type;
            $product->glass_color = $request->glass_color;
            $product->frame_width = $request->frame_width;
            $product->catalog_no = $request->catalog_no;
            $product->barcode = $request->barcode;
            $product->created_at = date('Y-m-d H:i:s');
            $product->status = 1;
            // $brandId = $request->brand['id']; // Assuming 'id' is the relevant field
        
            // // Assign the brand ID to the product
            // $product->brand = $brandId;

            $product->save();

            if ($request->has('visit_id')) {
                $visit = Visits::find($request->visit_id);
                $visit->product = $product->id;
                $visit->save();
            }

            $res = [
                'success' => true,
                'message' => 'Product created successfully.'

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


    public function create(Request $request){
        try{

                     // $data['item_types'] = [
            //     [ "id"=> "1", "key"=> "FR", "name" => "FRAME" ],
            //     [ "id"=> "2", "key"=> "SG", "name" => "SUNGLASS" ],
            //     [ "id"=> "3", "key"=> "CL", "name" => "CONTACT LENSE" ],
            //     [ "id"=> "4", "key"=> "OL", "name" => "OPTHALMIC LENSE" ],
            //     [ "id"=> "5", "key"=> "AC", "name" => "ACCESSORIES" ],
            // ];
            $data['item_types'] = ItemType::where(['status' => '1'])->get();
    
            $data['rim_types'] = [
                                    "FULL RIM",
                                    "SUPRA",
                                    "RIMLESS",
                                    "LOW BRIDGE",
                                    "WIRE",
                                ];
    
            $data['shapes'] = Shapes::where(['status' => '1'])->get();
            $data['collection_types'] = CollectionTypes::where(['status' => '1'])->get();
            $data['materials'] = Materials::where(['status' => '1'])->get();
            $data['colors'] = color::Where(['status' => '1'])->get();
            $data['prescription_types'] = PrescriptionTypes::where(['status' => '1'])->get();
            $data['glass_colors'] = GlassColors::where(['status' => '1'])->get();
            $data['frame_widths'] = FrameWidth::where(['status' => '1'])->get();
    
           
                // Get the selected item type
            $selectedItemType = $request->input('item_type');

            if (!empty($selectedItemType)) {
                // Find brands that have the selected item type in their categories
                // $brands = Brands::where('category', 'LIKE', '%"'.$selectedItemType.'"%')->get();
                $brands = Brands::whereJsonContains('category', [$selectedItemType])->get();
                $data['brands'] = $brands;
            } else {
                // If no item type is selected, show all brands
                $data['brands'] = Brands::where(['status' => '1'])->get();
            }
            
            $res = [
                'success' => true,
                'message' => 'Product Create.',
                'data' => $data
            ];
    
            return response()->json($res);
    
        } catch(\Exception $e){
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }
    }
    

    public function show( $id){
        try{

            $data['product'] = Products::find($id); //customer propety changed
            // $data['item_types'] = [
            //     [ "id"=> "1", "key"=> "FR", "name" => "FRAME" ],
            //     [ "id"=> "2", "key"=> "SG", "name" => "SUNGLASS" ],
            //     [ "id"=> "3", "key"=> "CL", "name" => "CONTACT LENSE" ],
            //     [ "id"=> "4", "key"=> "OL", "name" => "OPTHALMIC LENSE" ],
            //     [ "id"=> "5", "key"=> "AC", "name" => "ACCESSORIES" ],
            // ];
            $data['item_types'] = ItemType::where(['status' => '1'])->get();

            $data['rim_types'] = [
                                    "FULL RIM",
                                    "SUPRA",
                                    "RIMLESS",
                                    "LOW BRIDGE",
                                    "WIRE",
                                ];
           
            $data['shapes'] = Shapes::where(['status' => '1'])->get();
            $data['collection_types'] = CollectionTypes::where(['status' => '1'])->get();
            $data['materials'] = Materials::where(['status' => '1'])->get();
            $data['prescription_types'] = PrescriptionTypes::where(['status' => '1'])->get();
            $data['colors'] = color::Where(['status' => '1'])->get();
            $data['glass_colors'] = GlassColors::where(['status' => '1'])->get();
            $data['frame_widths'] = FrameWidth::where(['status' => '1'])->get();

            // $selectedItemType = $request->input('item_type');

            // if (!empty($selectedItemType)) {
            //     // Find brands that have the selected item type in their categories
            //     // $brands = Brands::where('category', 'LIKE', '%"'.$selectedItemType.'"%')->get();
            //     $brands = Brands::whereJsonContains('category', [$selectedItemType])->get();
            //     $data['brands'] = $brands;
            // } else {
            //     // If no item type is selected, show all brands
            //     $data['brands'] = Brands::where(['status' => '1'])->get();
            // }

            $data['brands'] = Brands::where(['status' => '1'])->get();
            $res = [
                'success' => true,
                'message' => 'Product details.',
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

    public function update(StoreProductsRequest $request,$id)
    {
        try{

            $product = Products::find($id);

            $product->name = $request->name;
            $product->item_type = $request->item_type;
            
            $product->item_code = $request->item_code ? $request->item_code : null;
            $product->item_description = $request->item_description;
            $product->price = $request->price;
            $product->discount = $request->discount;
            $product->images = $request->images;
            $product->brand = $request->brand;
            $product->model = $request->model;
            $product->color = $request->color;
            $product->size = $request->size;
            $product->rim_type = $request->rim_type;
            $product->collection_type = $request->collection_type;
            $product->material = $request->material;
            $product->prescription_type = $request->prescription_type;
            $product->glass_color = $request->glass_color;
            $product->frame_width = $request->frame_width;
            $product->catalog_no = $request->catalog_no;
            $product->barcode = $request->barcode;
            $product->updated_at = date('Y-m-d H:i:s');

            $product->save(); 
          
           
            $res = [
                'success' => true,
                'message' => 'Product updated successfully.'
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
            $product = Products::where('id',$id)->where('status','1')->first();

            if(!empty($product)){

                $product->status = '0';
                $product->delete();
                $res = [
                    'success' => true,
                    'message' => 'Product deleted successfully.',
                    'data' => $product
                ];
            }else{
                $res = [
                    'success' => false,
                    'data' => 'Product details not found.',
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
public function getCustomerOrder($customerId)
{
    try{

        $measurments = Order::where('customer_id', $customerId)->get();
        $data = $measurments;
        $res = [
            'success' => true,
            'message' => 'Customer-Order details.',
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

public function searchProductByItemCode(Request $request)
{
    try {
        $itemCode = $request->itemCode;

        $product = Products::where('item_code', $itemCode)->first();

        if ($product) {
            $res = [
                'success' => true,
                'message' => 'Product found.',
                'data' => $product
            ];
        } else {
            $res = [
                'success' => false,
                'message' => 'Product not found.'
            ];
        }

        return response()->json($res);
    } catch (\Exception $e) {
        $res = [
            'success' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ];
        return response()->json($res, 500);
    }
}

}
