<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use Toastr;

use App\Http\Requests\productRequest;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;


class ProductController extends Controller
{

    public function index() 
    {

    }

    public function create()
    {

        $user = User::findOrFail(auth()->user()->id);

        $notifications = Notification::all();

        $categories = Category::all();

        return view('product/create',['categories' => $categories, 'notifications' => $notifications, 'user' => $user]);

    }

    public function store(productRequest $request) 
    {

        $product = Product::create([
            "name"  => $request->name,
            "img"   => str_replace('data:image/png;base64,', '', $request->img),
            "desc"  => $request->desc,
            "price" => $request->price,
            "user_id" => auth()->user()->id
        ]);


        if ($request->hasFile('img')) 
        {

            $img = $request->file('img');

            $filename = time(). "-" . $img->getClientOriginalName();

            $request->img->storeAs('public/products/images', $filename);

            $product->img = $filename;

        }

        $categories = array_unique($request->categories);

        $product->categories()->attach($categories);

        auth()->user()->products()->save($product);

        Toastr::info('Product has been added!');

        $product->save();

        return redirect(route('user.profile'));


    }

    public function show($id)
    {

    }

    public function edit($id) 
    {
    
     $user = User::firstOrFail();

     $categories = Category::all();

     $product = Product::where('id', $id)->with(['categories'])->firstOrFail();

     return view('product/edit', ['user' => $user, 'product' => $product, 'categories' => $categories]);

    }

    public function update($id, productRequest $request)
     {

      $product = Product::findOrFail($id);

      $oldImg = public_path("storage/products/images/{$product->img}");

      if (File::exists($oldImg)) 
      {

          unlink($oldImg);

      }

      $product->update([
        "name"  => $request->name,
        "img"   => str_replace('data:image/png;base64,', '', $request->img),
        "desc"  => $request->desc,
        "price" => $request->price,
        "user_id" => auth()->user()->id
      ]);

      if ($request->hasFile('img')) {

          $img = $request->file('img');

          $filename = time(). "-" . $img->getClientOriginalName();

          $request->img->storeAs('public/products/images', $filename);

          $product->img = $filename;

      }

      $product->categories()->sync($request->categories);

      auth()->user()->products()->save($product);

      Toastr::info('Product has been updated!');

      $product->update();

      return redirect(route('user.profile'));

     }


     public function destroy($id)
     {

        $product = Product::findOrFail($id);

        $productImg = public_path("storage/products/images/{$product->img}");

        if (File::exists($productImg)) {

            unlink($productImg);

        }
        
        $product->categories()->detach();

        $product->delete();

        Toastr::info('Product has been removed !');

        return redirect(route('user.profile'));

     }

}
