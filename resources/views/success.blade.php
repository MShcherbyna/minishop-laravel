@extends('layouts.app')

@section('content')
    <div class="thank-you-section">
        <h1>{{ session()->get('success_message') }}</h1>
        <p>A confirmation email was sent</p>
        <div class="spacer"></div>
        <div>
            <a href="{{ route('home') }}" class="button">Home Page</a>
        </div>
    </div>
@endsection
