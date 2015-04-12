<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueType extends Model{
    protected $table = 'queue_type';
    protected $fillable = ['name', 'capacity', 'disabled']; //to be determined

    public function getIdAttribute($value)
    {
        return intval($value);
    }
    
    public function getCapacityAttribute($value)
    {
        return intval($value);
    }
    
    public function getDisabledAttribute($value)
    {
        return intval($value);
    }

    public function tickets(){
        return $this->hasMany('App\Ticket');
    }

    /**
    * Return the ID of the queue type according to number of people
    *
    * @param  int $people
    * @return int
    */
    public static function typeByPeople($people){
        $type = self::where('capacity', '>=', $people)->orderBy('capacity', 'ASC')->get();
        if(count($type) <= 0){
            return 0;
        }
        return $type[0]->id;
    }
}