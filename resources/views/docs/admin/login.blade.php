@extends('layouts.docs')

@section('title', 'Login - Panduan Administrator')
@section('breadcrumbs', 'Administrator / Login')

@section('content')

<h1 id="top">Login / Autentikasi</h1>

<ul class="list-unstyled docs-bookmarks">
  <li><a href="#login">Login</a></li>
  <li><a href="#logout">Logout</a></li>
  <li><a href="#forgot-password">Lupa Kata Sandi</a></li>
</ul>

<hr>

<h2 id="login">Login <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Untuk dapat mengakses sistem utama pada halaman panel Andila, pengguna harus melaksanakan <i>login</i> terlebih dahulu.</p>

<img src="{{ asset('img/docs/Login.jpg') }}" class="img-responsive img-rounded" alt="Cara Melaksanakan Login">

<p>Tuliskan e-mail dan kata sandi pada <i>form login</i>, lalu tekan tombol bertuliskan "Login" untuk melaksanakan <i>login</i>. Tunggu hingga tombol berubah menjadi hijau dengan tulisan "Sukses", maka halaman akan dengan otomatis berpindah ke halaman panel Andila.</p>

<div class="alert alert-info">
  <h4>Saya mendapatkan pesan <i>error</i>!</h4>
  <p>Untuk melihat penjelasan mengenai pesan <i>error</i> silahkan kunjungi <a href="{{ route('web.docs.read', ['admin', 'errors']) }}">halaman panduan pesan error</a>.</p>
</div>

<hr>

<h2 id="logout">Logout <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Pengguna yang sudah <i>login</i> diperbolehkan untuk keluar / <i>logout</i>.</p>

<img src="{{ asset('img/docs/Logout.jpg') }}" class="img-responsive img-rounded" alt="Cara Melaksanakan Logout">

<p>Untuk melaksanakan <i>logout</i> klik nama e-mail Anda di sisi kanan atas halaman, maka kotak profil akan muncul. Pada kotak profil tersebut terdapat sebuah tombol bertuliskan "Logout". Klik pada tombol tersebut dan tunggu hingga proses selesai. Anda akan dipindahkan ke halaman depan jika proses <i>logout</i> telah berhasil dilaksanakan.</p>

<hr>

<h2 id="forgot-password">Lupa Kata Sandi<a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Jika Anda lupa kata sandi silahkan kontak administrator Andila pada e-mail di bawah ini:</p>

<pre>admin@andila.dist</pre>

<p>Pihak administrator akan mengatur kembali kata sandi akun Anda dan Anda akan dapat melaksanakan <i>login</i> kembali.</p>

@endsection
