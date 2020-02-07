<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notification;
use App\Resource;
use App\Skills;
use App\NotificationReply;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{

    public function __construct(){
        // $this->middleware('auth');
    }

    public function get_unseen()
    {
        $res = Resource::get();
        foreach ($res as $value) {
            $lastlogin = $value->last_login;
        }
        $notification = Notification::where('created_at', '>', $lastlogin)->get();
        return response()->json((!$notification->isEmpty()) ? true : false);
    }

    public function get_unseen_notificaiton($id)
    {
        $res = Resource::find($id);
        $lastlogin = $res->last_login;
        $notification = Notification::where('created_at', '>', $lastlogin)->get();
        return response()->json((!$notification->isEmpty()) ? true : false);
    }

    public function get_notification_reply()
    {
        try {
            $res = NotificationReply::orderBy('created_at','desc')->with('resource','notification')->get();
            return $res;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'getting data failed !'.$th], 409);
        }
    }

    // NotificaitonReply
    public function notification_reply(Request $request)
    {

        try {

            $this->validate($request, [
                'reply' => 'required'
            ]);
    
            // inserting object.
            $notification_reply = new NotificationReply;
            $notification_reply->notification_reply = $request->input('reply');
            $notification_reply->resource_id = $request->input('resource_id');
            $notification_reply->notification_id = $request->input('notification_id');
            $notification_reply->save();
    
            return response()->json(['message' => 'Reply Send.'], 201);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'reply sending failed !'.$th], 409);
        }

    }

    public function all(Request $request)
    {
        try {
            $notifications_all = Notification::where('notification_type', 'all')->orderBy('created_at','desc')->get();
            return response()->json($notifications_all);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Get Notification By All Failed!'.$th], 409);
        }
    }
    
    public function skills(Request $request)
    {
        try {
            $notifications_skills = Notification::where('notification_type', 'skills')->get();
            return response()->json($notifications_skills);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Get Notification By Skills Failed!'.$th], 409);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'notification_title' => 'required|string',
            'notification_desc' => 'required',
            'notification_type' => 'required'
        ]);

        try {
            // inserting object.
            $notification = new Notification;
            $notification->notification_title = $request->input('notification_title');
            $notification->notification_desc = $request->input('notification_desc');
            $notification->notification_type = $request->input('notification_type');
            
            $notification->save();

            if($request->input('notification_type') == "skills"){

                $res = Skills::where('id',$request->input('notification_skills_id'))->with('resource')->get();

                for($i=0; $i<count($res[0]->resource); $i++)
                {
                    $email_body = "<br>Title: ".$request->input('notification_title')."<br><br><br>Description: ".$request->input('notification_desc');
                    // $email_body = $request->input('notification_desc');
                    $data = array(
                        'to' => $res[0]->resource[$i]->email,
                        'from' => 'muneeburrehman@syscrypt.co.uk',
                        'subject' => 'New Notification | From TrainerManagement',
                        'content' => $email_body,
                    );
                    sleep(1);
                    Mail::send([], [], function($message) use ($data) {
                        $message->from($data['from']);
                        $message->to($data['to']);
                        $message->subject($data['subject']);
                        $message->setBody($data['content'], 'text/html');
                    });

                    $notification->resource()->attach($res[0]->resource[$i]->id);
                }

            }
            else if($request->input('notification_type') == "emails"){

                $a = explode("," , $request->input('notification_emails'));

                for($i=0; $i<count($a); $i++)
                {
                    $email_body = "<br>Title: ".$request->input('notification_title')."<br><br><br>Description: ".$request->input('notification_desc');
                    $data = array(
                        'to' => $a[$i],
                        'from' => 'muneeburrehman@syscrypt.co.uk',
                        'subject' => 'New Notification | From TrainerManagement',
                        'content' => $email_body,
                    );
                    sleep(1);
                    Mail::send([], [], function($message) use ($data) {
                        $message->from($data['from']);
                        $message->to($data['to']);
                        $message->subject($data['subject']);
                        $message->setBody($data['content'], 'text/html');
                    });
                    $res = Resource::where("email", $a[$i])->firstOrFail();
                    $notification->resource()->attach($res->id);
                }

            }
            else if($request->input('notification_type') == "all"){
                $res = Resource::where('is_active', 1)->get();
                // $res = Resource::all();
                for($i=0; $i<count($res); $i++)
                {
                    $email_body = "<br>Title: ".$request->input('notification_title')."<br><br><br>Description: ".$request->input('notification_desc');
                    $data = array(
                        'to' => $res[$i]->email,
                        'from' => 'muneeburrehman@syscrypt.co.uk',
                        'subject' => 'New Notification | From TrainerManagement',
                        'content' => $email_body,
                    );
                    sleep(1);
                    Mail::send([], [], function($message) use ($data) {
                        $message->from($data['from']);
                        $message->to($data['to']);
                        $message->subject($data['subject']);
                        $message->setBody($data['content'], 'text/html');
                    });
                    $notification->resource()->attach($res[$i]->id);
                }
            }
            return response()->json(['message' => 'New Notification Created.'], 201);
        }catch (\Throwable $th) {
            return response()->json(['message' => 'Creation failed !'.$th], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $update_notification = Notification::find($id);
            $update_notification->update($request->all());
            // input initalized.
            $resource = $request->input('resource_id');
            // ...
            $input['resource_id'] = $resource;
            // update foriegn keys
            $update_notification->resource()->sync($input['resource_id']);

            return response()->json(['message' => 'Notification Updated Successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Notification Updation Failed!'.$th], 409);
        }
    }

    public function destroy($id)
    {
        try {
            $delete_notificaiton = Notification::findOrFail($id);
            $delete_notificaiton->resource()->detach();
            $delete_notificaiton->delete();

            return response()->json(['message', 'Notification Deleted Successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message', 'Notification Deletion Failed!'], 409);
        }
    }

}
