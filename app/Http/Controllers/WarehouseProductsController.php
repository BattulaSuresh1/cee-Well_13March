<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Models\Brands;
use App\Models\CollectionTypes;
use App\Models\FrameWidth;
use App\Models\GlassColors;
use App\Models\Materials;
use App\Models\PrescriptionTypes;
use App\Models\WarehouseProducts;
use App\Models\Shapes;
use Illuminate\Http\Request;

class WarehouseProductsController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $advaceSearch = $request->advanceFilter ?? "";

        $query = WarehouseProducts::orderBy('id', 'DESC')->where('status','1');

        if(!empty($search)){
            $query->where('name', 'LIKE', "%$search%");
            $query->orWhere('item_type', 'LIKE', "%$search%");
            $query->orWhere('item_code', 'LIKE', "%$search%");
            $query->orWhere('available', 'LIKE', "%$search%");
            $query->orWhere('rim_type', 'LIKE', "%$search%");
        }
 
        if(!empty($advaceSearch)){
            $advaceSearch = json_decode($advaceSearch);
            $item_type = $advaceSearch->item_type;
            if(!empty($item_type)) {
                $query->where('item_type', 'LIKE', $item_type);
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

}
