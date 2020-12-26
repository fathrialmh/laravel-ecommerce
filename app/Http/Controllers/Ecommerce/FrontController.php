<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Province;
use App\Models\Order;

class FrontController extends Controller
{
    public function index()
{
   
    $products = Product::orderBy('created_at', 'DESC')->paginate(10);
    
    return view('ecommerce.index', compact('products'));
}
public function product()
{
    
    $products = Product::orderBy('created_at', 'DESC')->paginate(12);
    return view('ecommerce.product', compact('products'));
}
public function categoryProduct($slug)
{

    $products = Category::where('slug', $slug)->first()->product()->orderBy('created_at', 'DESC')->paginate(12);
    return view('ecommerce.product', compact('products'));
}
public function show($slug)
{
    $product = Product::with(['category'])->where('slug', $slug)->first();
    return view('ecommerce.show', compact('product'));
}
public function verifyCustomerRegistration($token)
{
    $customer = Customer::where('activate_token', $token)->first();
    if ($customer) {
        //JIKA ADA MAKA DATANYA DIUPDATE DENGNA MENGOSONGKAN TOKENNYA DAN STATUSNYA JADI AKTIF
        $customer->update([
            'activate_token' => null,
            'status' => 1
        ]);
        //REDIRECT KE HALAMAN LOGIN DENGAN MENGIRIMKAN FLASH SESSION SUCCESS
        return redirect(route('customer.login'))->with(['success' => 'Verifikasi Berhasil, Silahkan Login']);
    }
    //JIKA TIDAK ADA, MAKA REDIRECT KE HALAMAN LOGIN
    //DENGAN MENGIRIMKAN FLASH SESSION ERROR
    return redirect(route('customer.login'))->with(['error' => 'Invalid Verifikasi Token']);
}

public function customerSettingForm()
{
    $customer = auth()->guard('customer')->user()->load('district');

    $provinces = Province::orderBy('name', 'ASC')->get();
    return view('ecommerce.setting', compact('customer', 'provinces'));
}

public function customerUpdateForm(Request $request)
{
    $this->validate($request, [
        'name' => 'required|string|max:100',
        'phone_number' => 'required|max:15',
        'address' => 'required|string',
        'district_id' => 'required|exists:districts,id',
        'password' => 'nullable|string|min:6'
    ]);

    $user = auth()->guard('customer')->user();

    $data = $request->only('name', 'phone_number', 'address', 'district_id');

    if ($request->password != '') {
        //MAKA TAMBAHKAN KE DALAM ARRAY
        $data['password'] = $request->password;
    }
    //TERUS UPDATE DATANYA
    $user->update($data);
    //DAN REDIRECT KEMBALI DENGAN MENGIRIMKAN PESAN BERHASIL
    return redirect()->back()->with(['success' => 'Profil berhasil diperbaharui']);
}
public function referalProduct($user, $product)
{
    $code = $user . '-' . $product; //KITA MERGE USERID DAN PRODUCTID
    $product = Product::find($product); //FIND PRODUCT BERDASARKAN PRODUCTID
    $cookie = cookie('dw-afiliasi', json_encode($code), 2880); //BUAT COOKIE DENGAN NAMA DW-AFILIASI DAN VALUENYA ADALAH CODE YANG SUDAH DI-MERGE
    //KEMUDIAN REDIRECT KE HALAMAN SHOW PRODUCT DAN MENGIRIMKAN COOKIE KE BROWSER
    return redirect(route('front.show_product', $product->slug))->cookie($cookie);
}
public function listCommission()
{
    $user = auth()->guard('customer')->user();
    $orders = Order::where('ref', $user->id)->where('status', 4)->paginate(10);
    //LOAD VIEW AFFILIATE.BLADE.PHP DAN PASSING DATA ORDERS
    return view('ecommerce.affiliate', compact('orders'));
}

}
