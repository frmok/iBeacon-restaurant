<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueType extends Model{
    protected $table = 'queue_type';
    protected $fillable = []; //to be determined

    public function tickets(){
        return $this->hasMany('App\Ticket');
    }

    public static function typeByPeople($people){
        return self::where('capacity', '>=', $people)->orderBy('capacity', 'ASC')->first()->id;
    }
}