@extends('layouts.docs')

@section('title', 'Ikhtisar - Panduan Administrator')
@section('breadcrumbs', 'Administrator / Ikhtisar')

@section('content')

<h1 id="top">Ikhtisar</h1>

<ul class="list-unstyled docs-bookmarks">
  <li><a href="#introduction">Pengenalan</a></li>
  <li><a href="#distribution-model">Alur Distribusi</a></li>
  <li><a href="#using-panels">Cara Menggunakan Panel</a></li>
  <li><a href="#using-docs">Cara Menggunakan Panduan</a></li>
  <li><a href="#important-notes">Catatan Penting</a></li>
</ul>

<hr>

<h2>Pengenalan <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Selamat datang di halaman panduan Andila!</p>

<p>Andila adalah aplikasi digital yang bertujuan untuk membantu pemantauan dan integrasi distribusi produk LPG subsidi 3 kilogram Pertamina. </p>

<p>Andila sangat mudah untuk digunakan, namun pengguna juga perlu memperhatikan panduan penggunaan agar Andila dapat berjalan dengan 
sebaiknya dan kesalahan penggunaan dapat diminimalisir.</p>

<p>Pada Andila terdapat tiga tingkatan pengguna, yaitu:</p>

<ul>
  <li>
    <b>Administrator</b>
    <p>Pengguna tertinggi dari sistem Andila yang memegang kuasa penuh dalam mengatur keanggotaan dan penjadwalan.</p>
  </li>
  <li>
    <b>Agen</b>
    <p>Pengguna yang mengatur suatu agen distribusi LPG.</p>
  </li>
  <li>
    <b>Subagen (Pangkalan)</b>
    <p>Pengguna yang mengatur suatu subagen distribusi LPG.</p>
  </li>
</ul>

<p>Anda sebagai Administrator memiliki kewajiban untuk mengelola kepenggunaan, data stasiun, data agen, data subagen, penjadwalan 
(<i>schedule agreement</i>) antara stasiun dan agen.</p>

<hr>

<h2 id="distribution-model">Alur Distribusi <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Andila memiliki alur atau model pendistribusian sebagai berikut:</p>

<img src="{{ asset('img/docs/Alur.jpg') }}" class="img-responsive img-rounded" alt="Alur Distribusi">

<p>Proses-proses yang berwarna merah merupakan proses yang dilakukan oleh administrator. Seandainya gambar di atas tidak muncul, berikut penjelasan 
secara tekstual:</p>

<ol>
  <li>Administrator mengirimkan jadwal pengambilan LPG (<i>schedule agreement</i>) kepada agen.</li>
  <li>Agen memesan LPG kepada stasiun sesuai dengan jadwal yang telah diberikan.</li>
  <li>Administrator mengkonfirmasi pesanan agen. </li>
  <li>Agen menyalurkan LPG kepada subagen sesuai dengan jadwal yang telah diberikan.</li>
  <li>Subagen melaporkan penjualan LPG kepada agen.</li>
</ol>

<p>Perlu diketahui bahwa data penjadwalan <b>tidak dapat dimodifikasi</b>. Karena itu berhati-hatilah saat mengoperasikan sistem penjadwalan pada Andila.</p>

<hr>

<h2 id="using-panels">Cara Menggunakan Panel <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Halaman panel adalah halaman yang pertama Anda lihat setelah melakukan proses <i>login</i>. Untuk panduan melaksanakan <i>login</i> silahkan menuju ke <a href="{{ route('web.docs.read', ['admin', 'login']) }}">halaman panduan login</a>.</p>

<img src="{{ asset('img/docs/Panel.jpg') }}" class="img-responsive img-rounded" alt="Halaman Panel">

<p>Anda dapat mengakses panduan ini kapanpun Anda mau dengan meng-klik tombol yang memiliki ikon tanda tanya yang terletak pada sisi kanan atas halaman.</p>

<p>Untuk berpindah halaman, klik pada <i>link</i> yang terletak pada sisi kiri halaman. <i>Link-link</i> tersebut merepresentasikan kategori perintah yang tersedia
pada Andila. Sehingga jika Anda ingin menambahkan data subagen, Anda dapat memilih link yang bertuliskan "Subagen" dan klik tombol yang bertuliskan "Tambah".</p>

<p>Untuk keluar atau <i>logout</i>, klik pada tulisan e-mail Anda pada sisi kanan atas halaman panel, lalu klik tombol bertuliskan "logout".</p>

<hr>

<h2 id="using-docs">Cara Menggunakan Panduan <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Panduan Andila dirancang sedemikian rupa agar mudah untuk digunakan oleh pengguna Andila.</p>

<img src="{{ asset('img/docs/Guide.jpg') }}" class="img-responsive img-rounded" alt="Halaman Panduan">

<p>Untuk menavigasi panduan atau berpindah halaman, Anda dapat memilih <i>link</i> yang berada pada bagian kiri halaman. <i>Link-link</i> tersebut merepresentasikan
kategori panduan yang tersedia. Setiap kategori merepresentasikan halaman atau proses pada panel Anda. Sehingga, bila Anda ingin mengetahui bagaimana 
cara mengoperasikan halaman laporan Anda dapat memilih <i>link</i> yang bertuliskan "laporan" untuk membuka panduan penggunaannya.</p>

<p>Saat Anda pertama membuka halaman panduan, Anda akan melihat sejumlah <i>link</i> subkategori yang dapat Anda klik untuk menavigasi ke bagian subkategori tersebut.
Untuk kembali melihat subkategori yang tersedia Anda dapat meng-klik ikon panah yang mengarah ke atas berwarna merah.</p>

<p>Untuk kembali ke halaman panel, klik pada tombol "Kembali" yang terletak pada sisi kanan atas halaman ini.</p>

<hr>

<h2 id="important-notes">Catatan Penting <a href="#top" class="pull-right"><i class="fa fa-angle-up"></i></a></h2>

<p>Perlu diingat bahwa pengguna dilarang menyalahgunakan sistem Andila. Penyalahgunaan itu antara lain mengubah DOM (<i>Document Object Model</i>) dengan sembarangan, mengutak-atik API agar mampu melaksanakan aktifitas yang tidak diperbolehkan dan sebagainya. Bagi mereka yang telah menyalahgunakannya akan diberi e-mail peringatan dari Administrator dan terancam dinonaktifkan dari sistem Andila!</p>

<p>Untuk melihat bagaimana cara menangani pesan error silahkan kunjungi <a href="{{ route('web.docs.read', ['admin', 'errors']) }}">halaman panduan pesan error.</a></p>

@endsection
