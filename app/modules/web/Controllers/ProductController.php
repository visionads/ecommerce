<?php

namespace App\Modules\Web\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

use App\ProductGroup;
use DB;


class ProductController extends Controller
{

	public function index($product_slug){

		$product = DB::table('product')->where('slug',$product_slug)->where('status','active')->first();
		$related_product_r = DB::table('product')->where('product_subgroup_id',$product->product_subgroup_id)->whereNotIn('id',[$product->id])->get();

		if($product->meta_title != ''){
			$title =$product->meta_title ." | ";
		}else{
			$title =$product->title ." | ";
		}
		

			return view('web::product.details',[
                'title' => $title,
                'product' => $product,
                'related_product_r' => $related_product_r
                
            ]);

	}
	
}