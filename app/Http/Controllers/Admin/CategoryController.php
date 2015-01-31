<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Category;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index()
    {
        $categories = Category::all();
        return view('admin.category_index')->with('categories', $categories);
    }

    public function detail($id = NULL)
    {
        if(isset($id)){
            $category = Category::find($id);
            $action = 'update';
        }else{
            $category = new Category();
            $action = 'create';
        }
        return view('admin.category_detail')->with('category', $category)->with('action', $action);
    }

    public function create(Request $request)
    {
        $category = new Category();
        $category->fill($request->except('category_img'));
        if ($request->hasFile('category_img') === true) {
            $destinationPath = public_path() . Category::$img_path;
            $request->file('category_img')->move($destinationPath, $request->file('category_img')->getClientOriginalName());
            $category->category_img = $request->file('category_img')->getClientOriginalName();
        }
        $category->save();
        return view('admin.category_detail')->with('category', $category)->with('action', 'update')->with('msg', 'Added successfully');
    }

    public function update(Request $request)
    {
        $category = Category::find($request->get('id'));
        $category->fill($request->except('category_img'));
        if ($request->hasFile('category_img') === true) {
            $destinationPath = public_path() . Category::$img_path;
            $request->file('category_img')->move($destinationPath, $request->file('category_img')->getClientOriginalName());
            $category->category_img = $request->file('category_img')->getClientOriginalName();
        }
        $category->save();
        return view('admin.category_detail')->with('category', $category)->with('action', 'update')->with('msg', 'Updated successfully');
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->to('admin/category');

    }
}