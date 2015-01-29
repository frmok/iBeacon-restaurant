<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model {
	protected $table = 'table';
	protected $fillable = ['table_name', 'capacity'];

	public function bills(){
		return $this->hasMany('App\Bill');
	}
}
