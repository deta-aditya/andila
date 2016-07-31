@extends('layouts.docs')

@section('title', 'Dasbor - Panduan Administrator')
@section('breadcrumbs', 'Administrator / Dasbor')

@section('content')

<h1 id="top">Dasbor</h1>

<ul class="list-unstyled docs-bookmarks">
  <li><a href="#introduction">Pengenalan</a></li>
  <li><a href="#statistics">Statistik</a></li>
  <li><a href="#chart">Grafik Rekap Penyaluran</a></li>
  <li><a href="#quick-nav">Navigasi Cepat</a></li>
  <li><a href="#dist-map">Peta Penyebaran</a></li>
  <li><a href="#orders">Pesanan Menunggu Penerimaan</a></li>
  <li><a href="#subagents">Subagen Menunggu Aktifasi</a></li>
</ul>

<hr>

<h2 id="introduction">Pengenalan <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Halaman dasbor adalah halaman yang pertama Anda kunjungi setelah proses <i>login</i> selesai dilaksanakan. Secara fungsional, dasbor menampilkan statistik-statistik penting dan menyediakan fitur-fitur yang bersifat <i>urgent</i> seperti <a href="#orders">menerima pesanan agen</a> dan <a href="#subagents">mengaktifasi subagen</a>.</p>

<div class="alert alert-info">
  <b>Tips:</b> Anda dapat menyembunyikan atau menampilkan fitur-fitur yang disediakan di dasbor dengan meng-klik tombol "-" atau "+" pada ujung kanan atas fitur tersebut. 
</div>

<hr>

<h2 id="statistics">Statistik <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<img src="{{ asset('img/docs/admin/dashboard/Statistics.jpg') }}" class="img-responsive img-rounded" alt="Statistik">

<p>Bagian ini menampilkan jumlah stasiun, agen, subagen, dan pesanan yang terdaftar pada Andila.</p>

<hr>

<h2 id="chart">Grafik Rekap Penyaluran<a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<img src="{{ asset('img/docs/admin/dashboard/Chart.jpg') }}" class="img-responsive img-rounded" alt="Grafik Rekap Penyaluran">

<p>Fitur ini menampilkan grafik penyaluran pesanan yang dikelompokan per bulan. Data yang ditampilkan merupakan data keseluruhan yang telah direkam pada sistem Andila. Rentang data yang ditampilkan pada grafik ini adalah hingga 12 bulan terkini.</p>

<hr>

<h2 id="quick-nav">Navigasi Cepat<a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<img src="{{ asset('img/docs/admin/dashboard/Quicknav.jpg') }}" class="img-responsive img-rounded" alt="Navigasi Cepat">

<p>Navigasi cepat adalah rangkaian tombol yang merupakan jalan pintas menuju halaman-halaman panel yang tersedia. Klik pada tombol untuk berpindah halaman dengan cepat.</p>

<hr>

<h2 id="dist-map">Peta Penyebaran<a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<img src="{{ asset('img/docs/admin/dashboard/Map.jpg') }}" class="img-responsive img-rounded" alt="Peta Penyebaran">

<p>Pada fitur ini Anda dapat melihat seluruh lokasi distributor, yaitu lokasi stasiun, agen dan subagen pada peta (Google Map).</p>

<p>Pada awalnya fitur ini hanya sebuah kotak putih bertulisan "Klik untuk membuka peta". Klik pada kotak tersebut dan <b>tunggu hingga peta muncul</b>. Setelah itu Anda dapat menggunakan fitur ini seperti peta Google Map pada umumnya. Untuk melihat informasi mengenai distributor, klik pada <i>marker</i> yang bertebaran di peta.</p>

<div class="alert alert-danger">
  <h4>Tunggu hingga peta muncul!</h4>
  <p>Jangan lakukan apapun saat peta masih <i>loading</i> atau Anda akan mendapatkan pesan <i>error</i>.</p>
</div>

<hr>

<h2 id="orders">Pesanan Menunggu Penerimaan<a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Dasbor administrator menyediakan fitur untuk menerima/mengkonfirmasi pesanan dari agen secara cepat, karena fitur ini merupakan salah satu fitur <i>urgent</i>, berkaitan dengan proses yang harus segera diselesaikan. Fitur ini berasal dari <a href="{{ route('web.docs.read', ['admin', 'schedules']) }}">halaman jadwal.</a></p>

<img src="{{ asset('img/docs/admin/dashboard/Orders1.jpg') }}" class="img-responsive img-rounded" alt="Navigasi Cepat">

<p>Tabel di atas hanya akan ditampilkan pesanan yang belum dikonfirmasi oleh administrator. Gunakan <i>field</i> "Cari..." untuk mencari data yang diinginkan. Anda bisa mencari ID pesanan, tanggal terjadwal, dan nama agen yang memesan. Klik pada baris data yang akan dikonfirmasi untuk memunculkan <i>modal</i>.</p>

<img src="{{ asset('img/docs/admin/dashboard/Orders2.jpg') }}" class="img-responsive img-rounded" alt="Navigasi Cepat">

<p>Untuk mengkonfirmasi/menerima pesanan, klik pada tombol "Terima", lalu klik tombol "Terima Pesanan". <b>Tunggu hingga proses selesai</b>, <i>modal</i> akan tertutup dan pesan "Berhasil!" akan muncul di bagian atas halaman.</p>

<hr>

@endsection
