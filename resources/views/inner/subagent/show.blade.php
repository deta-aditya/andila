@extends('layouts.inner')

@section('meta')
<meta name="resource-uri" content="{{ $uri }}">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', $subagent->name)
@section('head', $subagent->name)
@section('description', 'Subagen')

@section('breadcrumbs')
<li><a href="@unless ($user->isSubagent()) {{ route('web.agents.index') }} @else # @endunless">Subagen</a></li>
<li class="active">{{ $subagent->id }}</li>
@endsection

@section('content')
<div class="row">
	<div class="col-xs-12 col-md-3">

		<!-- Intro Box -->
		<div id="box-subagent-intro" class="box box-danger">
			<div class="box-body box-profile">
				<img src="{{ asset('img/user1-128x128.jpg') }}" class="profile-user-img img-responsive img-circle" alt="Profile Image">
				<h3 class="profile-username text-center">{{ $subagent->name }}</h3>
				<p class="text-muted text-center">Subagen</p>

				<div class="btn-group btn-group-justified">

					@if($user->isSubagent() && $user->handleable->id === $subagent->id)
					<a href="{{ route('web.users.edit', $user->id) }}" class="btn btn-default"><i class="fa fa-edit" style="margin-right:5px"></i>Ubah Profil</a>
					@endif

					@if ($user->isAdmin())
					<a href="{{ route('web.subagents.edit', $subagent->id) }}" class="btn btn-default"><i class="fa fa-edit" style="margin-right:5px"></i>Ubah</a>
					<a href="#modal-subagent-delete" id="btn-subagent-delete" class="btn btn-default" data-toggle="modal" title="Hapus Subagen?">
						<i class="fa fa-trash" style="margin-right:5px"></i>Hapus
					</a>
					@endif

				</div>
			</div>
		</div>
		<!-- /Intro Box -->

		<!-- Navigation Box -->
		<div class="box box-solid">
			<div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li class="active"><a href="#profile" data-toggle="tab">Profil <i class="fa fa-angle-right pull-right" style="margin-top:2.5px"></i></a></li>
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
						<h3 class="box-title">Profil Subagen</h3>
					</div>
					<div class="box-body">
						<table class="table">
							<tr>
								<th>ID Subagen (Andila)</th>
								<td>{{ $subagent->id }}</td>
							</tr>
							<tr>
								<th>Agen Yang Bersangkutan</th>
								<td>
									@unless ($user->isSubagent())
									<a href="{{ route('web.agents.show', $subagent->agent->id) }}">{{ $subagent->agent->name }}</a>
									@else
									<b>{{ $subagent->agent->name }}</b>
									@endunless
								</td>
							</tr>
							<tr>
								<th>Nama Subagen</th>
								<td>{{ $subagent->name }}</td>
							</tr>
							<tr>
								<th>E-mail Subagen</th>
								<td>{{ $subagent->email }}</td>
							</tr>
							<tr>
								<th>E-mail Pengguna</th>
								<td>{{ $subagent->user ? $subagent->user->email : 'Tidak Ada' }}</td>
							</tr>
							<tr>
								<th>No. Telepon</th>
								<td>{{ $subagent->phone }}</td>
							</tr>
							<tr>
								<th>Kuota/Hari</th>
								<td>{{ $subagent->contract_value }}</td>
							</tr>
							<tr>
								<th>Aktif</th>
								<td>@if ($subagent->active) Ya @else<b class="text-red">Tidak</b>@endif</td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td>
									@if($subagent->address !== null)
									<address>
										{{ $subagent->address->detail }},<br>
										KELURAHAN/DESA {{ $subagent->address->subdistrict }},<br>
										KECAMATAN {{ $subagent->address->district }},<br>
										{{ $subagent->address->regency }} {{ $subagent->address->postal_code }},<br>
										{{ $subagent->address->province }}<br>
									</address>
									@else
									Alamat belum disertakan. <a href="{{ route('web.subagents.edit', $subagent->id) }}">Sertakan alamat</a>
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
						<h3 class="box-title">Lokasi Subagen</h3>
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

<!-- Modal Subagent Delete -->
<div id="modal-subagent-delete" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus Subagen</h4>
			</div>
			<div class="modal-body">
				<p class="lead">Apakah benar Anda akan menghapus subagen ini? Berhati-hatilah karena aksi ini tidak dapat dipertiadakan.</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="btn-subagent-confirm-delete" class="btn btn-danger"><i class="fa fa-times"></i> Hapus Subagen</button>
			</div>
		</div>
	</div>
</div>
<!-- /Modal Subagent Delete -->

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="{!! asset('js/app/inner/subagents/show.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection