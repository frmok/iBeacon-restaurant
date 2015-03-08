<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Order;
use App\Item;
use App\Stat;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class StatController extends Controller {

    public function index()
    {
        return view('admin.stat_index');
    }

    public function ajax_best_selling_item2(){
        $items = Stat::bestItem();
        foreach($items as $item){
            $data[] = $item->total;
            $labels[] = $item->item_name;
        }
        $json["datasets"] = $data;
        $json["labels"] = $labels;
        echo json_encode($json);
    }


    public function ajax_best_selling_item(){
        $items = Stat::bestItem();
        $json["values"] = array();
        foreach($items as $item){
            $data = [$item->item_name, intval($item->total)];
            array_push($json["values"], $data);
        }
        $json["key"] = "Best Selling Items";
        echo json_encode($json);
    }

    public function ajax_profit(){
        $bestItems = \DB::table('order')->join('item', 'item.id', '=', 'order.item_id')
        ->select([\DB::raw('sum(quantity * price) as total'), \DB::raw('DAY(order.created_at) as day'),'order.created_at'])
        ->where(\DB::raw('YEAR(order.created_at)'), date('Y'))
        ->where(\DB::raw('MONTH(order.created_at)'), date('n'))
        ->groupBy(\DB::raw('YEAR(order.created_at)'))
        ->groupBy(\DB::raw('MONTH(order.created_at)'))
        ->groupBy(\DB::raw('DAY(order.created_at)'))
        ->orderBy('order.id', 'DESC')
        ->get();
        $totalDay = date('t', strtotime($bestItems[0]->created_at));
        for($i = 1; $i <= $totalDay; $i++){
            $report["day"] = $i;
            $report["total"] = 0;
            foreach($bestItems as $item){
                if($item->day == $i){
                    $report["total"] = $item->total;
                    break;
                }
            }
            $reports[] = $report;
        }
        $json["values"] = array();
        foreach($reports as $report){
            $data = [$report["day"], intval($report["total"])];
            array_push($json["values"], $data);
        }
        $json["key"] = "Profit of this month";

        echo json_encode($json);
    }

    public function ajax_profit2(){
        $bestItems = \DB::table('order')->join('item', 'item.id', '=', 'order.item_id')
        ->select([\DB::raw('sum(quantity * price) as total'), \DB::raw('DAY(order.created_at) as day'),'order.created_at'])
        ->where(\DB::raw('YEAR(order.created_at)'), date('Y'))
        ->where(\DB::raw('MONTH(order.created_at)'), date('n'))
        ->groupBy(\DB::raw('YEAR(order.created_at)'))
        ->groupBy(\DB::raw('MONTH(order.created_at)'))
        ->groupBy(\DB::raw('DAY(order.created_at)'))
        ->orderBy('order.id', 'DESC')
        ->get();
        $totalDay = date('t', strtotime($bestItems[0]->created_at));
        for($i = 1; $i <= $totalDay; $i++){
            $report["day"] = $i;
            $report["total"] = 0;
            foreach($bestItems as $item){
                if($item->day == $i){
                    $report["total"] = $item->total;
                    break;
                }
            }
            $reports[] = $report;
        }
        foreach($reports as $report){
            $data[] = $report["total"];
            $labels[] = $report["day"];
        }
        $json["datasets"] = $data;
        $json["labels"] = $labels;

        echo json_encode($json);
    }

}