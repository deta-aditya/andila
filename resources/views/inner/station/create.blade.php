@extends('layouts.inner')

@section('title', 'Tambah Stasiun')
@section('head', 'Tambah Stasiun')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ route('web.stations.index') }}">Stasiun</a></li>
<li class="active">Tambah</li>
@endsection

@section('content')
<form id="form-station-create" method="post" action="{{ url('api/station') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div id="andila-station-profile" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Stasiun</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Nama Stasiun*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" required>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Nomor Telepon*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="phone" required>
						</div>
					</div>
					<div class="form-group">
						<label for="type" class="col-sm-3 control-label">Tipe Stasiun*</label>
						<div class="col-sm-9">
							<select name="type" class="form-control" required>
								<option value="SPPBE">SPPBE</option>
								<option value="SPPEK">SPPEK</option>
								<option value="SPBU">SPBU</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div id="andila-station-address" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Alamat</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="address_province" class="col-sm-3 control-label">Provinsi*</label>
						<div class="col-sm-9">
							<select name="address_province" class="form-control" required>
								<option value="" data-id="null" disabled selected>Pilih Provinsi...</option>
							</select>
							<p class="help-block">Silahkan isi data alamat dengan berurutan dari Provinsi hingga Kelurahan/Desa.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_regency" class="col-sm-3 control-label">Kabupaten/Kota</label>
						<div class="col-sm-9">
							<select name="address_regency" class="form-control" disabled>
								<option value="" data-id="null" disabled selected>Pilih Kabupaten/Kota...</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="address_district" class="col-sm-3 control-label">Kecamatan</label>
						<div class="col-sm-9">
							<select name="address_district" class="form-control" disabled>
								<option value="" data-id="null" disabled selected>Pilih Kecamatan...</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="address_subdistrict" class="col-sm-3 control-label">Kelurahan/Desa</label>
						<div class="col-sm-9">
							<select name="address_subdistrict" class="form-control" disabled>
								<option value="" disabled selected>Pilih Kelurahan/Desa...</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="address_detail" class="col-sm-3 control-label">Alamat Lengkap</label>
						<div class="col-sm-9">
							<textarea class="form-control" name="address_detail"></textarea>
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
				<div class="overlay">
					<i class="fa fa-spinner fa-pulse"></i>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div id="andila-gmap-box" class="box box-primary gmap-box">
				<div class="box-header with-border">
					<h3 class="box-title">Lokasi Pada Peta <small class="gmap-query"></small></h3>
				</div>
				<div class="box-body">
					<p class="help-block">Klik pada peta untuk menentukan lokasi.</p>

					<div id="gmap-choose-location" class="google-map"></div>

					<div class="form-group">
						<label for="location" class="col-sm-3 control-label">Lokasi</label>
						<div class="col-xs-9">
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
			<button type="submit" class="btn btn-primary"><i class="fa fa-send" style="margin-right:10px"></i> Simpan Data</button>
			<a href="{{ route('web.stations.index') }}" class="btn btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/inner/stations/create.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo" async defer></script>
@endsection