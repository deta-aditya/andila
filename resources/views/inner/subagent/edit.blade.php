@extends('layouts.inner')

@section('title', 'Ubah Subagen')
@section('head', 'Ubah Subagen')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ route('web.subagents.index') }}">Subagen</a></li>
<li><a href="{{ url('#') }}">{{ $subagent->id }}</a></li>
<li class="active">Ubah</li>
@endsection

@section('content')
<form id="form-subagent-edit" method="post" action="{{ url('api/subagent') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<input type="hidden" name="id" value="{{ $subagent->id }}">
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div id="andila-subagent-profile" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Subagen</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">Nama Subagen</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" placeholder="{{ $subagent->name }}">
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-3 control-label">Alamat E-mail</label>
						<div class="col-sm-9">
							<input type="email" class="form-control" name="email" placeholder="{{ $subagent->email }}">
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Nomor Telepon</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="phone" placeholder="{{ $subagent->phone }}">
						</div>
					</div>
					<div class="form-group">
						<label for="id_reg" class="col-sm-3 control-label">Pemilik</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="owner" placeholder="{{ $subagent->owner }}">
						</div>
					</div>
					<div class="form-group">
						<label for="agent_id" class="col-sm-3 control-label">Agen Penyalur</label>
						<div class="col-sm-9">
							<select name="agent_id" class="form-control" disabled>
								<option value="" data-id="null" disabled>Pilih Agen...</option>
								@foreach ($agents as $a)
								<option value="{{ $a->id }}" @if ($a->id === $subagent->agent_id) selected @endif>{{ $a->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-sm-3 control-label">Kuota Maksimal LPG/Hari</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" name="contract_value" min="50" max="200" placeholder="{{ $subagent->contract_value }}">
							<p>Kuota/hari akan digunakan sebagai jumlah alokasi LPG maksimal pada pangkalan. <b>Nilai harus diantara 50 dan 200</b>.</p>
						</div>
					</div>
				</div>
			</div>
			<div id="andila-subagent-address" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Alamat</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="address_province" class="col-sm-3 control-label">Provinsi</label>
						<div class="col-sm-9">
							<select name="address_province" class="form-control">
								<option value="" data-id="null" disabled selected>Pilih Provinsi...</option>
							</select>
							<p class="help-block">Data Sebelumnya: {{ $subagent->address->province }}</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_regency" class="col-sm-3 control-label">Kabupaten/Kota</label>
						<div class="col-sm-9">
							<select name="address_regency" class="form-control" disabled>
								<option value="" data-id="null" disabled selected>Pilih Kabupaten/Kota...</option>
							</select>
							<p class="help-block">Data Sebelumnya: {{ $subagent->address->regency }}</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_district" class="col-sm-3 control-label">Kecamatan</label>
						<div class="col-sm-9">
							<select name="address_district" class="form-control" disabled>
								<option value="" data-id="null" disabled selected>Pilih Kecamatan...</option>
							</select>
							<p class="help-block">Data Sebelumnya: {{ $subagent->address->district }}</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_subdistrict" class="col-sm-3 control-label">Kelurahan/Desa</label>
						<div class="col-sm-9">
							<select name="address_subdistrict" class="form-control" disabled>
								<option value="" disabled selected>Pilih Kelurahan/Desa...</option>
							</select>
							<p class="help-block">Data Sebelumnya: {{ $subagent->address->subdistrict }}</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_detail" class="col-sm-3 control-label">Alamat Lengkap</label>
						<div class="col-sm-9">
							<textarea class="form-control" name="address_detail" placeholder="{{ $subagent->address->detail }}"></textarea>
							<p class="help-block">Tuliskan nama jalan, nama gang, nomor pada jalan, dan RT/RW bila perlu.</p>
						</div>
					</div>
					<div class="form-group">
						<label for="address_postal_code" class="col-sm-3 control-label">Kode Pos</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="address_postal_code" placeholder="{{ $subagent->address->postal_code }}">
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
							<input type="text" class="form-control" name="location" placeholder="{{ implode(',', $subagent->location) }}" readonly>
							<p class="help-block">Klik ulang pada peta untuk mengubah lokasi.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<button type="submit" class="btn btn-primary"><i class="fa fa-edit" style="margin-right:10px"></i> Ubah Data</button>
			<a href="{{ route('web.subagents.index') }}" class="btn btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/inner/subagents/edit.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection