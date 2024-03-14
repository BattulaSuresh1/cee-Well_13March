<?php

namespace App\Http\Controllers;

use App\Models\Visits;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->searchKey ?? "";

        $query = Visits::with('customer')->orderBy('id', 'DESC');
        // ->where([
        //     ['status', '=', '1'],
        //     // ['time_entry', '!=', null],
        //     // ['time_exit', '!=', null],
        //  ]);

        if(!empty($search)){
            // $query->where('name', 'LIKE', "%$search%");
        }

        $data = $query->paginate($pageSize,$columns,$pageName,$page);    

        return response()->json($data);
    }

    public function show($id){
        try{

            $data['visits'] = Visits::with('customer')->find($id);
            $data['countries'] = CommonController::getCountries($id);
            $res = [
                'success' => true,
                'message' => 'Visit details.',
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


}
