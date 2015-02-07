<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
	protected $table = 'order';
	protected $fillable = ['bill_id', 'item_id', 'quantity', 'order_status'];
    public static $statusText = [0 => 'Ordered', 1 => 'Processing', 2 => 'Done', 3 => 'Paid'];

    public static function boot()
    {
        parent::boot();
        static::created(function($order)
        {
            \Event::fire('order.created', array($order));
        });
        static::updated(function($order)
        {
            \Event::fire('order.updated', array($order));
        });
    }


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
