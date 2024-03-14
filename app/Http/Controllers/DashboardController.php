<?php

namespace App\Http\Controllers;

use App\Models\Visits;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index(){
        try{

            $data['customersInStore'] = CommonController::getCustomersInStore();

            $visits = Visits::with('customer')->where([
                ['status', '=', '1'],
                ['time_entry', '!=', null],
                ['time_exit', '=', null],
                // ['']
             ])->get();

            $data['visits'] = self::PrepareVisitsData($visits);

            $res = [
                'success' => true,
                'message' =>  'Dashboard Details',
                'data' => $data,
            ];

            return response()->json($res);

        }catch(\Exception $e){
            $data = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' =>  $e->getMessage()
            ];
            return response()->json($data);
        }
    }

    public static function PrepareVisitsData($visitsArr = []){
        $visitsData = [];
        foreach ($visitsArr as $key => $visit) {
            $visitsData[$key]['id'] = $visit['id'] ?? " - ";
            $visitsData[$key]['images'] = $visit['customer']['images'] ?? null;
            $visitsData[$key]['brand_purchased'] = $visit['brand_purchased'] ?? " - ";
            $visitsData[$key]['gender'] = $visit['gender'] ?? " - ";
            $visitsData[$key]['emotion'] = $visit['entry_emotion'] ?? " - ";
            $visitsData[$key]['name'] = $visit['customer']['name'] ?? " - ";
            $visitsData[$key]['age'] = $visit['customer']['age'] ?? " - ";
            $visitsData[$key]['life_style'] = $visit['customer']['life_style'] ?? " - ";
            $visitsData[$key]['phone'] = $visit['customer']['phone'] ?? " - ";
            $visitsData[$key]['customerId'] = $visit['customer']['id'] ?? null;
        }
        return $visitsData;
    }
}
