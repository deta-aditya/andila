@extends('layouts.inner')

{{-- @section('styles')

@endsection --}}

@section('title', 'Tambah Pengguna')
@section('head', 'Tambah Pengguna')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ url('admin/user') }}">Pengguna</a></li>
<li class="active">Tambah</li>
@endsection

@section('content')
<form id="form-user-create" method="post" action="{{ url('user') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data Keanggotaan</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">Nama Pengguna*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="username" required>
							<span class="help-block"><i>Nama Pengguna</i> harus memiliki panjang maksimal 240 karakter.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Kata Sandi*</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" name="password" required>							
							<span class="help-block">Tidak direkomendasikan menuliskan tanggal lahir atau hal lainnya yang mudah ditebak oleh pengguna lain.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Keanggotaan*</label>
						<div class="col-sm-9">
							<label class="radio-inline">
								<input type="radio" name="role" value="admin"> Administrator
							</label>
							<label class="radio-inline">
								<input type="radio" name="role" value="agen" checked> Agen
							</label>
							<label class="radio-inline">
								<input type="radio" name="role" value="pangkalan"> Pangkalan
							</label>
							<span class="help-block">Tentukan keanggotaan dari pengguna. Lalu pilih lokasi kerja anggota.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Data Pribadi</h3>
				</div>
				<div class="box-body">
					<form id="form-user-create" method="post" action="{{ url('user') }}" class="form-horizontal">
						{!! csrf_field() !!}
						<div class="form-group">
							<label for="first_name" class="col-xs-12 col-sm-3 control-label">Nama Lengkap</label>
							<div class="col-xs-6 col-sm-4">
								<input type="text" class="form-control" name="first_name" placeholder="Nama Awal">
							</div>
							<div class="col-xs-6 col-sm-4">
								<input type="text" class="form-control" name="last_name" placeholder="Nama Akhir">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-send" style="margin-right:10px"></i> Simpan Data</button>
			<a href="{{ url('admin/user') }}" class="btn btn-lg btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/admin/user/create.js') !!}"></script>
@endsection
