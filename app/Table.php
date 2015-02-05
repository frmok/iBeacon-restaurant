<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model {
	protected $table = 'table';
	protected $fillable = ['table_name', 'capacity', 'major', 'minor', 'table_status'];

    public static function boot()
    {
        parent::boot();
        static::created(function($table)
        {
            \Event::fire('table.created', array($table));
        });
        static::updated(function($table)
        {
            \Event::fire('table.updated', array($table));
        });
    }
	public function bills(){
		return $this->hasMany('App\Bill');
	}

    public function openedBill()
    {
        return Bill::where('table_id', $this->id)->where('status', 0)->first();
    }

    public function closedBills()
    {

    }
}
