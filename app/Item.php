<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {
	protected $table = 'item';
	protected $fillable = ['item_name', 'item_img', 'price', 'category_id', 'description', 'item_time'];
    public static $img_path = "/assets/item/";

	public function category(){
		return $this->belongsTo('App\Category');
	}
}
