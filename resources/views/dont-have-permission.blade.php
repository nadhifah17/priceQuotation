@extends('adminLte.auth.layout')

@section('pageTitle')
    {{ __('view.unauthorized') }}
@endsection

@section('content')
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="text-center">
            <img src="{{ asset('assets/images/forbidden.png') }}" style="width: 150px; height: auto;" alt="">
            <h3 class="mb-5 mt-5">We are sorry ...</h3>
            <p class="m-0">The page you're trying to access has restricted access.</p>
            <p class="m-0">Please refer to your system administrator</p>

            <a class="btn btn-sm bg-primary-blue mt-5" href="{{ route('login') }}">Go Back</a>
        </div>
    </div>
@endsection