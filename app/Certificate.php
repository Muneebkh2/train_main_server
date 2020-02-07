<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $table = "certificates";

    protected $fillable = [
        'resource_id',
        'title',
        'issued_by',
        'issued_year',
        'expertise_level',
    ];  

    public function Resource()
    {
        return $this->belongsToMany('App\Resource');
    }


}