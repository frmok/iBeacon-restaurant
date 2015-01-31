<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Item;
use App\Category;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller {

    public function index()
    {
        $items = Item::all();
        return view('admin.item_index')->with('items', $items);
    }

    public function detail($id = NULL)
    {
        $cats = Category::all();
        $catOption = array();
        foreach ($cats as $cat) {
            $categories[$cat->id] = $cat->category_name;
        }
        if(isset($id)){
            $item = Item::find($id);
            $action = 'update';
        }else{
            $item = new Item();
            $action = 'create';
        }
        return view('admin.item_detail')->with('item', $item)->with('action', $action)->with('categories', $categories);
    }

    public function create(Request $request)
    {
        $cats = Category::all();
        $catOption = array();
        foreach ($cats as $cat) {
            $categories[$cat->id] = $cat->category_name;
        }
        $item = new Item();
        $item->fill($request->except('item_img'));
        if ($request->hasFile('item_img') === true) {
            $destinationPath = public_path() . Item::$img_path;
            $request->file('item_img')->move($destinationPath, $request->file('item_img')->getClientOriginalName());
            $item->item_img = $request->file('item_img')->getClientOriginalName();
        }
        $item->save();
        return view('admin.item_detail')->with('item', $item)->with('action', 'update')->with('msg', 'Added successfully')->with('categories', $categories);
    }

    public function update(Request $request)
    {
        $cats = Category::all();
        $catOption = array();
        foreach ($cats as $cat) {
            $categories[$cat->id] = $cat->category_name;
        }
        $item = Item::find($request->get('id'));
        $item->fill($request->except('item_img'));
        if ($request->hasFile('item_img') === true) {
            $destinationPath = public_path() . Item::$img_path;
            $request->file('item_img')->move($destinationPath, $request->file('item_img')->getClientOriginalName());
            $item->item_img = $request->file('item_img')->getClientOriginalName();
        }
        $item->save();
        return view('admin.item_detail')->with('item', $item)->with('action', 'update')->with('msg', 'Updated successfully')->with('categories', $categories);
    }

    public function delete($id)
    {
        $item = Item::find($id);
        $item->delete();
        return redirect()->to('admin/item');

    }
}