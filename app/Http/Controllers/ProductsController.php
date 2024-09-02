<?php

namespace App\Http\Controllers;

use App\Models\ProductMembers;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //

    public function index()
    {
        $products = Products::with(['ProductMembers' => function($query) {
            $query->with('user:id,name'); // Include only the id and name from the users table
        }])->get();
    
        $products = $products->map(function($product) {
            $product->product_members = $product->ProductMembers->map(function($member) {
                return [
                    'member_id' => $member->member_id,
                    'name' => $member->user->name
                ];
            });
            return $product;
        });
    
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);

        $product = new Products();
        $product->brand = $request->input('brand');
        $product->description = $request->input('description');
        $product->category = $request->input('category');
        $product->save();


        $productMembers = $request->input('product_members');
        foreach ($productMembers as $member) {
            $productMember = new ProductMembers();
            $productMember->product_id = $product->id;
            $productMember->member_id = $member;
            $productMember->save();
        }

        return response()->json(['success' => 'Product created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);

        $product = Products::find($id);
        $product->brand = $request->input('brand');
        $product->description = $request->input('description');
        $product->category = $request->input('category');
        $product->save();

        $productMembers = $request->input('product_members');
        ProductMembers::where('product_id', $id)->delete();
        foreach ($productMembers as $member) {
            $productMember = new ProductMembers();
            $productMember->product_id = $product->id;
            $productMember->member_id = $member;
            $productMember->save();
        }

        return response()->json(['success' => 'Product updated successfully.']);
    }

    public function destroy($id)
    {
        $product = Products::find($id);
        $product->delete();

        return response()->json(['success' => 'Product deleted successfully.']);
    }
}
