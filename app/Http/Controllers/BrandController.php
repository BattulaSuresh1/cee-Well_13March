<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;

use App\Models\Brands;
use App\Models\ItemType;
use Illuminate\Http\Request;
use App\Models\Visits;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = Brands::orderBy('id', 'DESC');

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%$search%");
        }
        $data = $query->paginate($pageSize, $columns, $pageName, $page);
        // echo "<pre>";print_r($data);exit;
        return response()->json($data);
    }
    public function create()
    {
        try {

            $data['brand'] = Brands::all();
            // $data['category'] = ItemType::where(['status' => '1'])->get(); // Fetch item types data
            $data['category'] = ItemType::all();

            $res = [
                'success' => true,
                'message' => 'Brand Create.',
                'data' => $data
            ];

            return response()->json($res);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    public function store(StoreBrandRequest $request)
    {
        try {
            $brand = new Brands();

            $brand->name = $request->name;
            // $brand->category = $request->input('category');
            $brand->category = json_encode($request->input('category')); // Convert to JSON

            $brand->save();

            if ($request->has('visit_id')) {
                $visit = Visits::find($request->visit_id);
                $visit->brand_id = $brand->id;

                $visit->save();
            }

            $res = [
                "success" => true,
                "message" => 'New Brand Created Successfully',
                "data" => $brand
            ];

            return response()->json($res);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage()
            ];

            return response()->json($res);
        }
    }


    public function Show($id)
    {
        try {
            $data['brand'] = Brands::find($id);
            $data['brand']->category = json_decode($data['brand']->category); // Convert to array
            
            $res = [
                'Success' => true,
                'message' => 'Brand Details Found',
                'data' => $data,
            ];

            return response()->json($res);
        } catch (\Exception $e) {
            $res = [
                'Success' => false,
                'message' => 'Something Went Wrong',
                'data' => $e->getMessage()
            ];

            return response()->json($res);
        }
    }


    public function update(StoreBrandRequest $request, $id)
    {
        try {
            $brandToUpdate = Brands::find($request->id);
            $brandToUpdate->name = $request->name;
            // $brandToUpdate->category = $request->category;
            $brandToUpdate->category = json_encode($request->category); // Convert to JSON
            $brandToUpdate->save();
            //$id = $brandToUpdate->id;

            $res = [
                'Success' => true,
                'message' => 'Brand Updated Successfully'
            ];
            return response()->json($res);
        } catch (\Exception $e) {
            $res = [
                'Success' => false,
                'message' => 'Something went Wrong',
                'brand' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    // public function delete($id)
    // {
    //     try
    //     {
    //         $data = Brand::find($id);
    //         $data->delete();

    //         $res = [
    //             'Success' => true,
    //             'Message' => 'Brand Deleted Successfully',
    //         ];

    //         return response()->json($res);
    //     }catch(\Exception $e)
    //     {
    //         $res = [
    //             'success' => false,
    //             'Message' => 'Something Went Wrong',
    //             'data'=> $e->getMessage()
    //         ];

    //         return response()->json($res);
    //     }
    // }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $data = Brands::where('id', $id)->where('status', '1')->first();

            if (!empty($data)) {

                $data->status = '0';
                $data->delete();
                $res = [
                    'success' => true,
                    'message' => 'Brand Deleted Successfully.',
                    'data' => $data
                ];
            } else {
                $res = [
                    'success' => false,
                    'data' => 'Brand Details not Found.',
                    'message' =>  $id
                ];
            }

            return response()->json($res);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($res);
        }
    }
}
