<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Item;
use App\Category;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller {
/*
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
*/
    /**
    * Return all items.
    *
    * @return array
    */
    public function index(){
        return Item::all();
    }
    
    /**
    * Return the data of a specific item.
    *
    * @param  int $id
    * @return Item
    */
    public function detail($id){
        echo Item::find($id);
    }
    
    /**
    * Create a new item with the submitted data and return the new item data.
    *
    * @param  Request $request
    * @return Item
    */
    public function add(Request $request)
    {
        $item = new Item();
        $item->fill($request->except('item_img'));
        if ($request->hasFile('item_img') === true) {
            $destinationPath = public_path() . Item::$img_path;
            $request->file('item_img')->move($destinationPath, $request->file('item_img')->getClientOriginalName());
            $item->item_img = $request->file('item_img')->getClientOriginalName();
        }
        $item->save();
        return $item;
    }

    /**
    * Update the item with submitted data and return the updated data.
    *
    * @param  Request $request
    * @return Item
    */
    public function update(Request $request)
    {
        $item = Item::find($request->get('id'));
        $item->fill($request->except('item_img'));
        if ($request->hasFile('item_img') === true) {
            $destinationPath = public_path() . Item::$img_path;
            $request->file('item_img')->move($destinationPath, $request->file('item_img')->getClientOriginalName());
            $item->item_img = $request->file('item_img')->getClientOriginalName();
        }
        $item->save();
        return $item;
    }
    
    /**
    * Delete a specific item
    *
    * @param  Request $request
    * @return void
    */
    public function delete(Request $request)
    {
        $item = Item::find($request->get('id'));
        $item->delete();
    }
}