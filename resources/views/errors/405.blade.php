@extends('layouts.app')

@section('title', 'Error 405: Metode Tidak Diizinkan')

@section('page')

<div class="container text-center" style="margin-top:50px">
	<h1>Andila</h1>
	<p class="lead text-red" style="font-size:48pt;margin-top:40px;margin-bottom:0px">Error 405</p>
	<p class="lead text-red" style="font-size:36pt;margin-bottom:40px">"Metode Tidak Diizinkan"</p>
	<p class="lead">Metode yang Anda kirimkan tidak tersedia atau tidak diizinkan di <i>server</i> kami</p>
	<p><a class="btn btn-lg btn-danger" href="{{ url()->previous() }}"><i class="fa fa-arrow-left"></i> Kembali</a></p>
</div>

@endsection