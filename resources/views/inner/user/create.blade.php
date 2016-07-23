@extends('layouts.inner')

@section('title', 'Tambah Administrator')
@section('head', 'Tambah Administrator')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ route('web.users.index') }}">Pengguna</a></li>
<li class="active">Tambah</li>
@endsection

@section('content')
<form id="form-user-create" method="post" action="{{ url('api/user') }}" class="form-horizontal">
	{!! csrf_field() !!}
	<div class="row">
		<div class="col-xs-12">
			<div id="andila-user-profile" class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Profil Administrator</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">E-mail*</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="email" required>
							<span class="help-block">Pastikan e-mail memiliki format yang benar.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Kata Sandi*</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="password" required>
							<span class="help-block">Tidak direkomendasikan menuliskan tanggal lahir atau hal lainnya yang mudah ditebak oleh pengguna lain.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<button type="submit" class="btn btn-primary"><i class="fa fa-send" style="margin-right:10px"></i> Simpan Data</button>
			<a href="{{ route('web.users.index') }}" class="btn btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/inner/users/create.js') !!}"></script>
@endsection