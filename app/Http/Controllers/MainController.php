<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;


class MainController extends Controller
{
    public function index() {
        $allProducts=Product::all();
        $hotSales=Product::where('type','sale')->get();
        $newArrival=Product::where('type','new-arrival')->get();
        $bestSeller=Product::where('type','Best Sellers')->get();

        return view('index', compact('allProducts', 'hotSales', 'newArrival', 'bestSeller'));
    }
    public function cart() {
       $cartitems=DB::table('products')->join('carts', 'carts.productid', 'products.id')
                    ->select('products.title', 'products.quantity as pqty','products.price', 'products.picture', 'carts.*')
                    ->where('carts.customerid', session()->get('id'))
                    ->get();
        return view('shopping-cart', compact('cartitems'));
    }
    public function checkout() {
        return view('checkout');
    }
    public function shop() {
        return view('shop');
    }
    public function singleProduct($id) {
        $product=Product::find($id);
        return view('singleProduct',compact('product'));
    }
    public function deleteCartItem($id) {
        $cartItem=Cart::find($id);
        $cartItem->delete();
        return redirect()->back()->with('success', '1 Item has been deleted from your Cart');
    }
    public function register() {
        return view('register');
    }
    public function registerUser(Request $data){
        $user=new User();
        $user->fullname=$data->input('fullname');
        $user->email=$data->input('email');
        $user->password=$data->input('password');
        $user->type="Customer";
        $user->picture=$data->file('file')->getClientOriginalName();
        $data->file('file')->move('uploads/',$user->picture);

        if($user->save()) {
            return redirect('login')->with('success', 'Congratulations your account is ready!');
        }
        return redirect('register');
    }
    public function login() {
        return view('login');
    }
    public function logout() {
        session()->forget('id');
        session()->forget('type');
        return redirect('/login');
    }
    public function loginUser(Request $data){
        $user=User::where('email',$data->input('email'))->where('password',$data->input('password'))->first();
        if($user){
            session()->put('id',$user->id);
            session()->put('type',$user->type);
            if($user->type=='Customer'){
                return redirect('/');
            }
        } else {
            return redirect('login')->with('error', 'Email/Password is incorrect!');
        }
    }

    public function addToCart(Request $data){
        if(session()->has('id')) {
            $item=new Cart();
            $item->quantity=$data->input('quantity');
            $item->productid=$data->input('id');
            $item->customerid=session()->get('id');
            $item->save();
                
            return redirect()->back()->with('success', 'Congratulations! item added into cart.');
        } else {
            return redirect('login')->with('error', 'Info! Please log in.');
        }
        
    }

    public function updateCart(Request $data){
        if(session()->has('id')) {
            $item=Cart::find($data->input('id'));
            $item->quantity=$data->input('quantity');
            $item->save();
                
            return redirect()->back()->with('success', 'Success! Item quantity updated.');
        } else {
            return redirect('login')->with('error', 'Info! Please log in.');
        }
        
    }
}
