@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Subagen')
@section('head', 'Lihat Subagen')

@section('breadcrumbs')
<li class="active">Subagen</li>
@endsection

@section('content')

<div id="andila-subagents-data" class="box box-default">
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
					<div class="col-sm-4">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-default active">
								<input type="radio" name="active" value="" data-table="search-active" autocomplete="off" checked> Semua
							</label>
							<label class="btn btn-default">
								<input type="radio" name="active" value="Ya" data-table="search-active" autocomplete="off"> Aktif
							</label>
							<label class="btn btn-default">
								<input type="radio" name="active" value="Tidak" data-table="search-active" autocomplete="off"> Nonaktif
							</label>
						</div>
					</div>
					<div class="col-sm-5">
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
				<a href="{{ route('web.subagents.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Subagen</a>
			</div>
		</div>
	</div>

	<table class="table table-condensed table-striped table-hover table-clickable andila-datatable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nama Subagen</th>
				<th>Telepon</th>
				<th class="hidden-xs hidden-sm hidden-md">Provinsi</th>
				<th>Aktif</th>
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

<div id="modal-subagent-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class="subagent-name">Sebuah Subagen</span> <small class="subagent-id"></small></h4>
				<p class="subagent-province">Provinsi</p>
			</div>			
			<div id="gmap-see-location" class="modal-body google-map"></div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4 simple-statbox">
						<div id="stat-contract-value" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Kuota / Hari</p>
					</div>
					<div class="col-sm-4 simple-statbox">
						<div id="stat-allocated" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Alokasi Bln Ini</p>
					</div>
					<div class="col-sm-4 simple-statbox">
						<div id="stat-reported" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Terlapor Bln Ini</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
				@if ($user->isAdmin())
					<a href="#" id="btn-subagent-show" class="col-sm-3 text-center"><i class="fa fa-eye"></i> Detail</a>
					<a href="#" id="btn-subagent-activate" class="col-sm-3 text-center"><i class="fa fa-link"></i> Aktifasi</a>
					<a href="#" id="btn-subagent-deactivate" class="col-sm-3 text-center"><i class="fa fa-unlink"></i> Deaktifasi</a>
					<a href="#" id="btn-subagent-edit" class="col-sm-3 text-center"><i class="fa fa-edit"></i> Ubah</a>
					<a href="#" id="btn-subagent-delete" class="col-sm-3 text-center" data-toggle="popover" data-trigger="focus" title="Hapus Subagen?"><i class="fa fa-trash"></i> Hapus</a>
				@elseif ($user->isAgent())
					<a href="#" id="btn-subagent-show" class="col-sm-12 text-center"><i class="fa fa-eye"></i> Detail</a>
				@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/subagents/index.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection