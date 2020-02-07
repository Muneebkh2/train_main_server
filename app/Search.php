<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    public $table = "search";

    protected $fillable = [
        'surname','skills','subskills',
        'postcode','location','date'
    ]; 

}