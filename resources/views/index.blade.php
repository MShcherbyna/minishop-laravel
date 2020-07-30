@extends('layouts.app')

@section('header-content')
<div class="hero container">
    <div class="hero-copy">
        <h1>CSS Grid Example</h1>
        <p>A practical example of using CSS Grid for a typical website layout.</p>
        <div class="hero-buttons">
            <a href="#" class="button button-white">Button 1</a>
            <a href="#" class="button button-white">Button 2</a>
        </div>
    </div> <!-- end hero-copy -->

    <div class="hero-image">
        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/cssgrid_macbook-pro-laravel.png" alt="hero image">
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <h1 class="text-center">Buy the best product here</h1>

    <div class="text-center button-container">
        <p class="section-description text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aliquid earum fugiat debitis nam, illum vero,
            maiores odio exercitationem quaerat. Impedit iure fugit veritatis cumque quo provident doloremque est itaque.
        </p>
    </div>

    <div class="text-center">
        <div class="product-slider">
            @foreach ($products as $product)
                <div class="product">
                    @php
                        $image = strpos($product->photo, 'htt') === false
                            ? '/uploads/' . $product->photo
                            : $product->photo;
                    @endphp
                    <a href="#" ><img class="product-name" src="{{ $image }}" alt="product" width="250px"></a>
                    <a href="#">
                        <div class="product-name">{{ $product->name }}</div>
                    </a>
                    <div class="product-price">{{ $product->presentPrice() }}</div>
                    <form action="{{ route('cart.store') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <button type="submit" class="btn btn-outline-secondary btn-sm">Add to cart</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div> <!-- end products -->
</div>
@endsection
@push('scripts')
    <script>
        jQuery(document).ready(function($){
            $('.product-slider').slick({
                infinite: true,
                rows: 2,
                slidesToShow: 3,
                slidesToScroll: 3
            });
        });
    </script>
@endpush
