<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model {
	protected $table = 'table';
	protected $fillable = ['table_name', 'capacity', 'major', 'minor', 'table_status'];

	public function bills(){
		return $this->hasMany('App\Bill');
	}

    public function openedBill()
    {
        
    }

    public function closedBills()
    {

    }
}
