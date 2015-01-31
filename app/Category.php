<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model {
	protected $table = 'category';
	protected $fillable = ['category_name', 'category_img'];
    public static $img_path = "/assets/category/";

	public function items(){
		return $this->hasMany('App\Item');
	}

}
