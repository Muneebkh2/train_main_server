<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Cohorts extends Model
{
    public $table = "cohorts";
    
    protected $fillable = [
        'cohorts_name',
        'cohorts_desc'
    ];  

    public function resource()
    {
        return $this->belongsToMany('App\Resource')->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skills')->withTimestamps();
    }

    public function subskills()
    {
        return $this->belongsToMany('App\Subskills')->withTimestamps();
    }
}