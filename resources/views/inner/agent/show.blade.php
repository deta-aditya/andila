@extends('layouts.inner')

@section('meta')
<meta name="resource-uri" content="{{ $uri }}">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', $agent->name)
@section('head', $agent->name)
@section('description', 'Agen')

@section('breadcrumbs')
<li><a href="@if ($user->isAdmin()) {{ route('web.agents.index') }} @else # @endif">Agen</a></li>
<li class="active">{{ $agent->id }}</li>
@endsection

@section('content')
<div class="row">
	<div class="col-xs-12 col-md-3">

		<!-- Intro Box -->
		<div id="box-agent-intro" class="box box-danger">
			<div class="box-body box-profile">
				<img src="{{ asset('img/user1-128x128.jpg') }}" class="profile-user-img img-responsive img-circle" alt="Profile Image">
				<h3 class="profile-username text-center">{{ $agent->name }}</h3>
				<p class="text-muted text-center">Agen</p>

				@if($user->isAdmin()) 
				<div class="btn-group btn-group-justified">
					<a href="{{ route('web.agents.edit', $agent->id) }}" class="btn btn-default"><i class="fa fa-edit" style="margin-right:5px"></i>Ubah</a>	
					<a href="#modal-agent-delete" id="btn-agent-delete" class="btn btn-default" data-toggle="modal" title="Hapus Agen?">
						<i class="fa fa-trash" style="margin-right:5px"></i>Hapus
					</a>
				</div>
				@endif

			</div>
		</div>
		<!-- /Intro Box -->

		<!-- Navigation Box -->
		<div class="box box-solid">
			<div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li class="active"><a href="#profile" data-toggle="tab">Profil <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
					<li><a href="#stations" data-toggle="tab">Stasiun <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
					<li><a href="#subagents" data-toggle="tab">Subagen <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
					<li><a href="#schedules" data-toggle="tab">Pesanan <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
					<li><a href="#reports" data-toggle="tab">Penyaluran <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
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
						<h3 class="box-title">Profil Agen</h3>
					</div>
					<div class="box-body">
						<table class="table">
							<tr>
								<th>ID Agen (Andila)</th>
								<td>{{ $agent->id }}</td>
							</tr>
							<tr>
								<th>Nama Agen</th>
								<td>{{ $agent->name }}</td>
							</tr>
							<tr>
								<th>E-mail Agen</th>
								<td>{{ $agent->email }}</td>
							</tr>
							<tr>
								<th>No. Telepon</th>
								<td>{{ $agent->phone }}</td>
							</tr>
							<tr>
								<th>Kuota/Bulan</th>
								<td>{{ $agent->contract_value * 30 }}</td>
							</tr>
							<tr>
								<th>Aktif</th>
								<td>@if ($agent->active) Ya @else<b class="text-red">Tidak</b>@endif</td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td>
									@if($agent->address !== null)
									<address>
										{{ $agent->address->detail }},<br>
										KELURAHAN/DESA {{ $agent->address->subdistrict }},<br>
										KECAMATAN {{ $agent->address->district }},<br>
										{{ $agent->address->regency }} {{ $agent->address->postal_code }},<br>
										{{ $agent->address->province }}<br>
									</address>
									@else
									Alamat belum disertakan. <a href="{{ route('web.agents.edit', $agent->id) }}">Sertakan alamat</a>
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
						<h3 class="box-title">Lokasi Agen</h3>
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

			<!-- Stations Pane -->
			<div id="stations" class="tab-pane fade">

				<!-- Station Box -->
				<div id="box-station-data" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Stasiun yang Bersangkutan</h3>
						<div class="box-tools pull-right">
							<div class="has-feedback">
								<input type="text" class="form-control input-sm" data-table="search" data-target="stations" placeholder="Cari...">
								<span class="glyphicon glyphicon-search form-control-feedback"></span>
							</div>
						</div>
					</div>
					<table id="andila-station-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nama Stasiun</th>
								<th>Telepon</th>
								<th>Tipe</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<!-- /Station Box -->

			</div>
			<!-- /Stations Pane -->

			<!-- Subagents Pane -->
			<div id="subagents" class="tab-pane fade">

				<!-- Subagent Box -->
				<div id="box-subagent-data" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Subagen yang Bersangkutan</h3>
						<div class="box-tools pull-right">
							<div class="has-feedback">
								<input type="text" class="form-control input-sm" data-table="search" data-target="subagents" placeholder="Cari...">
								<span class="glyphicon glyphicon-search form-control-feedback"></span>
							</div>
						</div>
					</div>
					<table id="andila-subagent-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nama Subagen</th>
								<th>Telepon</th>
								<th>Kuota/Hari</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<!-- /Subagent Box -->

				<!-- Spread Chart Box -->
				<div id="box-subagent-chart" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Grafik Penyebaran LPG pada Subagen</h3>
					</div>

					<canvas id="chart-subagent-chart" style="width:100%"></canvas>
				</div>
				<!-- /Spread Chart Box -->

			</div>
			<!-- /Subagents Pane -->

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
								<th>Ke Stasiun</th>
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

			<!-- Reports Pane -->
			<div id="reports" class="tab-pane fade">

				<!-- Reports Box -->
				<div id="box-report-data" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Penyaluran</h3>
						<div class="box-tools pull-right">
							<div class="has-feedback">
								<input type="text" class="form-control input-sm" data-table="search" data-target="reports" placeholder="Cari...">
								<span class="glyphicon glyphicon-search form-control-feedback"></span>
							</div>
						</div>
					</div>
					<table id="andila-report-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Ke Subagen</th>
								<th>Jadwal Pengiriman</th>
								<th>Alokasi</th>
								<th>Total Penjualan</th>
								<th>Dilaporkan Pada</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<!-- /Reports Box -->

			</div>
			<!-- /Reports Pane -->

		</div>
	</div>
</div>

<!-- Modal Agent Delete -->
<div id="modal-agent-delete" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus Agen</h4>
			</div>
			<div class="modal-body">
				<p class="lead">Apakah benar Anda akan menghapus agen ini? Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn-agent-confirm-delete" class="btn btn-danger"><i class="fa fa-times"></i> Hapus Agen</button>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Agent Delete -->
@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/agents/show.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection