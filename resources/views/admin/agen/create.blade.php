@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
@endsection

@section('title', 'Tambah Agen')
@section('head', 'Tambah Agen')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ url('admin/agen') }}">Agen</a></li>
<li class="active">Tambah</li>
@endsection

@section('content')
<form id="form-agen-create" method="post" action="{{ url('api/agen') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Agen</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="id_reg" class="col-sm-3 control-label">ID Registrasi*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="id_reg" required>
						</div>
					</div>
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Nama Agen*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" required>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">Alamat E-mail*</label>
						<div class="col-sm-9">
							<input type="email" class="form-control" name="email" required>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Nomor Telepon*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="phone" required>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Perusahaan</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">NPWP*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="npwp" required>
						</div>
					</div>
					<div class="form-group">
						<label for="id_reg" class="col-sm-3 control-label">Sales Executive*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="sales_exec" required>
						</div>
					</div>
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Sales Group*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="sales_group" required>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data Distribusi</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="station_id" class="col-sm-3 control-label">Stasiun Pengisian*</label>
						<div class="col-sm-9">
							<select name="station_id" class="form-control select2">
								<option value="null" data-id="null" @unless(isset($station)) selected @endunless>Pilih Stasiun...</option>
								@foreach ($db['stations'] as $s)
								<option value="{{ $s->id }}" @if(isset($station) && $station == $s->id) selected @endif>{{ $s->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Kuota Maksimal LPG/Bulan*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="quota_monthly" required>
							<p>Kuota/bulan akan digunakan sebagai jumlah alokasi LPG maksimal pada agen.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Aktif*</label>
						<div class="col-sm-9">
							<label class="radio-inline">
								<input type="radio" name="active" value="1" checked> Ya
							</label>
							<label class="radio-inline">
								<input type="radio" name="active" value="0"> Tidak
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data Keanggotaan</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">Nama Pengguna*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="user_username" required>
							<span class="help-block"><i>Nama Pengguna</i> harus memiliki panjang maksimal 240 karakter.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Kata Sandi*</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" name="user_password" required>							
							<span class="help-block">Tidak direkomendasikan menuliskan tanggal lahir atau hal lainnya yang mudah ditebak oleh pengguna lain.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Alamat</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="address_province" class="col-sm-3 control-label">Provinsi*</label>
						<div class="col-sm-9">
							<select name="address_province" class="form-control" data-href="{{ url('api/region') }}" required>
								<option value="null" data-id="null" selected>Pilih Provinsi...</option>
								@foreach ($db['provinces'] as $p)
								<option value="{{ ucwords($p->name) }}" data-id="{{ $p->id }}">{{ $p->name }}</option>
								@endforeach
							</select>
							<p class="help-block">Silahkan isi data alamat dengan berurutan dari Provinsi hingga Kelurahan/Desa.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_region" class="col-sm-3 control-label">Kabupaten/Kota</label>
						<div class="col-sm-9">
							<select name="address_region" class="form-control" data-href="{{ url('api/district') }}" disabled>
								<option value="null" data-id="null" selected>Pilih Kabupaten/Kota...</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="address_district" class="col-sm-3 control-label">Kecamatan</label>
						<div class="col-sm-9">
							<select name="address_district" class="form-control" data-href="{{ url('api/subdistrict') }}" disabled>
								<option value="null" data-id="null" selected>Pilih Kecamatan...</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="address_subdistrict" class="col-sm-3 control-label">Kelurahan/Desa</label>
						<div class="col-sm-9">
							<select name="address_subdistrict" class="form-control" disabled>
								<option value="null" selected>Pilih Kelurahan/Desa...</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="address_street" class="col-sm-3 control-label">Alamat Lengkap</label>
						<div class="col-sm-9">
							<textarea class="form-control" name="address_street"></textarea>
							<p class="help-block">Tuliskan nama jalan, nama gang, nomor pada jalan, dan RT/RW bila perlu.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_postal_code" class="col-sm-3 control-label">Kode Pos</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="address_postal_code">
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary gmap-box">
				<div class="box-header with-border">
					<h3 class="box-title">Lokasi Pada Peta <small class="gmap-query"></small></h3>
				</div>
				<div class="box-body">
					<p class="help-block">Klik pada peta untuk menentukan lokasi.</p>

					<div id="gmap-choose-location" class="google-map"></div>

					<div class="form-group">
						<div class="col-xs-12">
							<input type="text" class="form-control" name="location" placeholder="Gunakan peta di atas untuk menentukan lokasi" required readonly>
							<p class="help-block">Klik ulang pada peta untuk mengubah lokasi.</p>
						</div>
					</div>
				</div>
				<div class="overlay">
					<p>Silahkan pilih <b>Provinsi</b> pada <i>form</i> <b>Alamat</b> terlebih dahulu.</p>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-send" style="margin-right:10px"></i> Simpan Data</button>
			<a href="{{ url('admin/agen') }}" class="btn btn-lg btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('vendor/select2/select2.full.min.js') !!}"></script>

<script src="{!! asset('js/app/admin/agen/create.js') !!}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo&callback=initMap" async defer></script>
@endsection
