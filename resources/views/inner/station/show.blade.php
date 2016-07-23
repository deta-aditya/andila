@extends('layouts.inner')

@section('meta')
<meta name="resource-uri" content="{{ $uri }}">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', $station->name)
@section('head', $station->name)
@section('description', 'Stasiun')

@section('breadcrumbs')
<li><a href="{{ route('web.stations.index') }}">Stasiun</a></li>
<li class="active">{{ $station->id }}</li>
@endsection

@section('content')
<div class="row">
	<div class="col-xs-12 col-md-3">

		<!-- Intro Box -->
		<div id="box-station-intro" class="box box-danger">
			<div class="box-body box-profile">
				<img src="{{ asset('img/user1-128x128.jpg') }}" class="profile-user-img img-responsive img-circle" alt="Profile Image">
				<h3 class="profile-username text-center">{{ $station->name }}</h3>
				<p class="text-muted text-center">Stasiun / {{ $station->type }}</p>

				<div class="btn-group btn-group-justified">
					<a href="{{ route('web.stations.edit', $station->id) }}" class="btn btn-default"><i class="fa fa-edit" style="margin-right:5px"></i>Ubah</a>
					<a href="#modal-station-delete" id="btn-station-delete" class="btn btn-default" data-toggle="modal" title="Hapus Stasiun?">
						<i class="fa fa-trash" style="margin-right:5px"></i>Hapus
					</a>
				</div>
			</div>
		</div>
		<!-- /Intro Box -->

		<!-- Navigation Box -->
		<div class="box box-solid">
			<div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li class="active"><a href="#profile" data-toggle="tab">Profil <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
					<li><a href="#agents" data-toggle="tab">Agen <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
					<li><a href="#schedules" data-toggle="tab">Pesanan <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
				</ul>
			</div>
		</div>
		<!-- /Navigation Box -->

	</div>
	<div class="col-xs-12 col-md-9">
		<div class="tab-content">

			<!-- Profile Pane -->
			<div id="profile" class="tab-pane fade in active">

				<!-- Profile Box -->
				<div class="box box-default">
					<div class="box-header">
						<h3 class="box-title">Profil Stasiun</h3>
					</div>
					<div class="box-body">
						<table class="table">
							<tr>
								<th>ID Stasiun (Andila)</th>
								<td>{{ $station->id }}</td>
							</tr>
							<tr>
								<th>Nama Stasiun</th>
								<td>{{ $station->name }}</td>
							</tr>
							<tr>
								<th>No. Telepon</th>
								<td>{{ $station->phone }}</td>
							</tr>
							<tr>
								<th>Tipe</th>
								<td>{{ $station->type }}</td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td>
									@if($station->address !== null)
									<address>
										{{ $station->address->detail }},<br>
										KELURAHAN/DESA {{ $station->address->subdistrict }},<br>
										KECAMATAN {{ $station->address->district }},<br>
										{{ $station->address->regency }} {{ $station->address->postal_code }},<br>
										{{ $station->address->province }}<br>
									</address>
									@else
									Alamat belum disertakan. <a href="{{ route('web.stations.edit', $station->id) }}">Sertakan alamat</a>
									@endif
								</td>
							</tr>
						</table>
					</div>
				</div>
				<!-- /Profile Box -->

				<!-- Map Box -->
				<div id="box-profile-map" class="box box-default">
					<div class="box-header">
						<h3 class="box-title">Lokasi Stasiun</h3>
					</div>
					<div id="gmap-see-location" class="box-body google-map">
					</div>
					<div class="overlay">
						<p>Klik untuk membuka peta.</p>
					</div>
				</div>
				<!-- /Map Box -->

			</div>
			<!-- /Profile Pane -->

			<!-- Agents Pane -->
			<div id="agents" class="tab-pane fade">

				<!-- Agent Box -->
				<div id="box-agent-data" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Agen yang Bersangkutan</h3>
						<div class="box-tools pull-right">
							<div class="has-feedback">
								<input type="text" class="form-control input-sm" data-table="search" data-target="agents" placeholder="Cari...">
								<span class="glyphicon glyphicon-search form-control-feedback"></span>
							</div>
						</div>
					</div>
					<table id="andila-agent-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nama Agen</th>
								<th>Telepon</th>
								<th>Kuota/Bulan</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<!-- /Agent Box -->

				<!-- Spread Chart Box -->
				<div id="box-agent-chart" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Grafik Penyebaran LPG pada Agen</h3>
					</div>

					<canvas id="chart-agent-chart" style="width:100%"></canvas>
				</div>
				<!-- /Spread Chart Box -->

			</div>
			<!-- /Agents Pane -->

			<!-- Schedules Pane -->
			<div id="schedules" class="tab-pane fade">

				<!-- Schedules Box -->
				<div id="box-schedule-data" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Pesanan</h3>
						<div class="box-tools pull-right">
							<div class="has-feedback">
								<input type="text" class="form-control input-sm" data-table="search" data-target="schedules" placeholder="Cari...">
								<span class="glyphicon glyphicon-search form-control-feedback"></span>
							</div>
						</div>
					</div>
					<table id="andila-schedule-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Dari Agen</th>
								<th>Jadwal Pengiriman</th>
								<th>Jumlah Pesanan</th>
								<th>Waktu Pemesanan</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<!-- /Schedules Box -->

				<!-- Annual Chart Box -->
				<div id="box-schedule-chart" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Grafik Pemesanan Tahun Ini</h3>
					</div>

					<canvas id="chart-schedule-chart" style="width:100%"></canvas>
				</div>
				<!-- /Annual Chart Box -->

			</div>
			<!-- /Schedules Pane -->

		</div>
	</div>
</div>

<!-- Modal Station Delete -->
<div id="modal-station-delete" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus Stasiun</h4>
			</div>
			<div class="modal-body">
				<p class="lead">Apakah benar Anda akan menghapus stasiun ini? Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn-station-confirm-delete" class="btn btn-danger"><i class="fa fa-times"></i> Hapus Stasiun</button>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Station Delete -->

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="{!! asset('js/app/inner/stations/show.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection