<?php namespace App\Http\Controllers\Admin;

use Auth;
use App\Category;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller {
/*
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
*/
    /**
    * Return all categories.
    *
    * @return array
    */
    public function index(){
        $categories = Category::all();
        foreach($categories as $category){
            $category['category_img'] = rawurlencode($category['category_img']);
        }
        return $categories;
    }
    
    /**
    * Return the data of a specific category.
    *
    * @param  int $id
    * @return Category
    */
    public function detail($id){
        return Category::find($id);
    }
    
    /**
    * Create a new category with the submitted data and return the new category data.
    *
    * @param  Request $request
    * @return Category
    */
    public function add(Request $request)
    {
        $category = new Category();
        $category->fill($request->except('category_img'));
        if ($request->hasFile('category_img') === true) {
            $destinationPath = public_path() . Category::$img_path;
            $request->file('category_img')->move($destinationPath, $request->file('category_img')->getClientOriginalName());
            $category->category_img = $request->file('category_img')->getClientOriginalName();
        }
        $category->save();
        return $category;
    }

    /**
    * Update the category with submitted data and return the updated data.
    *
    * @param  Request $request
    * @return Category
    */
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
        return $category;
    }
    
    /**
    * Delete a specific category
    *
    * @param  Request $request
    * @return void
    */
    public function delete(Request $request)
    {
        $category = Category::find($request->get('id'));
        $category->delete();
    }
}