<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = "notification";

    protected $fillable = [
        'notification_title',
        'notification_desc',
        'notification_type',
    ];  

    public function Resource()
    {
        return $this->belongsToMany('App\Resource')->withTimestamps();
    }

    public function notification_reply()
    {
        return $this->hasMany('App\NotificationReply');
    }

}