<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Human extends Model
{
    protected $fillable = ['id','first_name','last_name','email','gender','country'];
    
    public static function fromCountry(){
        $people = new Human();
        $query = $people
            ->select(array('humans.country', DB::raw('COUNT(humans.country) as national')))
            ->groupBy('country')
            ->get()
            ->toArray();
        return $query;
    }
}
