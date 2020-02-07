<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    public $table = "availability";

    protected $fillable = [
        'resource_id',
        'availability'
    ];  

    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

}