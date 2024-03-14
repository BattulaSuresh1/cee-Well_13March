<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrescriptionRequest;
use App\Models\BaseCurves;
use App\Models\Diameters;
use App\Models\FrameWrapAngles;
use App\Models\LensPower;
use App\Models\LensRecommended;
use App\Models\LensShapes;
use App\Models\LifeStyle;
use App\Models\MirrorCoating;
use App\Models\PantascopicAngles;
use App\Models\PrecalValues;
use App\Models\Prescription;
use App\Models\ReadingDistance;
use App\Models\Thickness;
use App\Models\TintColors;
use App\Models\TintGradient;
use App\Models\TintGradients;
use App\Models\TintTypes;
use App\Models\VertexDistances;
use App\Models\LensBrands;
use App\Models\Dia;
use App\Models\EyeSelection;
use App\Models\Index;
use App\Models\type;
use App\Models\From;
use App\Models\To;
use App\Models\Rp;
use App\Models\LensDetails;
use App\Models\LensMasters;
use App\Models\Max_cyl;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{

    public function index(Request $request){
        $customerId = $request->customerId;

        $prescriptions = Prescription::with('lenspower')//->with('precalvalues')->with('thickness')
        ->where([
            ['status', '=', '1'],
            ['customer_id','=',$customerId],
        ])->orderBy('id', 'DESC')->get();
   
        $res = [
            'success' => true,
            'message' => 'Prescription details.',
            'data' => $prescriptions
        ];
       
        return response()->json($res);
    }

    public function create(){
        try{

            // $data['LensMasters'] =LensMasters::get();
            $data['prescritionTypes'] = ["Ophthalmic Lens" , "Contact Lens"];
            $data['power_measurements'] = [
                "+6.00",
                "+5.75",
                "+5.50",
                "+5.25",
                "+5.00",
                "+4.75",
                "+4.50",
                "+4.25",
                "+4.00",
                "+3.75",
                "+3.50",
                "+3.25",
                "+3.00",
                "+2.75",
                "+2.50",
                "+2.25",
                "+2.00",
                "+1.75",
                "+1.50",
                "+1.25",
                "+1.00",
                "+0.75",
                "+0.50",
                "+0.25",
                "-0.25",
                "-0.50",
                "-0.75",
                "-1.00",
                "-1.25",
                "-1.50",
                "-1.75",
                "-2.00",
                "-2.25",
                "-2.50",
                "-2.75",
                "-3.00",
                "-3.25",
                "-3.50",
                "-3.75",
                "-4.00",
                "-4.25",
                "-4.50",
                "-4.75",
                "-5.00",
                "-5.25",
                "-5.50",
                "-5.75",
                "-6.00",
            ];
            
            $data['prescriptionValidity'] = [
                "3 months",
                "6 months",
                "1 year",
            ];

            // $data['life_styles'] = LifeStyle::where(['status' => '1'])->get();
            // $data['lens_recommendeds'] = LensRecommended::where(['status' => '1'])->get();
            // $data['tint_types'] = TintTypes::where(['status' => '1'])->get();
            // $data['mirror_coatings'] = MirrorCoating::where(['status' => '1'])->get();
            // $data['tint_colors'] = TintColors::where(['status' => '1'])->get();
            // $data['tint_gradients'] = TintGradients::where(['status' => '1'])->get();
            
            $data['eye_selections'] = EyeSelection::where(['status' => '1'])->get();
            $data['lens_brands'] = LensBrands::where(['status' => '1'])->get();
            $data['type'] = Type::where(['status' => '1'])->get();
            $data['index'] = Index::where(['status' => '1'])->get();
            $data['from'] = From::where(['status' => '1'])->get();
            $data['dia'] = Dia::where(['status' => '1'])->get();
            $data['to'] = To::where(['status' => '1'])->get();
            $data['rp'] = Rp::where(['status' => '1'])->get();
            $data['max_cyl'] = Max_cyl::where(['status' => '1'])->get();
            

            $data['diameters'] = Diameters::where(['status' => '1'])->get();
            $data['base_curves'] = BaseCurves::where(['status' => '1'])->get();
            $data['vertex_distances'] = VertexDistances::where(['status' => '1'])->get();
            $data['pantascopic_angles'] = PantascopicAngles::where(['status' => '1'])->get();
            $data['frame_wrap_angles'] = FrameWrapAngles::where(['status' => '1'])->get();
            $data['reading_distances'] = ReadingDistance::where(['status' => '1'])->get();
            $data['shapes'] = LensShapes::where(['status' => '1'])->get();

            $res = [
                'success' => true,
                'message' => 'Prescription Create.',
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

 

    public function store(StorePrescriptionRequest $request)
    {
        try{

            $power_details = $request->power_details;
            // $measurements = $request->measurements;
            // $lens = $request->lens;

            $prescription = new Prescription();
            
            $prescription->lens_type = $power_details['prescritionType'];
            $prescription->validity = $power_details['prescriptionValidity'];
            $prescription->given_by = $power_details['prescribed_by'];
            $prescription->attachment = $power_details['attachment'];
            $prescription->customer_id = $request->customer_id;
            $prescription->remarks = $power_details['remarks'];

         /*    $prescription->life_style = $lens['life_style'];
            $prescription->lens_recommended = $lens['lens_recommended'];
            $prescription->tint_type = $lens['tint_type'];
            $prescription->tint_value = ($lens['tint_type'] == "Colour")? $lens['colour'] : $lens['gradient'];
            $prescription->mirror_coating = $lens['mirror_coating'];

            $prescription->diameter = $measurements['diameter'];
            $prescription->base_curve = $measurements['base_curve'];
            $prescription->vertex_distance = $measurements['vertex_distance'];
            $prescription->pantascopic_angle = $measurements['pantascopic_angle'];
            $prescription->frame_wrap_angle = $measurements['frame_wrap_angle'];
            $prescription->reading_distance = $measurements['reading_distance'];
            $prescription->shape = $measurements['shapes'];

            $prescription->lens_width = $measurements['lens_size']['lens_width'];
            $prescription->bridge_distance = $measurements['lens_size']['bridge_distance'];
            $prescription->lens_height = $measurements['lens_size']['lens_height'];
            $prescription->temple = $measurements['lens_size']['temple'];
            $prescription->total_width = $measurements['lens_size']['total_width'];
            $prescription->created_at = date('Y-m-d H:i:s');
            $prescription->status = 1;
            
 */
            
              
            $prescription->save();

            if($power_details['prescritionType'] == 'Ophthalmic Lens'){

                $ophthamicLens = $power_details['ophthalmic_lens'];

                foreach ($ophthamicLens as $key => $items) {

                    foreach ($items as $category => $item) {

                        $lensPower = new LensPower();
                        $lensPower->prescription_id = $prescription->id;
                        $lensPower->lens_type = $power_details['prescritionType'];
                        $lensPower->eye_type = $key;
                        $lensPower->category = $category;
                        $lensPower->sph = $item['sphere'];
                        $lensPower->cyl = $item['cylinder'];
                        $lensPower->axis = $item['axis'];
                        $lensPower->add = $item['add'];
                        $lensPower->pd = $item['pd'];
                        $lensPower->prism = $item['prism'];



                        $lensPower->save();
                    }
                }

            }else{
                $contactLens = $power_details['contact_lens'];

                foreach ($contactLens as $key => $item) {
                    $lensPower = new LensPower();
                    $lensPower->prescription_id = $prescription->id;
                    $lensPower->lens_type = $power_details['prescritionType'];
                    $lensPower->eye_type = $key;
                    $lensPower->sph = $item['sph'];
                    $lensPower->cyl = $item['cyl'];
                    $lensPower->axis = $item['axis'];
                    $lensPower->bc = $item['bc'];
                    $lensPower->dia = $item['dia'];

                    $lensPower->save();
                }
            }

            
            $res = [
                'success' => true,
                'message' => 'Prescription created successfully.'
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

   
    public function uploadAttachments(Request $request){
        try{

            // $request->validate([
            //     'file' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048'
            // ]);

            if($request->file()) {
                $originalFileName = $request->file->getClientOriginalName();
                $fileName = time().'_'.str_replace(' ', '_', $originalFileName);

                if($request->uploadType === "products"){
                    $filePath = $request->file('file')->storeAs('product', $fileName, 'public');
                }else if($request->uploadType === "customer"){
                    $filePath = $request->file('file')->storeAs('customer', $fileName, 'public');
                }else{
                    $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
                }

            }
        
            $res = [
                'success' => true,
                'message' => 'file uploaded.',
                'data' => $filePath
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
