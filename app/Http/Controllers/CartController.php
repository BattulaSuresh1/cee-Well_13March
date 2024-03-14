<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\cartLens;
use App\Models\cartMeasurements;
use App\Models\PrecalValues;
use App\Models\Products;
use App\Models\Thickness;
use App\Models\LensMasters;
use App\Models\Brands;
use App\Models\LensDetails;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {

        $pageSize = $request->per_page ?? 25;

        $customerId = $request->customerId;

        $columns = ['*'];

        $pageName = 'page';

        $page = $request->current_page ?? 1;

        $search = $request->searchKey ?? "";


        $query = cart::with('product')->with('customer')->orderBy('id', 'DESC')->where('customer_id', $customerId)->where('status', '1');

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $data = $query->paginate($pageSize, $columns, $pageName, $page);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {

            $cartProductQuantities = 1;
            $productId = $request->productId;
            $customerId = $request->customerId;
            $product = Products::where('id', $productId)->where('status', '1')->first();

            if ($product) {

                $cartProduct = cart::where('product_id', $productId)
                    ->where('customer_id', $customerId)->where('status', '1')->first();

                if ($cartProduct) {
                    $cartProductQuantities = $cartProduct->quantities + $cartProductQuantities;
                    $cartProduct->total_amount = (($cartProductQuantities) * ($product->price));
                    $cartProduct->price = $product->price;
                    $cartProduct->discount = self::getDiscountAmount($product->price, $cartProductQuantities, $product->discount);
                    $cartProduct->quantities = $cartProductQuantities;
                    $cartProduct->customer_id = $customerId;
                    $cartProduct->save();
                } else {
                    
                    $cartProduct = new cart();
                    $cartProduct->product_id = $productId;
                    $cartProduct->customer_id = $customerId;
                    $cartProduct->quantities = $cartProductQuantities;
                    $cartProduct->total_amount = ($cartProductQuantities * $product->price);
                    $cartProduct->price = $product->price;
                    $cartProduct->discount = self::getDiscountAmount($product->price, $cartProductQuantities, $product->discount);
                    $cartProduct->status = "1";
                    $cartProduct->save();
                }

                $totalCartItems = cart::where('customer_id', $customerId)->where('status', '1')->count();

                $res = [
                    'success' => true,
                    'message' => $product->name . ' ' . $cartProductQuantities . ' time added to cart successfully.',
                    'data' => $cartProduct,
                    'totalCartItems' => $totalCartItems
                ];
            } else {
                $res = [
                    'success' => false,
                    'message' => 'product details not found.',
                    'data' => ''

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

    public function create()
    {
        try {

            $data['countries'] = CommonController::getCountries('');

            $res = [
                'success' => true,
                'message' => 'Customer Create.',
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

    public function show($id)
    {
        try {

            $data['cart'] = cart::with('product')
                ->with('prescription.lenspower')
                ->with('lens')
                ->with(['measurements.precalvalues', 'measurements.thickness'])
                ->find($id);
            $data['cart'] = LensMasters::with('product')
            ->with('prescription.lenspower')
            ->with('lens')
            ->with(['measurements.precalvalues', 'measurements.thickness'])
            ->find($id);
            $res = [
                'success' => true,
                'message' => 'Cart details.',
                'data' => $data,
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

    public function update(Request $request, $id)
    {
        try {

            $cart = cart::find($id);
            $cart->quantities = $request->quantities ;
            $cart->total_amount = (($request->quantities) * ($cart->price));
            $cart->prescription_id = $request->prescriptionId;
            $cart->discount = self::getDiscountAmount($cart->price, $request->quantities, $request->discount);
            // $cart->status = $request->status;
            $cart->status = 1;
            if ($request->quantities == 0){
                $cart->status = 0;
            }
            
            $cart->save();
            $customerId = $cart->customer_id;
            $totalCartItems = cart::where('customer_id', $customerId)->where('status', '1')->count();

            $res = [
                'success' => true,
                'message' => 'Cart updated successfully.',
                'totalCartItems' => $totalCartItems
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

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $cart = cart::where('id', $id)->where('status', '1')->first();

            if (!empty($cart)) {
                $cart->quantities = 0;
                $cart->status = '0';
                $cart->save();
                $res = [
                    'success' => true,
                    'message' => 'cart deleted successfully.',
                    'data' => $cart
                ];
            } else {
                $res = [
                    'success' => false,
                    'data' => 'cart details not found.',
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




    // public function addLens(Request $request)
    // {
    //     try {
    //         $lens = $request;
    //         $cart = cart::where('id', $lens['cartId'])->where('status', '1')->first();

    //         if (!empty($cart)) {
    //             $cartLens = new cartLens();

    //             $eyeData = $request->input('eyeData');

    //             switch ($lens['eye_selection']) {
    //                 case '1':
    //                     $eyeData = $lens['right_eye'];
    //                     break;
    //                 case '2':
    //                     $eyeData = $lens['left_eye'];
    //                     break;
    //                 case '3':
    //                     $eyeData = $lens['both_eyes'];
    //                     break;
    //                 default:

    //                     break;
    //             }

    //             // Set common properties for all eye selections
    //             $cartLens->cart_id = $lens['cartId'];

    //             // Set properties based on eye_selection
    //             $cartLens->eye_selection = $lens['eye_selection'];
    //             $cartLens->brands = $eyeData['brand'];
    //             $cartLens->type = $eyeData['type'];
    //             $cartLens->index = $eyeData['index'];
    //             $cartLens->dia = $eyeData['dia'];
    //             $cartLens->from = $eyeData['from'];
    //             $cartLens->to = $eyeData['to'];
    //             $cartLens->rp = $eyeData['rp'];
    //             $cartLens->max_cyl = $eyeData['max_cyl'];
    //             $cartLens->code = $eyeData['code'];
    //             $cartLens->name = $eyeData['name'];
    //             $cartLens->mrp = $eyeData['mrp'];
    //             $cartLens->cost_price = $eyeData['cost_price'];

    //             $cartLens->save();

    //             $cart->cart_lenses_id = $cartLens->id;
    //             $cart->save();

    //             $res = [
    //                 'success' => true,
    //                 'message' => 'Lens Details added to cart successfully.',
    //                 'data' => $cartLens
    //             ];
    //         }

    //         else {
    //             $res = [
    //                 'success' => false,
    //                 'data' => 'cart details not found.',
    //                 'message' => $lens['cartId']
    //             ];
    //         }

    //         return response()->json($res);
    //     } catch (\Exception $e) {
    //         $res = [
    //             'success' => false,
    //             'data' => 'Something went wrong.',
    //             'message' => $e->getMessage()
    //         ];
    //         return response()->json($res);
    //     }
    // }




    // public function addLens(Request $request)
    // {
    //     try {
    //         $lens = $request;
    //         $cart = cart::where('id', $lens['cartId'])->where('status', '1')->first();

    //         if (!empty($cart)) {
    //             $cartLens = cartLens::where('cart_id', $lens['cartId'])->where('eye_selection', $lens['eye_selection'])->where('status', '1')->first();

    //             if (empty($cartLens)) {
    //                 // If no existing lens, create a new one
    //                 $cartLens = new cartLens();
    //             }

    //             $eyeData = $request->input('eyeData');

    //             switch ($lens['eye_selection']) {
    //                 case '1':
    //                     $eyeData = $lens['right_eye'];
    //                     break;
    //                 case '2':
    //                     $eyeData = $lens['left_eye'];
    //                     break;
    //                 case '3':
    //                     $eyeData = $lens['both_eyes'];
    //                     break;
    //                 default:
    //                     // Handle default case if needed
    //                     break;
    //             }

    //             // Set common properties for all eye selections
    //             $cartLens->cart_id = $lens['cartId'];

    //             // Set properties based on eye_selection
    //             $cartLens->eye_selection = $lens['eye_selection'];
    //             $cartLens->lens_brand = $eyeData['brand'];            
    //             $cartLens->type = $eyeData['type'];
    //             $cartLens->index = $eyeData['index'];
    //             $cartLens->dia = $eyeData['dia'];
    //             $cartLens->from = $eyeData['from'];
    //             $cartLens->to = $eyeData['to'];
    //             $cartLens->rp = $eyeData['rp'];
    //             $cartLens->max_cyl = $eyeData['max_cyl'];
    //             $cartLens->code = $eyeData['code'];
    //             $cartLens->name = $eyeData['name'];
    //             $cartLens->mrp = $eyeData['mrp'];
    //             $cartLens->cost_price = $eyeData['cost_price'];

    //             $cartLens->save();

    //             $cart->cart_lenses_id = $cartLens->id;
    //             $cart->save();

    //             $res = [
    //                 'success' => true,
    //                 'message' => ($cartLens->wasRecentlyCreated ? 'Lens Details added' : 'Lens Details updated') . ' to cart successfully.',
    //                 'data' => $cartLens
    //             ];
    //         } else {
    //             $res = [
    //                 'success' => false,
    //                 'data' => 'Cart details not found.',
    //                 'message' => $lens['cartId']
    //             ];
    //         }

    //         return response()->json($res);
    //     } catch (\Exception $e) {
    //         $res = [
    //             'success' => false,
    //             'data' => 'Something went wrong.',
    //             'message' => $e->getMessage()
    //         ];
    //         return response()->json($res);
    //     }
    // }





    public function addLens(Request $request)
    {
        try {
            $lens = $request->all();
            $cartId = $lens['cartId'];

            $cart = Cart::where('id', $cartId)->where('status', '1')->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'data' => 'Cart details not found.',
                    'message' => $cartId
                ]);
            }

            $eyeSelection = $lens['eye_selection'];

            // $lensMasterData = LensMasters::where('name', $lens['eyeData']['name'])
            //     ->orWhere('code', $lens['eyeData']['code'])
            //     ->first();

            // if (!$lensMasterData) {
            //     return response()->json([
            //         'success' => false,
            //         'data' => 'Lens master details not found for the provided name or code.',
            //         'message' => 'Lens master not found'
            //     ]);
            // }

            $eyeData = [];

            switch ($eyeSelection) {
                case '1':
                    $eyeData = $lens['right_eye'];
                    break;
                case '2':
                    $eyeData = $lens['left_eye'];
                    break;
                case '3':
                    $eyeData = $lens['both_eyes'];
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'data' => 'Invalid eye selection.',
                        'message' => 'Invalid eye selection'
                    ]);
            }

            // Set common properties for all eye selections
            $eyeData = array_merge($eyeData, [
                'cart_id' => $cartId,
                'eye_selection' => $eyeSelection
            ]);

            $cartLens = CartLens::updateOrCreate(
                ['cart_id' => $cartId, 'eye_selection' => $eyeSelection],
                $eyeData
            );

            $cart->cart_lenses_id = $cartLens->id;
            $cart->save();

            $res = [
                'success' => true,
                'message' => ($cartLens->wasRecentlyCreated ? 'Lens Details added' : 'Lens Details updated') . ' to cart successfully.',
                'data' => [
                    'cartLens' => $cartLens,
                    // 'lensMaster' => $lensMasterData // Include lens master data in the response
                ]
            ];

            return response()->json($res);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'data' => 'Something went wrong.',
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }



    public function addMeasurements(Request $request)
    {
        try {

            $measurements = $request;

            $cart = cart::where('id', $measurements['cartId'])->where('status', '1')->first();

            if (!empty($cart)) {

                if (empty($cart['cart_measurements_id'])) {

                    $measurementCart = new cartMeasurements();

                    $measurementCart->cart_id = $measurements['cartId'];
                    $measurementCart->diameter = $measurements['diameter'];
                    $measurementCart->base_curve = $measurements['base_curve'];
                    $measurementCart->vertex_distance = $measurements['vertex_distance'];
                    $measurementCart->pantascopic_angle = $measurements['pantascopic_angle'];
                    $measurementCart->frame_wrap_angle = $measurements['frame_wrap_angle'];
                    $measurementCart->reading_distance = $measurements['reading_distance'];
                    $measurementCart->shape = $measurements['shapes'];

                    $measurementCart->lens_width = $measurements['lens_size']['lens_width'];
                    $measurementCart->bridge_distance = $measurements['lens_size']['bridge_distance'];
                    $measurementCart->lens_height = $measurements['lens_size']['lens_height'];
                    $measurementCart->temple = $measurements['lens_size']['temple'];
                    $measurementCart->total_width = $measurements['lens_size']['total_width'];
                    $measurementCart->created_at = date('Y-m-d H:i:s');
                    $measurementCart->status = 1;

                    $measurementCart->save();



                    $precalValues = $measurements['precal_values'];

                    foreach ($precalValues as $key => $item) {
                        $precalValue = new PrecalValues();
                        $precalValue->cart_id = $measurements['cartId'];
                        $precalValue->eye_type = $key;
                        $precalValue->pd = $item['pd'];
                        $precalValue->ph = $item['ph'];
                        $precalValue->save();
                    }



                    $thickness = $measurements['thickness'];

                    foreach ($thickness as $key => $item) {
                        $precalValue = new Thickness();
                        $precalValue->cart_id = $measurements['cartId'];
                        $precalValue->thickness_type = $key;
                        $precalValue->left = $item['left'];
                        $precalValue->right = $item['right'];
                        $precalValue->save();
                    }

                    $cart->cart_measurements_id = $measurementCart->id;
                    $cart->save();

                    $res = [
                        'success' => true,
                        'message' => 'Lens Details added to cart successfully.',
                        'data' => $measurementCart
                    ];
                } else {

                    $measurementCart = cartMeasurements::where('cart_id', $measurements['cartId'])->where('status', '1')->first();

                    $measurementCart->diameter = $measurements['diameter'];
                    $measurementCart->base_curve = $measurements['base_curve'];
                    $measurementCart->vertex_distance = $measurements['vertex_distance'];
                    $measurementCart->pantascopic_angle = $measurements['pantascopic_angle'];
                    $measurementCart->frame_wrap_angle = $measurements['frame_wrap_angle'];
                    $measurementCart->reading_distance = $measurements['reading_distance'];
                    $measurementCart->shape = $measurements['shapes'];

                    $measurementCart->lens_width = $measurements['lens_size']['lens_width'];
                    $measurementCart->bridge_distance = $measurements['lens_size']['bridge_distance'];
                    $measurementCart->lens_height = $measurements['lens_size']['lens_height'];
                    $measurementCart->temple = $measurements['lens_size']['temple'];
                    $measurementCart->total_width = $measurements['lens_size']['total_width'];
                    $measurementCart->updated_at = date('Y-m-d H:i:s');

                    $measurementCart->save();

                    PrecalValues::where('cart_id', $measurements['cartId'])->where('status', '1')->delete();

                    $precalValues = $measurements['precal_values'];

                    foreach ($precalValues as $key => $item) {
                        $precalValue = new PrecalValues();
                        $precalValue->cart_id = $measurements['cartId'];
                        $precalValue->eye_type = $key;
                        $precalValue->pd = $item['pd'];
                        $precalValue->ph = $item['ph'];
                        $precalValue->save();
                    }

                    Thickness::where('cart_id', $measurements['cartId'])->where('status', '1')->delete();

                    $thickness = $measurements['thickness'];

                    foreach ($thickness as $key => $item) {
                        $precalValue = new Thickness();
                        $precalValue->cart_id = $measurements['cartId'];
                        $precalValue->thickness_type = $key;
                        $precalValue->left = $item['left'];
                        $precalValue->right = $item['right'];
                        $precalValue->save();
                    }

                    $cart->cart_measurements_id = $measurementCart->id;
                    $cart->save();

                    $res = [
                        'success' => true,
                        'message' => 'Measurements Details Updated to cart-Measurements successfully.',
                        'data' => $measurementCart
                    ];
                }
            } else {
                $res = [
                    'success' => false,
                    'data' => 'Measurements details not found.',
                    'message' =>  $measurements['cartId']
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

    public static function getDiscountAmount($price = 0, $quantity = 0, $discount = 0)
    {
        $discountAmount = (($price * $discount) / 100);

        $totalDiscountAmount =  $discountAmount * $quantity;

        return $totalDiscountAmount;
    }

    public function searchLensMaster(Request $request)
    {
        try {
            $searchTerm = $request->input('searchTerm', ''); // Read searchTerm from request body

            // Perform a search in the LensMaster model by name or code
            $lensMasters = LensMasters::where('name', 'like', "%$searchTerm%")
                ->orWhere('code', 'like', "%$searchTerm%")
                ->get();

            if ($lensMasters->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching records found.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $lensMasters
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching for lens masters: ' . $e->getMessage()
            ]);
        }
    }


    public function getLensMasterById(Request $request)
    {
        try {
            $id = $request->input('id');

            // Fetch the lens master by ID
            $lensMaster = LensMasters::find($id);

            if (!$lensMaster) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lens master not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $lensMaster
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching lens master: ' . $e->getMessage()
            ], 500);
        }
    }
}
