@extends('layouts.app')

@section('page')

@include('partials.front.navbar')

<div id="section-first">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8">
                <div class="jumbotron">
                    <h1 class="jumbo-title">Selamat Datang di Andila</h1>
                    <p class="jumbo-para">
                        Aplikasi Integrasi Distribusi LPG Pertamina (Andila), sesuai namanya, membantu
                        pemantauan dan integrasi distribusi produk LPG subsidi 3 kilogram Pertamina dari
                        tingkat agen hingga ke tingkat pangkalan.
                    </p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="login-box">
                    <div class="login-box-head">
                        <h3 class="login-box-title">Silahkan Login</h3>
                    </div>
                    @include('partials.login', ['message' => false])
                </div>
            </div>
        </div>
    </div>
</div>

<div id="section-second">
    <div class="container">
        <h1 class="section-title">Fitur Unggulan</h1>
        <div class="row">
            <div class="col-xs-6 col-md-3 feature-item text-red">
                <i class="fa fa-clipboard"></i>
                <p>Rencana penyaluran yang lebih mudah.</p>
            </div>
            <div class="col-xs-6 col-md-3 feature-item text-red">
                <i class="fa fa-map"></i>
                <p>Pemantauan posisi yang lebih nyaman dengan peta digital.</p>
            </div>
            <div class="col-xs-6 col-md-3 feature-item text-red">
                <i class="fa fa-area-chart"></i>
                <p>Pemantauan data yang lebih intuitif dengan fitur <i>chart</i>.</p>
            </div>
            <div class="col-xs-6 col-md-3 feature-item text-red">
                <i class="fa fa-laptop"></i>
                <p>Web yang <i>responsive</i>.</p>
            </div>
        </div>
    </div>
</div>

@include('partials.front.footer')

@endsection

@section('scripts')
<script src="{{ asset('js/app/welcome.js') }}"></script>
@endsection