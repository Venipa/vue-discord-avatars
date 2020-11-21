@extends('layouts.app')

@section('body')

    <div id="app" class="py-5">
    <app-avatar-fetch initial-id="{{ $userId }}"></app-avatar-fetch>
        <div class="footer text-muted my-2 d-flex flex-row justify-content-center align-items-center">
            <span class="mr-1">2019 &copy;</span><a href="https://venipa.net">venipa.net</a>
        </div>
    </div>

@endsection
