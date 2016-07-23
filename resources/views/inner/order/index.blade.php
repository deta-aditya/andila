@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Atur Pesanan')
@section('head', 'Atur Pesanan')

@section('breadcrumbs')
<li class="active">Pesanan</li>
@endsection

@section('content')
<div id="andila-orders-box" class="box box-default">
	<div class="box-header with-border">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-3">
						<input type="number" class="form-control" placeholder="Tahun..." max="{{ date('Y') }}" min="{{ date('Y') - 10 }}" data-table="search-scheduled-date">
					</div>
					<div class="col-sm-4">
						<div class="input-group">
							<input class="form-control" type="text" data-table="search" placeholder="Cari...">
							<span class="input-group-addon">
								<span class="fa fa-search"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<table class="table table-condensed table-striped table-hover table-clickable andila-datatable">
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
		<i class="fa fa-spinner fa-pulse"></i>
	</div>
</div>

<div id="modal-orders-show" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class="orders-scheduled-date">Sebuah Jadwal</span> <small class="orders-id"></small></h4>
				<p class="orders-allocated">Teralokasi</p>
			</div>			
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-order-create" class="col-sm-12 text-center"><i class="fa fa-truck"></i> Pesan</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-orders-create" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form id="form-order-create" class="form-horizontal modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Pesan LPG</h4>
			</div>
			<div class="modal-body">
				<p>Apakah benar Anda akan memesan sekarang? Pesanan perlu diterima terlebih dahulu oleh stasiun untuk dapat memulai penyaluran ke subagen.</p>
				<div class="form-group">
					<input type="hidden" name="station_id">
					<div class="col-sm-3 text-right"><b>Stasiun</b></div>
					<div class="col-sm-9 schedule-station">Sebuah Stasiun</div>
				</div>
				<div class="form-group">
					<input type="hidden" name="agent_id">
					<div class="col-sm-3 text-right"><b>Agen</b></div>
					<div class="col-sm-9 schedule-agent">Sebuah Agen</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right"><b>Tanggal Terjadwal</b></div>
					<div class="col-sm-9 schedule-scheduled-date">Tanggal</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right"><b>Jumlah Pesanan LPG</b></div>
					<div class="col-sm-9 schedule-quantity"><b class="text-danger">Qty</b></div>
				</div>
			</div>			
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger"><i class="fa fa-truck" style="margin-right:10px"></i>Pesan</button>
			</div>
		</form>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/orders/index.js') !!}"></script>
@endsection