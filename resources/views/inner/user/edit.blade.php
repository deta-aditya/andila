@extends('layouts.inner')

@section('title', 'Ubah Pengguna')
@section('head', 'Ubah Pengguna')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ route('web.users.index') }}">Pengguna</a></li>
<li><a href="{{ url('#') }}">{{ $user_edit->id }}</a></li>
<li class="active">Ubah</li>
@endsection

@section('content')
<form id="form-user-edit" method="post" action="{{ url('api/user') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<input type="hidden" name="id" value="{{ $user_edit->id }}">
	<div class="row">
		<div class="col-xs-12">
			<div id="andila-user-profile" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Pengguna</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">E-mail</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="email" placeholder="{{ $user_edit->email }}">
							<span class="help-block">Pastikan e-mail memiliki format yang benar.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Kata Sandi Lama</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="password_old">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Kata Sandi Baru</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="password">
							<span class="help-block">Tidak direkomendasikan menuliskan tanggal lahir atau hal lainnya yang mudah ditebak oleh pengguna lain.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Konfirmasi Kata Sandi</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="password_confirmation">
							<span class="help-block">Ketik ulang kata sandi baru Anda.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<button type="submit" class="btn btn-primary"><i class="fa fa-edit" style="margin-right:10px"></i> Ubah Data</button>
			<a href="{{ route('web.users.index') }}" class="btn btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/inner/users/edit.js') !!}"></script>
@endsection