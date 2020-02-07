<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Resource;
use App\Availability;
use App\Skills;
use App\Subskills;
use App\Notification;
// use Mail;
use App\User;

class ResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){ 
        // $this->middleware('auth');
    }


    //Get Counts
    public function getCounts()
    {
        $res[0] = Resource::count();
        $res[1] = Skills::count();
        $res[2] = Subskills::count();
        $res[3] = Notification::count();
        return response()->json($res);
    }

    public function getLastLogin()
    {
        $res = Resource::where('is_active',1)->orderBy('last_login', 'DESC')->take(10)->get();
        //  $res = Resource::orderBy('last_login', 'DESC');
        return response()->json($res);
    }

    // check last update of availability
    public function getLastUpdate__availability($id)
    {
        $res = availability::where('resource_id',$id)->orderBy('created_at', 'DESC')->get();
        $date = date('F d, Y - H:i', strtotime($res[0]->created_at));
        return response()->json($date);
    }

    public function index()
    {
        $res = Resource::with('skills')->get();
        return response()->json($res);
    }

    public function all()
    {
        $res = Resource::with('skills', 'subskills', 'availability')->get();
        return response()->json($res);
    }

    // upload profile image
    public function upload_image(Request $request, $id)
    {
        $this->validate($request, [
            // check validtion for image or file
            'file' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $res = Resource::find($id);
        if ($res) {

            if ( $request->hasFile('file')){
                if ($request->file('file')->isValid()){
                    $file = $request->file('file');
                    $name = $id.'.jpg';
                    $file->move('images' , $name);
                    $inputs = $request->all();
                    $inputs['path'] = $name;
                    $res->image = $inputs;
                    $res->save();
                    // $res->image($inputs);
                    return response()->json(['message' => 'Image Updated.'], 201);
                }else {
                    return response()->json(['message' => 'file format not allowed!'], 409);
                }
            }else {
                return response()->json(['message' => 'Please attach file to upload!'], 409);
            }

        }else{
            return response()->json(['message' => 'Resource not found!'], 409);
        }

    }

    public function getStats()
    {
        $res = [];
        for($i=1; $i<=12; $i++)
        {
            $res[$i] = Resource::whereMonth('created_at', $i)->count();
        }
        return response()->json($res);
    }

    public function show($id)
    {
        // $skills = Resource::find($id)->skills()->get();
        // $subskills = Resource::find($id)->subskills()->get();
        // $skills = $request->skills;
        // $qualifications = $request->qualifications;

        // return Resource::whereHas('skills', function ($q) use ($skills) {
        //     $q->whereIn('resource_id', $id);
        // })->orWhereHas('subskills', function ($q) use ($subskills) {
        //     $q->whereIn('resource_id', $id);
        // })->get();

        try{
            $res = Resource::with('skills', 'subskills', 'availability')->find($id);
            return response()->json($res);
        }
         catch (\Throwable $th) {
                return response()->json(['message' => 'Error!' .$th], 409);
        }
    }

     public function getResource($token)
        {
            try{
                $res = Resource::where('remember_token', $token)->get();
                return response()->json($res);
            }
             catch (\Throwable $th) {
                    return response()->json(['message' => 'Error!' .$th], 409);
            }
        }

    public function certificate(Request $request, $id)
    {    }

    public function availability(Request $request, $id)
    {
        $this->validate($request, [
            'availability_date' => 'required',
        ]);

        try {

            $dates = $request->input('availability_date');
            // $all_dates = count($dates);
            // dd(count($dates));die;
            // for ($i=0; $i < $all_dates ; $i++) {
            //     $availabilities = new Availability();
            //     $availabilities->resource_id = $id;
            //     $availabilities->availability = $dates[$i];
            //     $availabilities->save();
            // }
            // $resource_id = Resource::find($id);
            // if ($resource->id ) {
            //     # code...
            // }
            // if(updateOrCreate)
            // $newUser = \App\UserInfo::updateOrCreate([
            //     //Add unique field combo to match here
            //     //For example, perhaps you only want one entry per user:
            //     'user_id'   => Auth::user()->id,
            // ],[
            //     'about'     => $request->get('about'),
            //     'sec_email' => $request->get('sec_email'),
            //     'gender'    => $request->get("gender"),
            //     'country'   => $request->get('country'),
            //     'dob'       => $request->get('dob'),
            //     'address'   => $request->get('address'),
            //     'mobile'    => $request->get('cell_no')
            // ]);
            // $existing = Base_voter::where('resource_id', $id)->first();
            //         // select * from base_voters where `name` = 'someName' and (`phone` = 'somePhone' or `email` = 'someEmail')
            //     if ($existing) {
            //         // do an update on $existing
            //         $existing->fill($voter_arr);
            //     } else {
            //             // create new one
            //         Base_voter::create($voter_arr);
            //     }

            $existing = Availability::where('resource_id', $id)->first();
            if ($existing) {
                // $update_resource = Availability::find($id);
                Availability::where('resource_id', $id)->delete();
                // Availability::where('resource_id', $id)->update(['availability' => $date]);
                foreach($dates as $date){
                    $availabilities = new Availability();
                    $availabilities->resource_id = $id;
                    $availabilities->availability = $date;
                    $availabilities->save();
                }
            } else {
                foreach($dates as $date){
                    $availabilities = new Availability();
                    $availabilities->resource_id = $id;
                    $availabilities->availability = $date;
                    $availabilities->save();
                }
            }

            // foreach($dates as $date){


            //     // Availability::updateOrCreate(
            //     //     ['resource_id' => $id],
            //     //     ['availability' => $date]
            //     // );
            // }

            // foreach($dates as $date){
            //     $availabilities = new Availability();
            //     $availabilities->resource_id = $id;
            //     $availabilities->availability = $date;
            //     $availabilities->save();
            // }
            return response()->json(['message' => 'Dates Successfully Added!'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error!' .$th], 409);
        }
    }

    public function invites($token)
    {
        // forActivation resource
        $check_resource_token = Resource::where('remember_token', $token)->first();
        if ($check_resource_token) {
            $res_id = $check_resource_token['id'];
            $is_active = $check_resource_token['is_active'];
            if ($is_active == 1) {
                return response()->json(['message' => 'Already Activated.'], 201);
            }else {
                try {
                    Resource::where('id', $res_id)->update(['is_active' => 1]);
                    // resource password updated.
                    $res_pwd = User::where('email', $check_resource_token->email)->first();
                    if (app('hash')->check($token, $res_pwd->password)) {
                        // return response()->json(['message' => 'Account Activated.','redirection_url' => 'http://localhost:4200/#/reset-password/'], 201);
                        return redirect(env('CLIENT_URL').'#/reset-password/'.$token);
                    }else{
                        return response()->json(['message' => 'Account Activated.'], 201);
                    }
                } catch (\Throwable $th) {
                    return response()->json(['message' => 'Activation failed, Resource not found!'.$th], 409);
                }
            }
        }else {
            return response()->json(['message' => 'Error, Resource not found!'], 409);
        }
    }

    public function create(Request $request)
    {
        $resource_email = $request->input('email');
        $token_key = Str::random(32);

        $this->validate($request,[
            'firstname' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'street' => 'required',
            'town' => 'required',
            'city' => 'required',
            'postcode' => 'required'
        ]);

        try {
            // (closed)$input['availability'] = $request->input('availability');

            $resource = new Resource();
            $resource->firstname = $request->input('firstname');
            $resource->surname = $request->input('surname');
            $resource->email = $request->input('email');
            $resource->phone = $request->input('phone');
            $resource->street = $request->input('street');
            $resource->town = $request->input('town');
            $resource->city = $request->input('city');
            $resource->postcode = $request->input('postcode');
            $resource->rating = $request->input('rating');
            $resource->rates_per_hour = $request->input('rates_per_hour');
            // (closed)$resource->notes = $request->input('notes');
            $resource->remember_token = $token_key;
            // (closed)$resource->availability = $request->input('availability');
            // (closed)dd($request->input('availability'));
            // (closed)dd($input['availability'][0]);
            // (closed)die;
            $resource->save();

            // foriegn key insetion...
            $skills = $request->input('skills');
            $subskills = $request->input('subskills');
            $resource->skills()->attach($skills);
            $resource->subskills()->attach($subskills);
            // (closed)dd($token_key);die();
            // Creating account of resource...
            $user = new User();
            $user->name = $request->input('firstname');
            $user->email = $resource_email;
            $user->password = app('hash')->make($token_key);
            $user->save();


            $email_body = '<p>Please activate your account by clicking this link</p> <br> <a href="http://18.205.160.255:8000/api/resource/verify/'.$token_key.'">Activate</a><br><small>"'.$token_key.'"</small><br><br><br>';
            Mail::send([], [], function ($message) use ($resource_email, $email_body){
                $message->to($resource_email)
                  ->subject("Account Confirmation | From TrainerManagement")
                  ->setBody($email_body, 'text/html');
            });

            // Mail::send([], $data, function ($message) use ($resource_email, $email_body){
            //     $message->to($resource_email);
            //     $message->from('no-reply@syscrypt.co.uk');
            //     $message->subject("Account Confirmation | From TrainerManagement");
            //     $message->setBody($email_body, 'text/html');
            // });
            // Mail::send([], [], function ($message) {
            //     this->message->to($resource->email)->subject("Account Confirmation | From TrainerManagement")
            //       ->setBody($email_body, 'text/html')
            // });

            // Mail::to($request->user())
            // ->subject("Account Confirmation | From TrainerManagement")
            // ->setBody($email_body, 'text/html')
            // ->send(new OrderShipped($order));
            // $data = array(
            //     'to' => $resource_email,
            //     'from' => 'muneeburrehman@syscrypt.co.uk',
            //     'subject' => 'Account Confirmation | From TrainerManagement',
            //     'content' => $email_body,
            // );

            // Mail::send([], [], function($message) use ($data) {
            //     $message->from($data['from']);
            //     $message->to($data['to']);
            //     $message->subject($data['subject']);
            //     $message->setBody($data['content'], 'text/html');
            // });

            return response()->json(['message' => 'New Resource Created.'], 201);

        } catch (\Throwable $th) {

            return response()->json(['message' => 'Creation failed !'.$th], 409);
        }

    }

    public function update(Request $request, $id)
    {
        try {

            $update_resource = Resource::find($id);
            // $update_resource->update($request->all());
            $update_resource->firstname = $request->input('firstname');
            $update_resource->surname = $request->input('surname');
            $update_resource->email = $request->input('email');
            $update_resource->phone = $request->input('phone');
            $update_resource->street = $request->input('street');
            $update_resource->town = $request->input('town');
            $update_resource->city = $request->input('city');
            $update_resource->postcode = $request->input('postcode');
            $update_resource->rating = $request->input('rating');
            $update_resource->rates_per_hour = $request->input('rates_per_hour');
            $update_resource->notes = $request->input('notes');
            $update_resource->save();

            // input initalized.
            $skills = $request->input('skills_id');
            $subskills = $request->input('subskills_id');
            // ...
            $input['skills_id'] = $skills;
            $input['subskills_id'] = $subskills;
            // update foriegn keys
            $update_resource->skills()->sync($input['skills_id']);
            $update_resource->subskills()->sync($input['subskills_id']);

            return response()->json(['message' => 'Resource Updated Successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Resource Updation Failed!'.$th], 409);
            //throw $th;
        }

    }

    public function updateNotes_Skills(Request $request, $id)
        {
            try {

                $update_resource = Resource::find($id);

                $update_resource->notes = $request->input('notes');
                $update_resource->save();

                // input initalized.
                $skills = $request->input('skills_id');
                $subskills = $request->input('subskills_id');
                // ...
                $input['skills_id'] = $skills;
                $input['subskills_id'] = $subskills;
                // update foriegn keys
                $update_resource->skills()->sync($input['skills_id']);
                $update_resource->subskills()->sync($input['subskills_id']);

                return response()->json(['message' => 'Resource Updated Successfully'], 201);
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Resource Updation Failed!'.$th], 409);
                //throw $th;
            }

        }

    public function destroy(Request $request, $id)
    {
        try {
            $delete_resource = Resource::findOrFail($id);
            $delete_resource->skills()->detach();
            $delete_resource->subskills()->detach();
            $delete_resource->delete();

            return response()->json(['message' => 'Resource Deleted Successfully!'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Resource Deletion Failed!'.$th], 409);
        }
    }

    // Invites
    public function getInvites()
    {
        $res = Resource::where('is_invited', 1)->get();
        return response()->json($res);
    }

    public function deleteInvite(Request $request)
    {
        // var_dump($request->input('id'));
        try {
            $invite = Resource::find($request->input('id'));
            $invite->is_invited = 0;
            $invite->save();
            return response()->json(['message' => 'Invite Deleted Successfully!'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Invite Deletion Failed!'.$th], 409);
        }
    }

    public function resendInvite(Request $request)
    {
        $resource_email = $request->input('email');
//        $res = Resource::where('email', $resource_email)->get();

//        var_dump($res);
//            return response()->json($res, 201);

        try {
            $res = Resource::where('email', $resource_email)->get();
//            $email_body = '<p>Please activate your account by clicking this link</p> <br> <a href="http://18.205.160.255:8000/api/resource/verify/'.$token_key.'">Activate</a><br><small>"'.$token_key.'"</small>';
            $email_body = '<p>Please activate your account by clicking this link</p> <br> <a href="http://18.205.160.255:8000/api/resource/verify/'.$res[0]->remember_token.'">Activate</a><br><small>"'.$res[0]->remember_token.'"</small><br><br><br>';

            $data = array(
                'to' => $resource_email,
                'from' => 'muneeburrehman@syscrypt.co.uk',
                'subject' => 'Account Confirmation | From TrainerManagement',
                'content' => $email_body,
            );

            Mail::send([], [], function($message) use ($data) {
                $message->from($data['from']);
                $message->to($data['to']);
                $message->subject($data['subject']);
                $message->setBody($data['content'], 'text/html');
            });

            return response()->json($res, 201);

        } catch (\Throwable $th) {

            return response()->json(['message' => 'Invite Email failed !'.$th], 409);
        }

    }

}
