@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker3.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Atur Jadwal')
@section('head', 'Atur Jadwal')

@section('breadcrumbs')
<li class="active">Jadwal</li>
@endsection

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-4">
		<div id="andila-stations-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Stasiun</h3>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="stations" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<table id="andila-stations-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nama Stasiun</th>
						<th class="hidden-xs hidden-sm hidden-md">Provinsi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="overlay">
				<i class="fa fa-spinner fa-pulse"></i>
			</div>
		</div>
		<div id="andila-agents-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Agen</h3>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="agents" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<table id="andila-agents-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nama Agen</th>
						<th class="hidden-xs hidden-sm hidden-md">Provinsi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="overlay">
				<i class="fa fa-spinner fa-pulse"></i>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-8">
		<div id="andila-schedules-box" class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Daftar Jadwal</h3>
				<div class="box-tools pull-right">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-sm btn-default active">
							<input type="radio" name="active" value="" data-table="search-allocation" autocomplete="off" checked> Semua
						</label>
						<label class="btn btn-sm btn-default">
							<input type="radio" name="active" value="1" data-table="search-allocation" autocomplete="off"> Teralokasi
						</label>
						<label class="btn btn-sm btn-default">
							<input type="radio" name="active" value="Belum Ada" data-table="search-allocation" autocomplete="off"> Belum Teralokasi
						</label>
					</div>
					<button type="button" class="btn btn-sm btn-danger pull-right" data-toggle="modal" data-target="#modal-schedule-create" autocomplete="off" style="margin-left:10px" disabled>
						<i class="fa fa-plus"></i>&nbsp;Tambah Jadwal
					</button>
				</div>
			</div>
			<table id="andila-schedules-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Tanggal Terjadwal</th>
						<th>Alokasi</th>
						<th>Dibuat Pada</th>
						<th>Diterima Pada</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="overlay">
				<p>Silahkan pilih <b>Stasiun</b> atau <b>Agen</b> terlebih dahulu.</p>
			</div>
		</div>
	</div>
</div>

<div id="modal-schedule-create" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form id="form-schedule-create" class="form-horizontal modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tambah Jadwal</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="hidden" name="station_id">
					<div class="col-sm-3 text-right"><b>Stasiun</b></div>
					<div class="col-sm-9 station-name">Sebuah Stasiun</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="agent_id">
					<div class="col-sm-3 text-right"><b>Agen</b></div>
					<div class="col-sm-9 agent-name">Sebuah Agen</div>
				</div>
				<div class="form-group">
					<label for="scheduled_date" class="col-sm-3 control-label">Tanggal Terjadwal</label>
					<div class="col-sm-9">
						<input type="text" class="form-control andila-datepicker" name="scheduled_date" required>
						<p class="help-block">Tanggal terjadwal harus paling tidak satu bulan setelah jadwal sebelumnya (pada agen). Jika jadwal belum ada, maka tanggal harus setelah hari ini.</p>
					</div>
				</div>
			</div>			
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger"><i class="fa fa-send" style="margin-right:10px"></i>Simpan Data</button>
			</div>
		</form>
	</div>
</div>

<div id="modal-schedule-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
				<h4><span class="schedule-scheduled-date">Sebuah Jadwal</span> <small class="schedule-id"></small></h4>
				<p class="schedule-allocated">Teralokasi</p>
				<div class="row">
					<div class="col-sm-6">
						<h5>Stasiun</h5>
						<p class="schedule-station" style="font-weight:bold">Sebuah Stasiun</p>
					</div>
					<div class="col-sm-6">
						<h5>Agen</h5>
						<p class="schedule-agent" style="font-weight:bold">Sebuah Agen</p>
					</div>
				</div>
			</div>			
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-schedule-accept" class="col-sm-12 text-center" data-toggle="popover" title="Terima Pesanan?"><i class="fa fa-check"></i> Terima</a>
					<a href="#" id="btn-schedule-subschedules" class="col-sm-12 text-center"><i class="fa fa-book"></i> Laporan</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datepicker/bootstrap-datepicker.js') !!}"></script>
<script src="{!! asset('vendor/datepicker/locales/bootstrap-datepicker.id.js') !!}"></script>
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/schedules/index.js') !!}"></script>
@endsection