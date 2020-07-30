@extends('layouts.app')

@section('content')
<div class="cart-section container">
    <div>
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif
        @if ($itemCount > 0)
            <h2> {{ $itemCount }} item(s) in Shopping Cart, total qty is {{ $itemQuantity }}</h2>

            <div class="cart-table">
                @foreach ($items as $item)
                    @php
                        $image = strpos($item->associatedModel->photo, 'htt') === false
                            ? '/uploads/' . $item->associatedModel->photo
                            : $item->associatedModel->photo;
                    @endphp
                    <div class="cart-table-row">
                        <div class="cart-table-row-left">
                            <div class='item-image'>
                                <img src="{{ $image }}" alt="item" class="cart-table-img">
                            </div>
                            <div class="cart-item-details">
                                <div class="cart-table-item"><a href="#">{{ $item->name }}</a></div>
                                <div class="cart-table-description">{{ Str::words($item->associatedModel->description, 10) }}</div>
                            </div>
                        </div>
                        <div class="cart-table-row-right">
                            <div class="cart-table-actions">
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <button type="submit" class="cart-options red">Remove</button>
                                </form>
                            </div>
                            <div>
                                {{-- <span>{{ $item->quantity }}</span> --}}
                                <select class="quantity" data-id="{{ $item->id }}" data-productQuantity="{{ $item->quantity }}">
                                    @for ($i = 1; $i < $item->associatedModel->qty + 1 ; $i++)
                                        <option {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>{{ $item->associatedModel->presentPrice() }}</div>
                        </div>
                    </div> <!-- end cart-table-row -->
                @endforeach
            </div> <!-- end cart-table -->

            <div class="cart-totals">
                <div class="cart-totals-left">
                    Shipping is free because we’re awesome like that. Also because that’s additional stuff I don’t feel like figuring out :).
                </div>

                <div class="cart-totals-right">
                    <div>
                        Subtotal <br>
                        {{ $taxName }}<br>
                        <span class="cart-totals-total">Total</span>
                    </div>
                    <div class="cart-totals-subtotal">
                        {{ presentPrice($subtotal) }}<br>
                        {{ presentPrice($taxValue) }}<br>
                        <span class="cart-totals-total">{{ presentPrice($total) }}</span>
                    </div>
                </div>
            </div> <!-- end cart-totals -->

            <div class="cart-buttons">
                <a href="{{ route('home') }}" class="button">Continue Shopping</a>
                <a href="{{ route('checkout') }}" class="button-primary">Proceed to Checkout</a>
            </div>
        @else
            <h3>You have no items.</h3>
            <div class="spacer"></div>
            <a href="{{ route('home') }}" class="button">Continue Shopping</a>
            <div class="spacer"></div>
        @endif
    </div>

</div>
@endsection
@push('scripts')
    <script>
        jQuery(document).ready(function($){
            $('.alert-success').delay(2000).fadeOut('slow');

            $('.quantity').on('change', function(){
                var qtyValue =  $(this).val();
                var productId = $(this).attr('data-id');

                axios({
                    method: 'put',
                    url: '/cart/' + productId,
                    data: {
                        qty: qtyValue,
                    }
                }).then(function (response) {
                    console.log(response);
                    window.location.href = '{{ route('cart') }}'
                }).catch(function (error) {
                    console.log(error);
                    window.location.href = '{{ route('cart') }}'
                });

                return false;
            });
        });
    </script>
@endpush
