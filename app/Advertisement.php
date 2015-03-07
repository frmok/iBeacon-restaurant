<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement {
	protected $table = 'advertisement';
	protected $fillable = []; //to be determined

    public function getMessage($memberId = null){
        if($memberId){
            //check last dining time
            //<3 days: Would you like to try {{random order item}} again?
            //>3 days: Long time no see, do you miss our {{random order item}}?
        }else{
            //randomly choose one message
        }
    }
}
