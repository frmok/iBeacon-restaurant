<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function orders(){
		return $this->hasMany('App\Order');
	}

	public function scopeRandomOrderedItem(){
		$randomOrder = \DB::table('order')->where('user_id', $this->id)->orderBy(\DB::raw('RAND()'))->first();
		if(!$randomOrder){
			//if the user has not ordered anything, just random one item from all items
			return Order::orderBy(\DB::raw('RAND()'))->first()->item;
		}else{
			//random one item from the items he ordered before
			return Order::find($randomOrder->id)->item;
		}
	}

	public function scopeGetLastOrder(){
		return Order::where('user_id', $this->id)->orderBy('created_at', 'DESC')->first();
	}

}
