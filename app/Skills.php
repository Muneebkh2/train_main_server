<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    public $table = "skills";

    protected $fillable = [
        'skills_title','skills_desc',
    ];  

    public function Subskills() 
    {
        return $this->HasMany('App\Subskills');
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