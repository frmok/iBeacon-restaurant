<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Order;

class Advertisement {
	protected $table = 'advertisement';
	protected $fillable = []; //to be determined
	
    /**
    * Return the advertisement message tailored for a specific member.
    *
    * @param  int $memberId
    * @return Response
    */
    public static function getMessage($memberId = null){
        if($memberId){
            //check last dining time
            $user = User::find($memberId);
            if(!property_exists($user->getLastOrder(), 'created_at')){
                $lastOrderTime = date('Y-m-d H:i:s');
            }else{
                $lastOrderTime = $user->getLastOrder()->created_at;
            }
            $currentTime = date('Y-m-d H:i:s');
            $secondDifference = abs(strtotime($lastOrderTime) - strtotime($currentTime));
            $hourDifference = intval(round($secondDifference/3600));


            $randomItemName = $user->randomOrderedItem()->item_name;
            $response = array();
            if($hourDifference >= 72){
                $response['message'] = 'Long time no see, do you miss our '.$randomItemName.'?';
            }else{
                $response['message'] = 'Welcome back. Would you like to try '.$randomItemName.' again?';
            }
            return \Response::json($response);
        }else{
            $response = array();
            $randomItemName = Order::orderBy(\DB::raw('RAND()'))->first()->item->item_name;
            $response['message'] = 'I bet you will like our '.$randomItemName.'.';
            return \Response::json($response);
        }
    }
}
