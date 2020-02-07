<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    public $table = "resource";

    protected $fillable = [
        'firstname','surname','email',
        'phone','street','town','city',
        'postcode','rating','rates_per_hour',
        'notes','availability','skills_id','subskills_id'
    ]; 
    
    public function certificates()
    {
        return $this->hasMany('App\Certificate');
    }

    public function availability()
    {
        return $this->hasMany('App\Availability');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skills', 'resource_skills', 'resource_id', 'skills_id')->withTimestamps();
    }

    public function subskills()
    {
        return $this->belongsToMany('App\Subskills')->withTimestamps();
    }

    public function cohorts()
    {
        return $this->belongsToMany('App\Cohorts');
    }

    public function notification()
    {
        return $this->belongsToMany('App\Notification');
    }

    public function notification_reply()
    {
        return $this->hasMany('App\NotificationReply');
    }

    

}