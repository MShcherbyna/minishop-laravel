@extends('layouts.app')

@section('content')
<div class="container">

    @if (session()->has('success_message'))
        <div class="spacer"></div>
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    @endif

    @if(count($errors) > 0)
        <div class="spacer"></div>
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 class="checkout-heading stylish-heading">Checkout</h1>
    <div class="checkout-section">
        <div>
            <form action="{{ route('checkout.store') }}" method="POST" id="payment-form">
                {{ csrf_field() }}
                <h2>Billing Details</h2>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    @if (auth()->user())
                        <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                    @else
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    @endif
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                </div>

                <div class="half-form row">
                    <div class="col-sm-6 form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="province">Province</label>
                        <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}" required>
                    </div>
                </div>
                <div class="half-form row">
                    <div class="col-sm-6 form-group">
                        <label for="postalcode">Postal Code</label>
                        <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ old('postalcode') }}" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <div class="spacer"></div>

                <h2>Payment Details</h2>

                <div class="form-group">
                    <label for="name_on_card">Name on Card</label>
                    <input type="text" class="form-control" id="name_on_card" name="name_on_card" value="">
                </div>

                <label for="card-element">Credit or debit card</label>
                <!-- Stripe Elements Placeholder -->
                <div id="card-element"></div>

                <div id="card-errors" role="alert"></div>

                <div class="spacer"></div>

                <button type="submit" id="complete-order" class="button-primary full-width">Complete Order</button>
            </form>
        </div>



        <div class="checkout-table-container">
            <h2>Your Order</h2>
            <div class="checkout-table">
                @foreach ($items as $item)
                @php
                    $image = strpos($item->associatedModel->photo, 'htt') === false
                        ? '/uploads/' . $item->associatedModel->photo
                        : $item->associatedModel->photo;
                @endphp
                <div class="checkout-table-row">
                    <div class="checkout-table-row-left">
                        <div class='item-image'>
                            <img src="{{ $image }}" alt="item" class="checkout-table-img">
                        </div>
                        <div class="checkout-item-details">
                            <div class="checkout-table-item">{{ $item->name }}</div>
                            <div class="checkout-table-description">{{ Str::words($item->associatedModel->description, 5) }}</div>
                            <div class="checkout-table-price">{{ $item->associatedModel->presentPrice() }}</div>
                        </div>
                    </div> <!-- end checkout-table -->

                    <div class="checkout-table-row-right">
                        <div class="checkout-table-quantity">{{ $item->quantity }}</div>
                    </div>
                </div> <!-- end checkout-table-row -->
                @endforeach
            </div> <!-- end checkout-table -->

            <div class="checkout-totals">
                <div class="checkout-totals-left">
                    Subtotal <br>
                    {{ $taxName }}<br>
                    <span class="checkout-totals-total">Total</span>

                </div>

                <div class="checkout-totals-right">
                    {{ presentPrice($subtotal) }}<br>
                    {{ presentPrice($taxValue) }}<br>
                    <span class="checkout-totals-total">{{ presentPrice($total) }}</span>

                </div>
            </div> <!-- end checkout-totals -->
        </div>

    </div> <!-- end checkout-section -->
</div>
@endsection
@push('scripts')
    <script>
        jQuery(document).ready(function($) {
            const stripe = Stripe('pk_test_MOXYBLHgdkmuDdZgZMMwb7qy00CYcUvIyT');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                hidePostalCode: true
            });

            cardElement.mount('#card-element');

            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('complete-order');

            const form = document.getElementById('payment-form');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                document.getElementById('complete-order').disabled = true;

                var options = {
                    name: document.getElementById('name_on_card').value,
                    address_line1: document.getElementById('address').value,
                    address_city: document.getElementById('city').value,
                    address_state: document.getElementById('province').value,
                    address_zip: document.getElementById('postalcode').value
                }

                stripe.createToken(cardElement, options).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                        // Enable the submit button
                        document.getElementById('complete-order').disabled = false;
                        console.log('Error!', result.error);
                    } else {
                        // Send the token to your server
                        console.log('Success!', result.token);
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                const form = document.getElementById('payment-form');
                const hiddenInput = document.createElement('input');

                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);

                form.appendChild(hiddenInput);
                // Submit the form
                form.submit();
            }
        });
    </script>
@endpush
