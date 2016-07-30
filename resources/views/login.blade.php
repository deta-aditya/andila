@extends('layouts.app')

@section('title', 'Login')

@section('page')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Andila</b></a>
        <!-- <p>Aplikasi Integrasi Distribusi LPG Pertamina</p> -->
    </div>

    <div class="alert-placeholder">
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4><span class="glyphicon glyphicon-exclamation-sign"></span> Terjadi Kesalahan!</h4>
            <p>Sistem mendeteksi kesalahan saat memproses permintaan Anda.</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif 
    </div>

    @include('partials.login', ['message' => true])

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/app/login.js') }}"></script>
@endsection