<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Client;
use App\Models\User;
use App\Models\Location;
use App\Models\Shift;
use App\Models\ConventionCodeApe;
use App\Models\ConventionCollective;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class AccountSettingController extends Controller
{

    //
    public function getData(Request $request)
    {
        $user = DB::table('users')->leftjoin('clients', 'users.id', '=', 'clients.user_id')->where('users.id', '=', $request['id'])->select("users.*", "clients.company", "clients.siret", "clients.tva_intracom")->first();
        unset($user->password);
        return response()->json(['data' => $user]);
    }
    public function saveChange(Request $request)
    {
        $this->validate($request, [
            'email' => ['required'],
            'phone_number' => ['required'],
        ]);

        $id = $request['id'];
        $avatar = $request['avatar'];
        $company = $request['company'];
        $email = $request['email'];
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $phone_number = $request['phone_number'];
        $siret = $request['siret'];
        $tva_intracom = $request['tva_intracom'];
        $user = User::where('id', '=', $id)->first();
        if ($user) {
            $user->email = $email;
            $user->name=$first_name." ".$last_name;
            $user->avatar = $avatar;
            $user->firstname = $first_name;
            $user->lastname = $last_name;
            $user->phone = $phone_number;
            $user->update();
        }
        $client = Client::where('user_id', '=', $id)->first();
        if ($client) {
            $client->siret = $siret;
            $client->tva_intracom = $tva_intracom;
            $client->company = $company;
            $client->update();
        } else {
            $client = new Client();
            $client->user_id = $id;
            $client->siret = $siret;
            $client->tva_intracom = $tva_intracom;
            $client->company = $company;
            $client->save();
        }
        return response()->json(['success' => true, 'message' => "Successfully Updated"]);
    }

    public function passSaveChange(Request $request)
    {
        $this->validate($request, [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:8',
            'retypePassword' => 'required|same:newPassword'
        ]);
        $id = $request['id'];
        $oldPass = $request['oldPassword'];
        $newPass = $request['newPassword'];
        $retypePass = $request['retypePassword'];
        $user = User::where('id', '=', $id)->first();
        if (\Hash::check($oldPass, $user->password)) {
            $user->password = Hash::make($newPass);
            $user->update();
            return response()->json(['success' => true, 'message' => 'Successfully Updated']);
        } else {
            return response()->json(['success' => false, 'message' => 'Wrong old password']);
        }
    }
    public function getAddress(Request $request)
    {
        $address = Client::where('user_id', '=', $request['id'])->first();
        return response()->json(['data' => $address]);
    }
    public function addressSaveChange(Request $request)
    {
        $id = $request['id'];
        $client = Client::where('user_id', '=', $id)->first();
        if ($client) {
            $client->address1 = $request['address1'];
            $client->address2 = $request['address2'];
            $client->country = $request['country'];
            $client->postcode = $request['postcode'];
            $client->city = $request['city'];
            $client->update();
        } else {
            $client = new Client();
            $client->user_id = $request['id'];
            $client->address1 = $request['address1'];
            $client->address2 = $request['address2'];
            $client->country = $request['country'];
            $client->postcode = $request['postcode'];
            $client->city = $request['city'];
            $client->save();
        }
        return response()->json(['success' => true, 'message' => "Successfully Updated"]);
    }

    public function addLocation(Request $request)
    {
        $location = new Location();
        $location->user_id = $request['id'];
        $location->company_name = $request['companyname'];
        $location->immatriculation = $request['immatriculation'];
        $location->occupational_medicine = $request['occupationalmedicine'];
        $location->address1 = $request['address1'];
        $location->address2 = $request['address2'];
        $location->postcode = $request['postcode'];
        $location->city = $request['city'];
        $location->country = $request['country'];
        $location->selectedconvention = $request['conventionName'];
        $location->selectedcodeAPE = $request['ape'];
        $location->status = 1;
        $location->save();
        return response()->json(['success' => true, 'message' => "Successfully Saved!"]);
    }
    public function updateLocation(Request $request){
        $location = Location::find($request['id']);
        $location->company_name = $request['company_name'];
        $location->immatriculation = $request['immatriculation'];
        $location->occupational_medicine = $request['occupational_medicine'];
        $location->address1 = $request['address1'];
        $location->address2 = $request['address2'];
        $location->postcode = $request['postcode'];
        $location->city = $request['city'];
        $location->country = $request['country'];
        $location->selectedconvention = $request['selectedconvention1'];
        $location->selectedcodeAPE = $request['selectedcodeAPE1'];
        $location->update();
        return response()->json(['success' => true, 'message' => "Successfully Updated!"]);
    }
    public function getLocation(Request $request)
    {
        $user_id = $request['id'];
        $user = DB::table('users')->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.user_id')->where('users.id', '=', $user_id)->select("users.*", "model_has_roles.role_id")->first();
        // if ($user->role_id == 2) {
        //     $data = Location::where('user_id', $user->user_id)->get();
        //     $staffData = User::where('user_id', $user->user_id)->get(['id', 'name', 'firstname', 'lastname'])->toArray();
        // } else {
        $data = Location::where('user_id', $user_id)->get();
        $staffData = User::where('user_id', $user_id)->get(['id', 'name', 'firstname', 'lastname'])->toArray();
       
        $locationStaffArr = array();
        for ($g = 0; $g < count($staffData); $g++) {
            $words = explode(" ", $staffData[$g]['name']);
            $id = explode(" ", $staffData[$g]['id']);

            // Avatar change to profile_photo_path
            $userAvatar = User::where('id', '=', $id)->select('avatar')->get();

            $acronym = "";
            foreach ($words as $w) {
                $acronym .= $w[0];
            }

            $avatar = "";
            foreach ($userAvatar as $a) {
                $avatar .= $a['avatar'];
            }

            $staffData[$g]['stfName'] = strtoupper($acronym);
            $staffData[$g]['avatar'] = $avatar;
            $locationStaffArr[$staffData[$g]['id']][] = $staffData[$g];
        }

        //        echo "<pre>";print_r($locationStaffArr);die;
        foreach ($data as $key => $value) {
            $staffArr = array();

            if ($value['shift_id'] !== null) {
                $staffId = explode(',', $value['shift_id']);

                foreach ($staffId as $staff) {
                    $staffArr[] = User::where('id', $staff)->select(['id', 'name', 'firstname', 'lastname'])->first();
                }
            }


            $moreStaff = 0;
            if (isset($locationStaffArr[$data[$key]['id']])) {
                $totalStaff = count($locationStaffArr[$data[$key]['id']]);
                if ($totalStaff > 3) {
                    $moreStaff = ($totalStaff - 3);
                }
                //                $staffArr = $locationStaffArr[$data[$key]['id']];
            }

            $data[$key]['staffArr'] = $staffArr;
            $data[$key]['moreStaff'] = $moreStaff;
            $value->responsive_id = null;
        }
        return response()->json(['data' => $data]);
    }

    public function deleteLocation(Request $reqest){
        $location = Location::find($reqest['id']);
        $location->delete();

        return response()->json([
            'success' => true,
            'message' => "Deleted Successfully."
        ]);
    }

    public function changeStatusLocation(Request $reqest){
        $location = Location::find($reqest['id']);
        $status=1;
        if($location->status==1){
            $status=2;
        }else{
            $status=1;
        }
        $location->status=$status;
        $location->update();
        return response()->json([
            'success' => true,
            'message' => "Status Updated Successfully."
        ]);
    }

    public function downloadImage(Request $request)
    {
        $imageName = $this->fileUpload($request['image']);
        return response()->json(['data' => 'storage/images/avatar/' . $imageName]);
    }

    public static function fileUpload($image)
    {


        if ($image) {

            $img = $image;

            $folderPath = 'storage/images/avatar/';

            if (!File::exists('storage/images/avatar')) {
                File::makeDirectory('storage/images/avatar', 0777, true);
            }
            $imageName = time() . '.' . explode('/', explode(
                ':',
                substr($image, 0, strpos($image, ';'))
            )[1])[1];

            $image_parts = explode(";base64,", $img);
            $image_base64 = base64_decode($image_parts[1]);

            $file = $folderPath . $imageName;
            file_put_contents($file, $image_base64);

            return $imageName;
        }
    }

    public function addShift(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'unique:shifts'],
            'starttime' => ['required'],
            'endtime' => ['required'],
        ]);

        $shift = new Shift();
        $shift->user_id = $request->id;
        $shift->name = $request->name;
        $shift->start_time = $request->starttime;
        $shift->end_time = $request->endtime;
        $shift->break_time = $request->breaktime;
        $shift->working_time = $request->workingtime;
        $shift->save();

        return response()->json([
            'success' => true,
            'message' => "Successfully Saved!"
        ]);
    }

    public function loadShift(Request $request)
    {
        $shifts = Shift::where('user_id', $request['id'])->get();
        $shiftList = [];

        foreach ($shifts as $value) {

            $getShift = [];
            $getShift['id'] = $value->id;
            $getShift['name'] = $value->name;
            $getShift['start_time'] = $value->start_time;
            $getShift['end_time'] = $value->end_time;
            $getShift['working_time'] = $value->working_time;
            $getShift['break_time'] = $value->break_time;
            $getShift['users'] = User::where('shift_id', $value->id)->select('id', 'name')->get();
            $shiftList[] = $getShift;
        }

        return response()->json(["data" => $shiftList]);
    }

    public function deleteShift(Request $reqest)
    {
        $shift = Shift::find($reqest['id']);
        $shift->delete();

        return response()->json([
            'success' => true,
            'message' => "Shift Delete Successfully."
        ]);
    }

    public function editShift(Request $request)
    {
        $shift = Shift::find($request['id']);
        return response()->json([
            'success' => true,
            'data' => $shift
        ]);
    }

    public function updateShift(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'starttime' => ['required'],
            'endtime' => ['required'],
        ]);

        $shift = Shift::find($request->id);
        // $shift->user_id = $request['user_id'];
        $shift->name = $request->name;
        $shift->start_time = $request->starttime;
        $shift->end_time = $request->endtime;
        $shift->break_time = $request->breaktime;
        $shift->working_time = $request->workingtime;
        $shift->update();

        return response()->json([
            'success' => true,
            'message' => 'Shift update successfully!'
        ]);
    }
    
    public function getConvention(){
        $conventioncollective=ConventionCollective::get();
        return response()->json(['data'=>$conventioncollective]);
    }
    public function getCode(Request $reqest){
        $code=ConventionCodeApe::where('convention_collective_id','=',$reqest['code'])->get();
        return response()->json(['code'=>$code]);
    }
}
