@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Pengguna')
@section('head', 'Lihat Pengguna')

@section('breadcrumbs')
<li class="active">Pengguna</li>
@endsection

@section('content')

<div id="andila-users-data" class="box box-default">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-3">
						<select class="form-control" data-table="length">
							<option value="10">10 Entri</option>
							<option value="25">25 Entri</option>
							<option value="50">50 Entri</option>
							<option value="100">100 Entri</option>
							<option value="250">250 Entri</option>
							<option value="500">500 Entri</option>
							<option value="-1">Semua Entri</option>
						</select>
					</div>
					<div class="col-sm-3">
						<select class="form-control" data-table="search-type">
							<option value="" disabled selected>Tipe Pengguna...</option>
							<option value="">Semua</option>
							<option value="Administrator">Administrator</option>
							<option value="Agen">Agen</option>
							<option value="Subagen">Subagen</option>
						</select>
					</div>
					<div class="col-sm-6">
						<div class="input-group">
							<input class="form-control" type="text" data-table="search" placeholder="Cari...">
							<span class="input-group-addon">
								<span class="fa fa-search"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 text-right">
				<a href="{{ route('web.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Administrator</a>
			</div>
		</div>
	</div>

	<table class="table table-condensed table-striped table-hover table-clickable andila-datatable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Email Pengguna</th>
				<th>Tipe Pengguna</th>
				<th>Terakhir Login</th>
				<th>Dibuat Pada</th>
				<th>Diubah Pada</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<div class="overlay">
		<i class="fa fa-spinner fa-pulse"></i>
	</div>
</div>

<div id="modal-user-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class="user-name">Seorang Pengguna</span> <small class="user-id"></small></h4>
			</div>			
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-user-handling" class="col-sm-4 text-center"><i class="fa fa-eye"></i> Detail</a>
					<a href="#" id="btn-user-edit" class="col-sm-4 text-center"><i class="fa fa-edit"></i> Ubah</a>
					<a href="#" id="btn-user-delete" class="col-sm-4 text-center" data-toggle="popover" data-trigger="focus" title="Hapus Pengguna?"><i class="fa fa-trash"></i> Hapus</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/users/index.js') !!}"></script>
@endsection