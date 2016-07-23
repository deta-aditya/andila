@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker3.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title')
Lihat Penyaluran
@endsection

@section('head', 'Penyaluran ke Pangkalan')

@section('breadcrumbs')
<li class="active">Penyaluran</li>
@endsection

@section('content')
<form method="post" action="{{ url('api/daily') }}" class="row">
	{!! csrf_field() !!}
	{!! method_field('PUT') !!}
	<div class="form-group col-sm-4 col-lg-2">
		<label class="control-label" style="display:block">Pangkalan</label>
		<select name="pangkalan" class="form-control select2">
			<option value="null" data-id="null" @unless(isset($pangkalan)) selected @endunless disabled>Pilih Pangkalan...</option>
			@foreach ($db['pangkalans'] as $p)
			<option value="{{ $p->id }}" @if(isset($pangkalan) && $pangkalan == $p->id) selected @endif>{{ $p->name }}</option>
			@endforeach
		</select>
	</div>
	<div class="form-group col-sm-4 col-lg-2">
		<label class="control-label">Dari Tanggal</label>
		<input type="text" class="form-control datepicker" name="date_from" placeholder="Dari Tanggal" value="{{ $date_from or '' }}">	
	</div>
	<div class="form-group col-sm-4 col-lg-2">
		<label class="control-label">Hingga Tanggal</label>
		<input type="text" class="form-control datepicker" name="date_to" placeholder="Hingga Tanggal" value="{{ $date_to or '' }}">
	</div>
	<div class="form-group col-sm-12 col-lg-4">
		<label class="control-label" style="display:block">Aksi</label>
		<div class="btn-group">
			<button id="search" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Cari"><i class="fa fa-search"></i></button>
			<button id="seed" type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Semai Rencana"><i class="fa fa-calendar-plus-o"></i></button>
			<button id="edit-mode" type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Modifikasi Rencana"><i class="fa fa-calendar-check-o"></i></button>
			<a href="agen/daily/{{ $date_from }}@if(isset($date_to))_{{ $date_to }}@endif/report" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print"></i></a>
			<a href="agen/daily/{{ $date_from }}@if(isset($date_to))_{{ $date_to }}@endif/download" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Unduh (PDF)"><i class="fa fa-file-pdf-o"></i></a>
		</div>
	</div>
</form>

<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title">Tabel Penyaluran <small class="range">{{ $range or '' }}</small></h3>
	</div>
	<div class="box-body">

		<table class="table table-condensed table-striped table-hover bs19-data-table table-clickable">
			<thead>
				<tr>
					<th>No.</th>
					<th title="Pangkalan">Pkln.</th>
					<th title="Alokasi">A</th>
					<th title="Total Normal">TN</th>
					<th title="Total Fakultatif">TF</th>
					<th title="Total Alokasi">TA</th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>

	</div>
</div>

<div id="modal-seed" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Semai Rencana Penyaluran</h4>
			</div>
			<div class="modal-body">
				<p class="lead">Data penyaluran harus di"semai" pada sistem terlebih dahulu agar dapat direncanakan.</p>
				<p>Anda akan menyemai penyaluran pada periode: <b> </b></p>
			</div>
			<div class="modal-footer">
				<button id="seed-apply" type="button" class="btn btn-danger" data-dismiss="modal">Semai</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{!! asset('vendor/datepicker/bootstrap-datepicker.js') !!}"></script>
<script src="{!! asset('vendor/datepicker/locales/bootstrap-datepicker.id.js') !!}"></script>
<script src="{!! asset('vendor/select2/select2.full.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>

<script src="{!! asset('js/app/agen/daily/index.js') !!}"></script>
@endsection
