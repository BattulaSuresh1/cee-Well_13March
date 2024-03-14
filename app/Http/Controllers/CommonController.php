<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Prescription;
use App\Models\Visits;
use App\Models\State;
use App\Models\City;

class CommonController extends Controller

{
    // public static function getCountries($countryId){
    //     $country = Country::where('id', $countryId)->get();
    //     //  return Country::where(['status'=> '1'])->get();
    //     return Country::all();
    //     // return response()->json('id', $country);
    //     return CommonController::getCountries($country,$countryId);
    // }

    public static function getCountries($id){
        $country = Country::where('id', $id);
        return Country::all();
    //  return response()->json('country_id', $country);
        return CommonController::getCountries($country,$id);
     }
      
        public function getStates(Request $request, $id) {
        $country = $request->input('id', $id);

        return CommonController::getStates($country,$id);
        }

        public function getCities(Request $request, $id) {
        $state = $request->input('id', $id);

        return CommonController::getCities($state,$id);
        }




    public static function getCustomersInStore(){
      return Visits::where([
            ['status', '=', '1'],
            ['time_entry', '!=', null],
            ['time_exit', '=', null],
         ])->count();
    }

    public static function getCustomerVisits($customerId = "", $limit = 10000){
        $visits = Visits::with('customer')->where([
                ['status', '=', '1'],
                ['customer_id','=',$customerId]
            ])->orderBy('id', 'DESC')->limit($limit)->get();

          return self::PrepareVisitsData($visits);
    }

    public static function PrepareVisitsData($visitsArr = []){
        $visitsData = [];
        foreach ($visitsArr as $key => $visit) {
            $visitsData[$key]['id'] = $visit['id'] ?? " - ";
            $visitsData[$key]['image'] = $visit['customer']['images'] ?? null;
            $visitsData[$key]['brand_purchased'] = $visit['brand_purchased'] ?? " - ";
            $visitsData[$key]['gender'] = $visit['gender'] ?? " - ";
            $visitsData[$key]['entry_emotion'] = $visit['entry_emotion'] ?? " - ";
            $visitsData[$key]['exit_emotion'] = $visit['exit_emotion'] ?? " - ";
            $visitsData[$key]['visit_date'] = $visit['visit_date'] ?? " - ";
            $visitsData[$key]['time_entry'] = $visit['time_entry'] ?? " - ";
            $visitsData[$key]['time_exit'] = $visit['time_exit'] ?? " - ";
            $visitsData[$key]['time_spent'] = $visit['time_spent'] ?? " - ";
            $visitsData[$key]['order_value'] = $visit['order_value'] ?? " - ";
            $visitsData[$key]['name'] = $visit['customer']['name'] ?? " - ";
            $visitsData[$key]['age'] = $visit['customer']['age'] ?? " - ";
            $visitsData[$key]['life_style'] = $visit['customer']['life_style'] ?? " - ";
            $visitsData[$key]['phone'] = $visit['customer']['phone'] ?? " - ";
            $visitsData[$key]['customerId'] = $visit['customer']['id'] ?? null;
        }
        return $visitsData;
    }

    public static function getCustomerPrescriptions($customerId = "",$lensType="Ophthalmic Lens", $limit = 1000){
        $prescription = Prescription::with('lenspower')//->with('precalvalues')->with('thickness')
        ->where([
            ['status', '=', '1'],
            ['customer_id','=',$customerId],
            ['lens_type','=',$lensType]
        ])->orderBy('id', 'DESC')->limit($limit)->get();
        return $prescription;
    }

    public static function Checkfileinspace($spacefilepath) {

        $code = 0;

        $size = 0;

        $content_type = '';

        $url = NULL;


        return ['code' => $code, 'size' => $size, 'url' => $url, 'content_type' => $content_type];
    }

    public static function Deletefilefromspace($spacefilepath) {

        $code = 0;

        $exists = 0;

        $del = 0;



        return ['code' => $code, 'del' => $del, 'exists' => $exists];
    }

    public static function Uploadfiletospace($localfilepath, $spacefilepath) {

        $code = 0;

        $size = 0;

        $del = 0;

        $exists = 0;

        $url = NULL;


        return ['code' => $code, 'size' => $size, 'url' => $url, 'del' => $del, 'exists' => $exists];
    }
}
