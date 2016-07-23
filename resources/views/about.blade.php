@extends('layouts.app')

@section('title', 'Tentang')

@section('page')

@include('partials.front.navbar')

<div class="jumbotron jumbotron-about">
	<div class="container">
		<h1>Tentang Andila</h1>

		<p class="lead">
			<b>A</b>plikasi I<b>n</b>tegrasi <b>Di</b>stribusi <b>L</b>PG Pertamin<b>a</b> (<b>Andila</b>) adalah riset aplikasi digital yang dikembangkan oleh Muhammad Deta Aditya, alumni SMK Negeri 1 Cimahi jurusan Rekayasa Perangkat Lunak. Tujuan dari pengembangan aplikasi ini adalah untuk memenangkan beasiswa Universitas Pertamina melalui Lomba Karya Kreatif dan Inovatif Universitas Pertamina 2016, di mana aplikasi ini dilombakan.
		</p>

		<p class="lead">
			Seluruh hak cipta berada pada saya, Muhammad Deta Aditya, selaku pengembang dari <b>Andila</b>. Namun, sesuai dengan apa yang disebutkan pada ketentuan lomba, hak milik dari <b>Andila</b> adalah berada pada PT Pertamina(Persero) dan Universitas Pertamina.
		</p>
	</div>
</div>

@include('partials.front.footer')

@endsection