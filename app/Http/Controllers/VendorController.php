<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreVendorsRequest;
use App\Models\Vendors;
use App\Models\Visits;
use Illuminate\Support\Str;
use App\Models\State;
use App\Models\City;
use Hash;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = Vendors::orderBy('id', 'DESC')->where('status','1');

        if(!empty($search)){
            $query->where('name', 'LIKE', "%$search%");
            $query->orWhere('email', 'LIKE', "%$search%");
            $query->orWhere('phone', 'LIKE', "%$search%");
            $query->orWhere('age', 'LIKE', "%$search%");
            $query->orWhere('profession', 'LIKE', "%$search%");
            $query->orWhere('life_style', 'LIKE', "%$search%");
        }

        $data = $query->paginate($pageSize,$columns,$pageName,$page);

        return response()->json($data);
    }

    public function store( StoreVendorsRequest $request )
    {
        try{
            // $password = Str::random(9);

            $newVendor = new Vendors();
            $newVendor->name = $request->name;
            $newVendor->email = $request->email ? $request->email : null;
            $newVendor->phone = $request->phone;
            // $newVendor->tax_id = $request->tax_id;
            $newVendor->images = $request->images;
            $newVendor->profession = $request->profession;
            $newVendor->alternate_phone = $request->alternate_phone;
            $newVendor->date_of_birth = $request->date_of_birth;
            $newVendor->age = $request->age;
            $newVendor->doa = $request->doa;
            $newVendor->life_style = $request->life_style;
            $newVendor->address = $request->address;
            $newVendor->nearby = $request->nearby;
            $newVendor->city = $request->city;
            $newVendor->state = $request->state;
            $newVendor->country_id = $request->country_id;
            $newVendor->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            $newVendor->status = 1;
            // $newVendor->password = Hash::make($password);
          

            $newSapCode = $this->generateSapCode($newVendor->id);
            $newVendor->code = $newSapCode;
            $newVendor->save();

            // if ($request->has('visit_id')) {
            //     $visit = Visits::find($request->visit_id);
            //     $visit->vendor_id = $newVendor->id;
            //     $visit->save();
            // }

            $res = [
                'success' => true,
                'message' => 'Vendor created successfully.',
                'data' => $newVendor
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

            $data['countries'] = CommonController::getCountries('id');
            $data['vendor']['code'] = '';
         
            $res = [
                'success' => true, 
                'message' => 'Vendor Create.',
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

            $data['vendor'] = Vendors::find($id);
            $data['countries'] = CommonController::getCountries($id);
          


            $res = [
                'success' => true,
                'message' => 'Vendors details.',
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

    public function update(StoreVendorsRequest $request , $id)
    {
        try{

            $vendorToUpdate = Vendors::find($id);
            // $vendorToUpdate->first_name = $request->first_name;
            // $vendorToUpdate->last_name = $request->last_name;
            $vendorToUpdate->name = $request->name;
            $vendorToUpdate->email = $request->email ? $request->email : null;
            $vendorToUpdate->phone = $request->phone;
            // $vendorToUpdate->tax_id = $request->tax_id;
            // $vendorToUpdate->currency_id = $request->currency_id;
            $vendorToUpdate->images = $request->images;
            $vendorToUpdate->code = $request->code;
            $vendorToUpdate->profession = $request->profession;
            $vendorToUpdate->alternate_phone = $request->alternate_phone;
            $vendorToUpdate->date_of_birth = $request->date_of_birth;
            $vendorToUpdate->age = $request->age;
            $vendorToUpdate->doa = $request->doa;
            $vendorToUpdate->life_style = $request->life_style;
            $vendorToUpdate->address = $request->address;
            $vendorToUpdate->nearby = $request->nearby;
            $vendorToUpdate->country_id = $request->country_id;
            $vendorToUpdate->city = $request->city;
            $vendorToUpdate->state = $request->state;
            $vendorToUpdate->updated_at = date('Y-m-d', strtotime($vendorToUpdate->date_of_birth));
            // $vendorToUpdate = date('Y-m-d', strtotime($vendor->date_of_birth)); // Format for display
            $vendorToUpdate->save();
            $id = $vendorToUpdate->id;

            $res = [
                'success' => true,
                'message' => 'Vendor updated successfully.'
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
            $vendor = Vendors::where('id',$id)->where('status','1')->first();

            if(!empty($vendor)){

                $vendor->status = '0';
                $vendor->save();
                $res = [
                    'success' => true,
                    'message' => 'Vendor deleted successfully.',
                    'data' => $vendor
                ];
            }else{
                $res = [
                    'success' => false,
                    'data' => 'Vendor details not found.',
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

    
//     public function vendorsVisits(Request $request){

//         $vendorId = $request->vendorId;

//         $pageSize = $request->per_page ?? 25;

//         $columns = ['*'];

//         $pageName = 'page';

//         $page = $request->current_page ?? 1;

//         $search = $request->searchKey ?? "";

//         $query = Visits::with('vendor')->where([
//             ['status', '=', '1'],
//             ['vendor_id','=',$vendorId]
//         ])->orderBy('id', 'DESC');

//         $data = $query->paginate($pageSize,$columns,$pageName,$page);

//         return response()->json($data);
// }

public static function generateSapCode()
{
    $lastVendor = Vendors::latest('id')->first();

    // If there is no last Vendor, start with 1
    $newSapCodeNumber = $lastVendor ? intval(substr($lastVendor->code, 7)) + 1 : 1;

    // Generate the new SAP code
    $newSapCode = 'SAPCODE' . $newSapCodeNumber;

    return $newSapCode;
}



public function getCountries(Request $request, $id = null){
    $country = $request->input('id', $id);
    return response()->json(['country_id' => $country]);
}


public function getStates(Request $request, $id) {
    $country = $request->input('id', $id);

    // Query the database to get the states for the selected country.
    $states = State::where('country_id', $country)->get();

    return response()->json(['states' => $states]);
}


public function getCities(Request $request, $id) {
    $state = $request->input('id', $id);

    // Query the database to get the cities for the selected states.
    $cities = City::where('state_id', $state)->get();

    return response()->json(['cities' => $cities]);
   
}
}
