@extends('layouts.inner')

@section('title', 'Tambah Agen')
@section('head', 'Tambah Agen')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ route('web.agents.index') }}">Agen</a></li>
<li class="active">Tambah</li>
@endsection

@section('content')
<form id="form-agent-create" method="post" action="{{ url('api/agent') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div id="andila-agent-profile" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Agen</h3>
				</div>
				<div class="box-body">
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
					<div class="form-group">
						<label for="id_reg" class="col-sm-3 control-label">Pemilik/CEO*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="owner" required>
						</div>
					</div>
				</div>
			</div>
			<div id="andila-agent-address" class="box box-primary">
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
			<div id="andila-agent-user" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data Keanggotaan</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">E-mail Pengguna*</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-btn">
									<button id="clone-email" class="btn btn-default" type="button" data-toggle="tooltip" data-placement="right" title="Samakan dengan e-mail agen">
										<i class="fa fa-hand-o-left"></i>
									</button>
								</span>
								<input type="text" class="form-control" name="user_email" required>
							</div>
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
		<div class="col-xs-12">
			<button type="submit" class="btn btn-primary"><i class="fa fa-send" style="margin-right:10px"></i> Simpan Data</button>
			<a href="{{ route('web.agents.index') }}" class="btn btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/inner/agents/create.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo" async defer></script>
@endsection