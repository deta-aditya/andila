@extends('layouts.inner')

@section('meta')
<meta name="this-user-uri" content="{{ route('api.v0.users.show', session('weblogin')->id) }}">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker3.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Laporan')
@section('head', 'Lihat Laporan')

@section('breadcrumbs')
<li class="active">Pelaporan</li>
@endsection

@section('content')

<p class="lead">Silahkan tentukan opsi-opsi di bawah ini untuk menentukan spesifikasi laporan, lalu klik tombol "Lihat" di ujung kiri bawah.</p>

<form id="form-report-query" class="row">
	{!! csrf_field() !!}

	<div class="col-sm-4">

		@if ($user->isAdmin())
		<!-- Select Station -->
		<div id="andila-stations-box" class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Stasiun</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="stations" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<div class="box-body no-padding">
				<table id="andila-stations-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
					<thead>
						<tr><th>ID</th><th>Nama Stasiun</th></tr>
					</thead>
					<tbody>
					@foreach ($stations as $station)
						<tr class="clickable"><td>{{ $station->id }}</td><td>{{ $station->name }}</td></tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<b>Dipilih :</b> <span class="is-selected"></span>	
				<button type="button" class="btn btn-link btn-sm pull-right clear-selected" data-target="stations"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<!-- /Select Station -->
		@endif

		<!-- Select Schedule -->
		<div id="andila-schedules-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Jadwal/Pesanan</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="schedules" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<div class="box-body no-padding">
				<table id="andila-schedules-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
					<thead>
						<tr><th title="ID Jadwal / ID Pesanan">ID</th><th>Tanggal Terjadwal</th></tr>
					</thead>
					<tbody>
					@if ($user->isAgent())
					@foreach ($schedules as $schedule)
						<tr class="clickable"><td>{{ $schedule->id }}</td><td>{{ Helper::indonesianDate($schedule->scheduled_date, 'j F Y', false) }}</td></tr>
					@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<b>Dipilih :</b> <span class="is-selected"></span>
				<button type="button" class="btn btn-link btn-sm pull-right clear-selected" data-target="schedules"><i class="fa fa-remove"></i></button>
			</div>
			@if ($user->isAdmin())
			<div class="overlay">
				<p>Silahkan pilih opsi lain terlebih dahulu.</p>
			</div>
			@endif
		</div>
		<!-- /Select Schedule -->

	</div>

	@if ($user->isAdmin())
	<div class="col-sm-8">

		<div class="row">

			<div class="col-sm-6">

				<!-- Select Agent -->
				<div id="andila-agents-box" class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Pilih Agen</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="input-group">
							<input class="form-control" type="text" data-table="search" data-target="agents" placeholder="Cari...">
							<span class="input-group-addon">
								<span class="fa fa-search"></span>
							</span>
						</div>
					</div>
					<div class="box-body no-padding">
						<table id="andila-agents-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
							<thead>
								<tr><th>ID</th><th>Nama Agen</th></tr>
							</thead>
							<tbody>
							@foreach ($agents as $agent)
								<tr class="clickable"><td>{{ $agent->id }}</td><td>{{ $agent->name }}</td></tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<b>Dipilih :</b> <span class="is-selected"></span>
						<button type="button" class="btn btn-link btn-sm pull-right clear-selected" data-target="agents"><i class="fa fa-remove"></i></button>	
					</div>
				</div>
				<!-- /Select Agent -->

			</div>

			<div class="col-sm-6">

				<!-- Select Subagent -->
				<div id="andila-subagents-box" class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Pilih Subagen</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="input-group">
							<input class="form-control" type="text" data-table="search" data-target="subagents" placeholder="Cari...">
							<span class="input-group-addon">
								<span class="fa fa-search"></span>
							</span>
						</div>
					</div>
					<div class="box-body no-padding">
						<table id="andila-subagents-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
							<thead>
								<tr><th>ID</th><th>Nama Subagen</th></tr>
							</thead>
							<tbody>
							@foreach ($subagents as $subagent)
								<tr class="clickable"><td>{{ $subagent->id }}</td><td>{{ $subagent->name }}</td></tr>
							@endforeach
							</tbody>
						</table>
					</div>
					<div class="box-footer">
						<b>Dipilih :</b> <span class="is-selected"></span>	
						<button type="button" class="btn btn-link btn-sm pull-right clear-selected" data-target="subagents"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /Select Subagent -->

			</div>

		</div>

		<!-- Other -->
		<div id="andila-other-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Opsi Lainnya</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="range_from" class="control-label">Rentang Waktu</label>

					<div class="row">
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" class="form-control andila-datepicker" placeholder="Dari..." name="range_from">
								<span class="input-group-addon">
									<i class="fa fa-arrow-right"></i>
								</span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-arrow-right"></i>
								</span>
								<input type="text" class="form-control andila-datepicker" placeholder="Hingga..." name="range_to">
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Other -->

	</div>
	@endif

	@if ($user->isAgent())
	<div class="col-sm-4">

		<!-- Select Subagent -->
		<div id="andila-subagents-box" class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Subagen</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="subagents" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<div class="box-body no-padding">
				<table id="andila-subagents-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
					<thead>
						<tr><th>ID</th><th>Nama Subagen</th></tr>
					</thead>
					<tbody>
					@foreach ($subagents as $subagent)
						<tr class="clickable"><td>{{ $subagent->id }}</td><td>{{ $subagent->name }}</td></tr>
					@endforeach
					</tbody>
				</table>
			</div>
			<div class="box-footer">
				<b>Dipilih :</b> <span class="is-selected"></span>	
				<button type="button" class="btn btn-link btn-sm pull-right clear-selected" data-target="subagents"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<!-- /Select Subagent -->

	</div>
	<div class="col-sm-4">

		<!-- Other -->
		<div id="andila-other-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Opsi Lainnya</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label for="range_from" class="control-label">Rentang Waktu</label>

					<div class="row">
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" class="form-control andila-datepicker" placeholder="Dari..." name="range_from">
								<span class="input-group-addon">
									<i class="fa fa-arrow-right"></i>
								</span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fa fa-arrow-right"></i>
								</span>
								<input type="text" class="form-control andila-datepicker" placeholder="Hingga..." name="range_to">
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- /Other -->

	</div>
	@endif 

	<div class="col-xs-12">
		<button type="submit" class="btn btn-primary"><i class="fa fa-book" style="margin-right:10px" autocomplete="off"></i> Lihat Laporan</button>
	</div>
</form>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datepicker/bootstrap-datepicker.js') !!}"></script>
<script src="{!! asset('vendor/datepicker/locales/bootstrap-datepicker.id.js') !!}"></script>
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/reports/query.js') !!}"></script>
@endsection