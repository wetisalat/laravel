<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Models\User;
use App\Models\Staff;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\Undefined;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->where('user_id', '=', auth()->user()->id)->get();
        return response()->json([
            'staffs' => $users
        ]);
    }

    public function getStaffList(){

        $users = User::with('roles')->find(auth()->user()->id);
        if($users->roles[0]->name == 'staff')
            $users = User::select('id', 'avatar', 'name')->get();
        else $users = User::select('id', 'avatar', 'name')->where('user_id', auth()->user()->id)->get();
        return response()->json([
            'staffs'=> $users
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created staff in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'gender' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email'    => 'required|email|unique:users',            
            'phoneNumber'    => 'required',            
        ]);

        try {
            $user = User::create([
                'user_id' => auth()->user()->id,
                'gender' => $request->gender,
                'name'  => $request->firstName . ' ' . $request->lastName,
                'firstname' => $request->firstName,
                'lastname'  => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'password' => bcrypt($request->firstName) // Set initial password as the first name
            ]);

            // Assign staff role 
            $roleExists = Role::where('name', '=', 'staff')->exists();

            if ($roleExists) {
                $user->assignRole('staff');
            }

            // Send email verification
            event(new Registered($user));

            return response()->json([
                'message' => 'Staff addedd successfully'
            ]);
        } catch (\Exception $e) {
            print ($e->getMessage());
            return response()->json([
                'message' => 'Error adding staff'
            ], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json( User::with('detail')->find($id) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $config = json_decode(file_get_contents('php://input'));
        $data = json_decode(json_encode($config->detail_data), true);
        $user = User::find($id);

        if(isset($data['avatar']) && str_contains($data['avatar'], ':')){
            /* Remove old image if present */
            if ($user['avatar'] && file_exists($user['avatar']))
                unlink(public_path($user['avatar']));

            $path = NULL;

            $image_64 = $data['avatar']; //your base64 encoded data

            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
          
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
          
            // find substring fro replace here eg: data:image/png;base64,
            
            $image = str_replace($replace, '', $image_64); 
            
            $image = str_replace(' ', '+', $image); 
            
            $imageName = 'images/avatar/'.Str::random(10).'.'.$extension;
            
            $path = Storage::disk('public')->put($imageName, base64_decode($image));

            $user['avatar'] = "storage/".$imageName;
        }

        $user->fill([
            'gender' => isset($data['gender'])?$data['gender']:$user['gender'],
            'name'  => (isset($data['firstname'])?$data['firstname']:$user['firstname']) . ' ' . (isset($data['lastname'])?$data['lastname']:$user['lastname']),
            'firstname' => isset($data['firstname'])?$data['firstname']:$user['firstname'],
            'lastname'  => isset($data['lastname'])?$data['lastname']:$user['lastname'],
	    'phone' => isset($data['phone'])?$data['phone']:$user['phone'],
        ]);
        $user->save();

        try {
            $keys = array_keys($data);
            $staff = Staff::where('user_id',$id)->first();
            if($staff){
            } else {
                $staff = Staff::create([
                    'user_id' => $id,
                ]);
                $staff = Staff::where('user_id',$id)->first();
            }

            $compare_keys = array_keys($staff->toArray());

            foreach ($keys as $key => $value) {
                if(in_array($value, $compare_keys)){
                    if(str_contains($value, 'salary')){
                        $temp = str_replace(",", "", $data[$value]);
                        $staff[$value] = isset($data[$value])?$temp:0;
                    } elseif(str_contains($value, 'date') ){
                        $staff[$value] = isset($data[$value])?date('Y-m-d', strtotime($data[$value])):null;
                    } elseif($value == "user_id"){
                    }
                    else {
                        $staff[$value] = isset($data[$value])?$data[$value]:'';
                    }
                }
            }
	    $staff['deleted_at']=null;
            $staff->save();

        } catch (\Exception $e) {
            print ($e->getMessage());
            return response()->json([
                'message' => 'Error Updating staff'
            ], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Staff updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = User::find($id);

        $staff->delete();

        return response()->json([
            'message' => 'Staff deleted successfully'
        ]);
    }

    /**
     * Remove multiple records
     */
    public function destroyMultiple() 
    {
        $ids = request()->ids;

        User::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => 'Staffs deleted successfully'
        ]);
    }
}
