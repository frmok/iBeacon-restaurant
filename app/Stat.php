<?php namespace App;


class Stat{

    public static function bestItem(){
        $items = \DB::table('order')->join('item', 'item.id', '=', 'order.item_id')
        ->select([\DB::raw('count(1) as total'), 'item_name'])
        ->groupBy('item_id')
        ->orderBy('total', 'desc')
        ->get();
        return $items;
    }
}
