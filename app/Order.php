<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
	protected $table = 'order';
	protected $fillable = ['bill_id', 'item_id', 'quantity', 'order_status'];
    public static $statusText = [0 => 'Ordered', 1 => 'Processing'];
	public function bill(){
		return $this->belongsTo('App\Bill');
	}

	public function item(){
		return $this->belongsTo('App\Item');
	}

    public function getStatusTextAttribute(){
        return self::$statusText[$this->order_status];
    }
}
