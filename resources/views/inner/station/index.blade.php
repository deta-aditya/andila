@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Stasiun')
@section('head', 'Lihat Stasiun')

@section('breadcrumbs')
<li class="active">Stasiun</li>
@endsection

@section('content')

<div id="andila-stations-data" class="box box-default">
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
							<option value="" disabled selected>Tipe Stasiun...</option>
							<option value="">Semua</option>
							<option value="SPPBE">SPPBE</option>
							<option value="SPPEK">SPPEK</option>
							<option value="SPBU">SPBU</option>
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
				<a href="{{ route('web.stations.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Stasiun</a>
			</div>
		</div>
	</div>

	<table class="table table-condensed table-striped table-hover table-clickable andila-datatable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nama Stasiun</th>
				<th>Telepon</th>
				<th class="hidden-xs hidden-sm hidden-md">Provinsi</th>
				<th>Tipe Stasiun</th>
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

<div id="modal-station-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class="station-name">Sebuah Stasiun</span> <small class="station-id"></small></h4>
				<p class="station-province">Provinsi</p>
			</div>
			<div id="gmap-see-location" class="modal-body google-map"></div>	
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6 simple-statbox">
						<div id="stat-allocations" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Alokasi Bulan Ini</p>
					</div>
					<div class="col-sm-6 simple-statbox">
						<div id="stat-agents" class="statbox-stat">N/A</div>
						<p class="statbox-desc">Agen Bersangkutan</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-station-show" class="col-sm-4 text-center"><i class="fa fa-eye"></i> Detail</a>
					<a href="#" id="btn-station-edit" class="col-sm-4 text-center"><i class="fa fa-edit"></i> Ubah</a>
					<a href="#" id="btn-station-delete" class="col-sm-4 text-center" data-toggle="popover" data-trigger="focus" title="Hapus Stasiun?"><i class="fa fa-trash"></i> Hapus</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/stations/index.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection