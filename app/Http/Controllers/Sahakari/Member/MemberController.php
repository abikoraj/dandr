<?php

namespace App\Http\Controllers\Sahakari\Member;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Distributer;
use App\Models\Farmer;
use App\Models\Member;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    //XXX Loading members
    public function index(){
        return view('sahakari.member.index',['members'=>Member::select('name','member_no','is_farmer','is_distributer','is_supplier','is_customer','id')->get()]);
    }
    //XXX Adding a new member
    public function add(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $messages = [];
            if ($request->filled('is_farmer')) {

                if (!$request->filled('farmer_no')) {
                    array_push($messages, "Please Enter Farmer No.");
                }
                if (!$request->filled('center_id')) {
                    array_push($messages, "Please Select A Collection Center");
                }

                if ($request->filled('userate')) {
                    if (!$request->filled('f_rate')) {
                        array_push($messages, "Please Add Fixed Rate Or UnCheck Fixed Rate");
                    }
                }
                if ($request->filled('farmer_no') && $request->filled('center_id')) {
                    if (Farmer::where('center_id', $request->center_id)->where('no', $request->farmer_no)->count() > 0) {
                        $center = Center::where('id', $request->center_id)->first();
                        array_push($messages, "Farmer With Farmer No " . $request->farmer_no . " already exists in" . $center->name);
                    }
                }
            }

            if ($request->filled('is_distributer')) {
                if ($request->filled('d_is_fixed')) {
                    if (!$request->filled('d_fixed_rate')) {
                        array_push($messages, "Please Add Fixed Rate Or UnCheck Fixed Rate For Distributer");
                    }
                }
                
            }
            if($request->filled('d')){

                foreach ($request->d as $key => $d) {
                    if ($request->filled('di-' . $d)) {
                        $di = $request->input('di-' . $d);
                        
                        if (NepaliDate::isWrongDate($di)) {
                            array_push($messages, "The Date " . $di . " For Document Issue Date is Invalid");
                        }
                    }
                }
            }
            if($request->filled('dob')){
                if (NepaliDate::isWrongDate($request->dob)) {
                    array_push($messages, "The Date " . $request->dob . " For Date Of Birth is Invalid");
                }
            }
            if($request->filled('join_date')){
                if (NepaliDate::isWrongDate($request->join_date)) {
                    array_push($messages, "The Date " . $request->join_date . " For Joined Date is Invalid");
                }
            }

            if($request->filled('n_dob')){
                if (NepaliDate::isWrongDate($request->n_dob)) {
                    array_push($messages, "The Date " . $request->n_dob . " For Nominee Date Of Birth is Invalid");
                }
            }

            if($request->acc_type=="dependent"){
                if($request->filled('ref_acc')){
                    if(Member::where('ref_acc',$request->ref_acc)->count()<=0){
                        array_push($messages, "Reference Account ".$request->ref_acc." Not Found For Dependent Account.");
                    }
                }else{
                    array_push($messages, "No Reference Account Found For Dependent Account.");
                }
            }

            if (count($messages) > 0) {
                return response()->json($messages, 500);
            }
            $user = new User();
            $user->name = $request->name;
            $user->phone=$request->phone??"NA";
            $user->address=$request->address??"NA";
            $user->role=$request->role??"-1";
            $user->password=bcrypt(mt_rand(88888,99999));
            $user->save();

            $member=new Member();
            //XXX General Info
            $member->name=$request->name;
            $member->name_nepali=$request->name_nepali;
            $member->phone=$request->phone??"NA";
            $member->type=$request->type;
            $member->acc_type=$request->acc_type;
            $member->is_farmer=$request->is_farmer;
            $member->is_distributer=$request->is_distributer;
            $member->is_supplier=$request->is_supplier;
            $member->is_customer=$request->is_customer;
            $member->member_no=$request->member_no;
            $member->ref_acc=$request->ref_acc;
            $member->join_date=$request->join_date;
            $member->dob=$request->dob;
            $member->gender=$request->gender;
            $member->pan_no=$request->pan_no;
            $member->reg_no=$request->reg_no;
            $member->father_name=$request->father_name;
            $member->mother_name=$request->mother_name;
            $member->spouse_name=$request->spouse_name;
            $member->grandfather_name=$request->grandfather_name;
            //XXX address
            $member->country=$request->country;
            $member->province=$request->province;
            $member->district=$request->district;
            $member->mun=$request->mun;
            $member->ward=$request->ward;
            $member->tole=$request->tole;
            $member->house_no=$request->house_no;
            $member->c_country=$request->c_country;
            $member->c_province=$request->c_province;
            $member->c_district=$request->c_district;
            $member->c_mun=$request->c_mun;
            $member->c_ward=$request->c_ward;
            $member->c_tole=$request->c_tole;
            $member->c_house_no=$request->c_house_no;
            //nominee detail
            $member->name=$request->name;
            $member->name_nepali=$request->name_nepali;
            $member->n_relation=$request->n_relation;
            $member->n_dob=$request->n_dob;
            $member->n_gender=$request->n_gender;
            $member->father_name=$request->father_name;
            $member->mother_name=$request->mother_name;
            $member->spouse_name=$request->spouse_name;
            $member->grandfather_name=$request->grandfather_name;
            //nomiee address detail
            $member->n_country=$request->n_country;
            $member->n_province=$request->n_province;
            $member->n_district=$request->n_district;
            $member->n_mun=$request->n_mun;
            $member->n_ward=$request->n_ward;
            $member->n_tole=$request->n_tole;
            $member->n_house_no=$request->n_house_no;
            //nomiee document
            $member->n_document_name=$request->n_document_name;
            $member->n_document_no=$request->n_document_no;
            $member->n_issued_date=$request->n_issued_date;
            $member->n_issued_from=$request->n_issued_from;
            if($request->hasFile('image')){
                $member->image=$request->image->store('members/'.userDir($user->id)) ;
            }
            $member->save();
            if($member->is_farmer==1){
                $farmer=new Farmer();
                $farmer->user_id = $user->id;
                $farmer->center_id = $request->center_id;
                $farmer->usecc = $request->usecc ?? 0;
                $farmer->usetc = $request->usetc ?? 0;
                $farmer->userate = $request->userate ?? 0;
                $farmer->rate = $request->rate;
                $farmer->save();
            }
            
            if($member->is_distributer==1){
                $dis = new Distributer();
                $dis->user_id = $user->id;
                $dis->rate = $request->rate??0;
                $dis->amount = $request->amount??0;
                $dis->snf_rate = $request->snf_rate??0;
                $dis->fat_rate = $request->fat_rate??0;
                $dis->added_rate = $request->added_rate??0;
                $dis->is_fixed = $request->d_is_fixed??0;
                $dis->fixed_rate = $request->d_fixed_rate??0;
                $dis->credit_days = $request->credit_days;
                $dis->credit_limit = $request->credit_limit;
                $dis->save();
            }

           
            
        } else {
            return view('sahakari.member.add');
        }
    }
}
