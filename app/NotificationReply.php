<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class NotificationReply extends Model
{

    public $table = "notification_reply";

    protected $fillable = [
        'resource_id'
    ];  

    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

    public function notification()
    {
        return $this->belongsTo('App\Notification');
    }

}