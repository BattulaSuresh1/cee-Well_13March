<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\CustomerBranch;
use App\Models\Customers;
use App\Models\Visits;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\State;
use App\Models\City;
use Hash;

class CustomerController extends Controller
{
    public function index(Request $request){

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->filter ?? "";

        $query = Customers::with('customerbranch')->orderBy('id', 'DESC')->where('status','1');

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

    public function store(StoreCustomerRequest $request)
    {
        try{
            $password = Str::random(9);

            $newCustomer = new Customers();
            // $newCustomer->first_name = $request->first_name;
            // $newCustomer->last_name = $request->last_name;
            $newCustomer->name = $request->name;
            $newCustomer->email = $request->email ? $request->email : null;
            $newCustomer->phone = $request->phone;
            $newCustomer->tax_id = $request->tax_id;
            $newCustomer->images = $request->images;
            // $newCustomer->code = $request->code;
            $newCustomer->profession = $request->profession;
            $newCustomer->alternate_phone = $request->alternate_phone;
            $newCustomer->date_of_birth = $request->date_of_birth;
            $newCustomer->age = $request->age;
            $newCustomer->doa = $request->doa;
            $newCustomer->life_style = $request->life_style;
            $newCustomer->address = $request->address;
            $newCustomer->nearby = $request->nearby;
            $newCustomer->city = $request->city;
            $newCustomer->state = $request->state;
            $newCustomer->country_id = $request->country_id;
            // $newCustomer->currency_id = $request->currency_id;
            $newCustomer->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            // $newCustomer->date_of_birth = date('d-m-Y', strtotime($request->date_of_birth));
            // $newCustomer->created_at =  date('Y-m-d H:i:s');
            $newCustomer->status = 1;
            $newCustomer->password = Hash::make($password);
          

            $newSapCode = $this->generateSapCode($newCustomer->id);
            $newCustomer->code = $newSapCode;
            $newCustomer->save();

            if ($request->has('visit_id')) {
                $visit = Visits::find($request->visit_id);
                $visit->customer_id = $newCustomer->id;
                $visit->save();
            }

            $res = [
                'success' => true,
                'message' => 'Customer created successfully.',
                'data' => $newCustomer
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
            $data['customer']['code'] = '';
         
            $res = [
                'success' => true, 
                'message' => 'Customer Create.',
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

            $data['customer'] = Customers::with('visits')->find($id);
            $data['countries'] = CommonController::getCountries($id);
          


            $res = [
                'success' => true,
                'message' => 'Customer details.',
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

    public function update(StoreCustomerRequest $request , $id)
    {
        try{

            $customerToUpdate = Customers::find($id);
            // $customerToUpdate->first_name = $request->first_name;
            // $customerToUpdate->last_name = $request->last_name;
            $customerToUpdate->name = $request->name;
            $customerToUpdate->email = $request->email ? $request->email : null;
            $customerToUpdate->phone = $request->phone;
            $customerToUpdate->tax_id = $request->tax_id;
            // $customerToUpdate->currency_id = $request->currency_id;
            $customerToUpdate->images = $request->images;
            $customerToUpdate->code = $request->code;
            $customerToUpdate->profession = $request->profession;
            $customerToUpdate->alternate_phone = $request->alternate_phone;
            $customerToUpdate->date_of_birth = $request->date_of_birth;
            $customerToUpdate->age = $request->age;
            $customerToUpdate->doa = $request->doa;
            $customerToUpdate->life_style = $request->life_style;
            $customerToUpdate->address = $request->address;
            $customerToUpdate->nearby = $request->nearby;
            $customerToUpdate->country_id = $request->country_id;
            $customerToUpdate->city = $request->city;
            $customerToUpdate->state = $request->state;
            $customerToUpdate->updated_at = date('Y-m-d', strtotime($customerToUpdate->date_of_birth));
            // $customerToUpdate = date('Y-m-d', strtotime($customer->date_of_birth)); // Format for display
            $customerToUpdate->save();
            $id = $customerToUpdate->id;

            $res = [
                'success' => true,
                'message' => 'Customer updated successfully.'
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
            $customer = Customers::where('id',$id)->where('status','1')->first();

            if(!empty($customer)){

                $customer->status = '0';
                $customer->save();
                $res = [
                    'success' => true,
                    'message' => 'Customer deleted successfully.',
                    'data' => $customer
                ];
            }else{
                $res = [
                    'success' => false,
                    'data' => 'Customer details not found.',
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

    public function overview(Request $request ){
        try{
            $customerId = $request->customerId;
            $data['lastFiveVisits'] = CommonController::getCustomerVisits($customerId,$limit=10);

            $data['prescriptionsOphthalmicLens'] = CommonController::getCustomerPrescriptions($customerId,"Ophthalmic Lens");
            $data['prescriptionsContactLens'] = CommonController::getCustomerPrescriptions($customerId,"Contact Lens");

            $res = [
                'success' => true,
                'message' => 'Customer overview details.',
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

    public function customerVisits(Request $request){

        $customerId = $request->customerId;

        $pageSize = $request->per_page ?? 25;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->searchKey ?? "";

        $query = Visits::with('customer')->where([
            ['status', '=', '1'],
            ['customer_id','=',$customerId]
        ])->orderBy('id', 'DESC');

        $data = $query->paginate($pageSize,$columns,$pageName,$page);

        return response()->json($data);
}

public static function generateSapCode()
{
    $lastCustomer = Customers::latest('id')->first();

    // If there is no last customer, start with 1
    $newSapCodeNumber = $lastCustomer ? intval(substr($lastCustomer->code, 7)) + 1 : 1;

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
