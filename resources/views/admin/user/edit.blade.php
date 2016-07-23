@extends('layouts.inner')

{{-- @section('styles')

@endsection --}}

@section('title', 'Ubah Pengguna')
@section('head', 'Ubah Pengguna')
@section('description', '* = Wajib diisi')

@section('breadcrumbs')
<li><a href="{{ url('admin/user') }}">Pengguna</a></li>
<li><a href="{{ url('admin/user/'. $id) }}">{{ $id }}</a></li>
<li class="active">Ubah</li>
@endsection

@section('content')
<form id="form-user-edit" method="post" action="{{ url('user/' . $id) }}" class="form-horizontal">
	{!! csrf_field() !!}
	{!! method_field('PUT') !!}
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Data Keanggotaan</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="username" class="col-sm-3 control-label">Nama Pengguna*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="username" value="{{ Helper::userify($db['user']->email) }}" required>
							<span class="help-block"><i>Nama Pengguna</i> harus memiliki panjang maksimal 240 karakter.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Kata Sandi</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" name="password">							
							<span class="help-block">Tidak perlu diisi bila Anda tidak ingin mengubah kata sandi pengguna ini.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Keanggotaan*</label>
						<div class="col-sm-9">
							<label class="radio-inline">
								<input type="radio" name="role" value="admin" 
									@if($db['user']->roles[0]->slug == 'admin')  
										{{ 'checked' }}
									@endif

									@if($user->id == $id)
										{{ 'disabled' }}
									@endif> Administrator
							</label>
							<label class="radio-inline">
								<input type="radio" name="role" value="agen" 
									@if($db['user']->roles[0]->slug == 'agen')  
										{{ 'checked' }}
									@endif

									@if($user->id == $id)
										{{ 'disabled' }}
									@endif> Agen
							</label>
							<label class="radio-inline">
								<input type="radio" name="role" value="pangkalan"
									@if($db['user']->roles[0]->slug == 'pangkalan')  
										{{ 'checked' }}
									@endif

									@if($user->id == $id)
										{{ 'disabled' }}
									@endif> Pangkalan
							</label>
							<span class="help-block">Tentukan keanggotaan dari pengguna. Lalu pilih lokasi kerja anggota.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div class="box box-warning">
				<div class="box-header with-border">
					<h3 class="box-title">Data Pribadi</h3>
				</div>
				<div class="box-body">
					<form id="form-user-create" method="post" action="{{ url('user') }}" class="form-horizontal">
						{!! csrf_field() !!}
						<div class="form-group">
							<label for="first_name" class="col-xs-12 col-sm-3 control-label">Nama Lengkap</label>
							<div class="col-xs-6 col-sm-4">
								<input type="text" class="form-control" name="first_name" placeholder="Nama Awal" value="{{ $db['user']->first_name }}">
							</div>
							<div class="col-xs-6 col-sm-4">
								<input type="text" class="form-control" name="last_name" placeholder="Nama Akhir" value="{{ $db['user']->last_name }}">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-edit" style="margin-right:10px"></i> Simpan Data</button>
			<a href="{{ url('admin/user/'. $id) }}" class="btn btn-lg btn-link">Kembali</a>
		</div>
	</div>
</form>
@endsection

@section('scripts')
<script src="{!! asset('js/app/admin/user/edit.js') !!}"></script>
@endsection
