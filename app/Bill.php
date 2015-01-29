<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {
	protected $table = 'bill';
	protected $fillable = ['table_id', 'amount', 'user_id', 'status'];

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function table(){
		return $this->belongsTo('App\Table');
	}
}
