@extends('layouts.app')

@section('title', 'Error 404: Tidak Ditemukan')

@section('page')

<div class="container text-center" style="margin-top:50px">
	<h1>Andila</h1>
	<p class="lead text-red" style="font-size:48pt;margin-top:40px;margin-bottom:0px">Error 404</p>
	<p class="lead text-red" style="font-size:36pt;margin-bottom:40px">"Tidak Ditemukan"</p>
	<p class="lead">Halaman yang Anda cari tidak dapat ditemukan</p>
	<p><a class="btn btn-lg btn-danger" href="{{ url()->previous() }}"><i class="fa fa-arrow-left"></i> Kembali</a></p>
</div>

@endsection