<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $data['product_variants'] = ProductVariant::all();
        $data['product_variant_prices'] = ProductVariantPrice::all();
//        $data['variants_group'] = ProductVariant::groupBy('variant')->get();
        $data['variants_group']=  ProductVariant::orderBy('id')->get()->groupBy(function($data) {
            return $data->variant;
        });

        if ($request['title'] !== null && $request['variant'] && $request['price_from'] && $request['price_to'] && $request['date']) {
            $data['products'] = Product::query()
                ->where('title', 'LIKE', "%{$request['title']}%")
                ->whereIn('id',function ($query) use ($request) {
                    $query->select('product_id')
                        ->from('product_variants')
                        ->where('variant', $request['variant']);
                })
                ->whereIn('id',function ($query) use ($request) {
                    $query->select('product_id')
                        ->from('product_variant_prices')
                        ->whereBetween('price', [$request['price_from'], $request['price_to']]);
                })
                ->whereDate('created_at', 'LIKE', "%{$request['date']}%")
                ->paginate(5);
        } elseif ($request['title'] && $request['date']) {
            $data['products'] = Product::query()
                ->where('title', 'LIKE', "%{$request['title']}%")
                ->whereDate('created_at', $request['date'])
                ->paginate(5);
        } elseif ($request['title']){
            $data['products'] = Product::query()
                ->where('title', 'LIKE', "%{$request['title']}%")
                ->paginate(5);
        } else {
            $data['products'] = Product::paginate(5);
        }

        return view('products.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
