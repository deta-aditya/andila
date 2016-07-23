@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Agen')
@section('head', 'Lihat Agen')

@section('breadcrumbs')
<li class="active">Agen</li>
@endsection

@section('content')

<div id="andila-agents-data" class="box box-default">
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
				<a href="{{ route('web.agents.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Agen</a>
			</div>
		</div>
	</div>

	<table class="table table-condensed table-striped table-hover table-clickable andila-datatable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nama Agen</th>
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

<div id="modal-agent-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class="agent-name">Sebuah Agen</span> <small class="agent-id"></small></h4>
				<p class="agent-province">Provinsi</p>
			</div>
			<div id="gmap-see-location" class="modal-body google-map"></div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4 simple-statbox">
						<div id="stat-contract-value" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Kuota / Bulan</p>
					</div>
					<div class="col-sm-4 simple-statbox">
						<div id="stat-subagents" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Subagen Bersangkutan</p>
					</div>
					<div class="col-sm-4 simple-statbox">
						<div id="stat-reported" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Terlapor Bln Ini</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-agent-show" class="col-sm-3 text-center"><i class="fa fa-eye"></i> Detail</a>
					<a href="#" id="btn-agent-activate" class="col-sm-3 text-center"><i class="fa fa-link"></i> Aktifasi</a>
					<a href="#" id="btn-agent-deactivate" class="col-sm-3 text-center"><i class="fa fa-unlink"></i> Deaktifasi</a>
					<a href="#" id="btn-agent-edit" class="col-sm-3 text-center"><i class="fa fa-edit"></i> Ubah</a>
					<a href="#" id="btn-agent-delete" class="col-sm-3 text-center" data-toggle="popover" data-trigger="focus" title="Hapus Agen?"><i class="fa fa-trash"></i> Hapus</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/agents/index.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection