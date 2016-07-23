@extends('layouts.inner')

@section('title', 'Pengguna '. $db['user']->first_name . ' ' . $db['user']->last_name)
@section('head', $db['user']->first_name . ' ' . $db['user']->last_name)
@section('description', 'Pengguna')

@section('breadcrumbs')
<li><a href="{{ url('admin/user') }}">Pengguna</a></li>
<li class="active">{{ $id }}</li>
@endsection

@section('content')
<div class="row">

	<div class="col-xs-12 col-md-4 col-lg-3">
		
		<div class="box box-default">
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="{{ asset('img/user1-128x128.jpg') }}" alt="User profile picture">

				<h3 class="profile-username text-center">{{ $db['user']->first_name . ' ' . $db['user']->last_name }}</h3>
				<p class="text-blue text-center">{{ $db['user']->roles[0]->name }}</p>

				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Nama Pengguna</b> <span class="pull-right">{{ Helper::userify($db['user']->email) }}</span>
					</li>
					<li class="list-group-item">
						<b>Lokasi Kerja</b> <span class="pull-right">PT Foo Bar</span>
					</li>
				</ul>

				<ul class="nav nav-justified">
					<li><a href="{{ url('admin/user/'. $id .'/edit' ) }}"><i class="fa fa-edit"></i> Ubah Data</a></li>
					@unless($user->id == $id) 
					<li><a href="#modal-delete"><i class="fa fa-trash"></i> Hapus Data</a></li> 
					@endunless
				</ul>
			</div>
		</div>

	</div>
	<div class="col-xs-12 col-md-8 col-lg-9">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="disabled"><a href="#activity" data-toggle="tab">Aktifitas</a></li>
				<li class="disabled"><a href="#personal" data-toggle="tab">Data Pribadi</a></li>
			</ul>
			<div class="tab-content">

				<div id="loading" class="tab-pane active text-center">
					<p class="lead"><i class="fa fa-refresh fa-spin"></i> Tunggu Sebentar</p>
				</div>

				<div id="activity" class="tab-pane fade">
					Aktifitas
				</div>

				<div id="personal" class="tab-pane fade">
					<div class="row">
						
						<p class="col-sm-3"><b>Nama Lengkap</b></p>
						<p class="col-sm-9">{{ $db['user']->first_name .' '. $db['user']->last_name }}</p>

						<p class="col-sm-3"><b>Alamat Rumah</b></p>
						<p class="col-sm-9">Jl. Foo No. Bar Kota Baz.</p>

						<p class="col-sm-3"><b>Nomor Telepon</b></p>
						<p class="col-sm-9">Foo Bar</p>

						<p class="col-sm-3"><b>Akun Sosial Media</b></p>
						<p class="col-sm-9">
							<a href="#" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></a>
							<a href="#" class="btn btn-social-icon btn-google"><i class="fa fa-google"></i></a>
							<a href="#" class="btn btn-social-icon btn-instagram"><i class="fa fa-instagram"></i></a>
							<a href="#" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></a>
						</p>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection