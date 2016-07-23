@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker3.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Atur Penyaluran')
@section('head', 'Atur Penyaluran')

@section('breadcrumbs')
<li class="active">Penyaluran</li>
@endsection

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-4">
		<div id="andila-subagents-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Subagen</h3>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="subagents" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<table id="andila-subagents-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nama Subagen</th>
						<th>Kuota/Hari</th>
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
		<div id="andila-subschedules-box" class="box box-danger">
			<form id="form-report-create">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Penyaluran</h3>
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
						<button type="submit" class="btn btn-sm btn-danger pull-right" style="margin-left:10px">
							<i class="fa fa-save"></i>&nbsp;Simpan Alokasi
						</button>
					</div>
				</div>
				<table id="andila-subschedules-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tanggal Terjadwal</th>
							<th>Alokasi</th>
							<th>Dibuat Pada</th>
							<th>Dilaporkan Pada</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</form>
			<div class="overlay">
				<p>Silahkan pilih <b>Subagen</b> terlebih dahulu.</p>
			</div>
		</div>
	</div>
</div>

<div id="modal-schedule-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
				<h4><span class="schedule-scheduled-date">Sebuah Penyaluran</span> <small class="schedule-id"></small></h4>
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
					<a href="#" class="col-sm-6 text-center"><i class="fa fa-eye"></i> Detail</a>
					<a href="#" id="btn-schedule-accept" class="col-sm-6 text-center" data-toggle="popover" title="Terima Pesanan?"><i class="fa fa-check"></i> Terima</a>
					<a href="#" id="btn-schedule-subschedules" class="col-sm-6 text-center"><i class="fa fa-shopping-cart"></i> Penyaluran</a>
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
<script src="{!! asset('js/app/inner/subschedules/index.js') !!}"></script>
@endsection