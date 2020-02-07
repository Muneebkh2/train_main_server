<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Subskills extends Model
{
    public $table = "subskills";

    protected $fillable = [
        'subskills_title','subskills_desc','skills_id'
    ];  

    public function Skills() 
    {
        return $this->belongsTo('App\Skills');
    }
    public function Resource()
    {
        return $this->belongsToMany('App\Resource');
    }

    public function Cohorts()
    {
        return $this->belongsToMany('App\Cohorts');
    }
}