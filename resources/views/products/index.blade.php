@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="search" name="title" placeholder="Product Title" class="form-control" >
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control" >
                        <optgroup label="Color">
                            @php $c= 0; @endphp
                        @foreach ($variants_group as $key => $variants)
                            <option value="{{ $key }}}">{{ $key }}</option>
                            @if($c == 2)
                                    </optgroup>
                                    <optgroup label="Size">
                            @endif
                            @php $c++; @endphp

                        @endforeach
                            </optgroup>
{{--                        @foreach($variants_group as $variants)--}}
{{--                            @dd($variants->name)--}}
{{--                            <option value="{{ $variants }}">{{ $variants }}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php $serial = 1; @endphp
                    @foreach($products as $product)
                    <tr>
                        <td>@php
                                echo $serial;
                                $serial++;
                             @endphp
                        </td>
                        <td>{{ $product['title'] }} <br> Created at : {{ date('Y-m-d', strtotime($product['created_at'])) }}</td>

                        <td>{{ $product['description'] }}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                <dt class="col-sm-3 pb-0">
                                    @foreach($product_variants as $product_variant)
                                        @if($product->id == $product_variant->product_id )
                                            {{ $product_variant['variant'] }}
                                            @foreach($product_variant_prices as $product_variant_price)
                                                @if($product->id == $product_variant_price->product_id)

                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
{{--                                    SM/ Red/ V-Nick--}}
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        @foreach($product_variant_prices as $product_variant_price)
                                            @if($product->id == $product_variant_price->product_id)
                                                <dt class="col-sm-4 pb-0">Price : {{ $product_variant_price['price'] }}.00</dt>
                                                <dd class="col-sm-8 pb-0">InStock : {{ $product_variant_price['stock'] }}</dd>
                                            @endif
                                        @endforeach

                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
{{--                    <p>Showing 1 to 10 out of 100</p>--}}
                    <p>Showing {{($products->currentpage()-1)*$products->perpage()+1}} to {{$products->currentpage()*$products->perpage()}} out of  {{$products->total()}}
                    </p>
                </div>
                <div class="col-md-2">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

@endsection
