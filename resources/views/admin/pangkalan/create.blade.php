@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
@endsection

@section('title', 'Tambah Pangkalan')
@section('head', 'Tambah Pangkalan')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ url('admin/pangkalan') }}">Pangkalan</a></li>
<li class="active">Tambah</li>
@endsection

@section('content')
<form id="form-pangkalan-create" method="post" action="{{ url('api/pangkalan') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Pangkalan</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="id_reg" class="col-sm-3 control-label">ID Registrasi*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="id_reg" required>
						</div>
					</div>
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Nama Pangkalan*</label>
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
					<div class="form-group">
						<label for="owner" class="col-sm-3 control-label">Pemilik*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="owner" required>
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
						<label for="agen_id" class="col-sm-3 control-label">Agen Penyalur*</label>
						<div class="col-sm-9">
							<select name="agen_id" class="form-control select2">
								<option value="null" data-id="null" @unless(isset($agen)) selected @endunless disabled>Pilih Agen...</option>
								@foreach ($db['agens'] as $a)
								<option value="{{ $a->id }}" @if(isset($agen) && $agen == $a->id) selected @endif>{{ $a->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Kuota Maksimal LPG/Hari*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="quota_daily" required>
							<p>Kuota/hari akan digunakan sebagai jumlah alokasi LPG maksimal pada pangkalan. <b>Nilai harus kurang dari atau sama dengan 200</b>.</p>
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
			<a href="{{ url('admin/pangkalan') }}" class="btn btn-lg btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('vendor/select2/select2.full.min.js') !!}"></script>

<script src="{!! asset('js/app/admin/pangkalan/create.js') !!}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo&callback=initMap" async defer></script>
@endsection
