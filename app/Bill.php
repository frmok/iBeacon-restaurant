<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {
	protected $table = 'bill';
	protected $fillable = ['table_id', 'amount', 'user_id', 'status'];
    public static $statusText = [0 => 'Not Paid', 1 => 'Paid'];

    public static function boot()
    {
        parent::boot();
        static::created(function($bill)
        {
            \Event::fire('bill.created', array($bill));
        });
        static::updated(function($bill)
        {
            \Event::fire('bill.updated', array($bill));
        });
    }

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function table(){
		return $this->belongsTo('App\Table');
	}

    public function orders(){
        return $this->hasMany('App\Order');
    }

    /**
    * Get outstanding balance
    *
    * @return int
    */
    public function tempAmount(){
        $amount = 0;
        foreach($this->orders as $order){
            $amount += $order->item->price * $order->quantity;
        }
        return $amount;
    }

    /**
    * Return the status in text
    *
    * @return string
    */
    public function getStatusTextAttribute(){
        return self::$statusText[$this->status];
    }

    /**
    * Get the outstanding balance
    *
    * @return int
    */
    public function outStandingBalance(){
        $amount = 0;
        foreach($this->orders as $order){
            if($order->order_status != 3){
                $amount += $order->item->price * $order->quantity;
            }
        }
        return $amount;
    }

}
