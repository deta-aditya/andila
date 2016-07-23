@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Isi Pelaporan')
@section('head', 'Isi Pelaporan')

@section('breadcrumbs')
<li class="active">Pelaporan</li>
@endsection

@section('content')
<form id="form-report-complete">
	{!! csrf_field() !!}
	<div id="andila-report-box" class="box box-default">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-sm-9">
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
				<div class="col-sm-3">
					<button type="submit" class="btn btn-danger pull-right" style="margin-left:10px">
						<i class="fa fa-save"></i>&nbsp;Laporkan
					</button>
				</div>
			</div>
		</div>

		<table class="table table-condensed table-striped table-hover table-clickable andila-datatable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Tanggal Terjadwal</th>
					<th>Alokasi</th>
					<th title="Penjualan kepada rumah tangga">P. Rumah Tangga</th>
					<th title="Penjualan kepada bisnis kecil (mikrobisnis)">P. Mikrobisnis</th>
					<th title="Penjualan kepada pengecer">P. Pengecer</th>
					<th>Tabung Kosong</th>
					<th>Tabung Berisi</th>
					<th>Total Penjualan</th>
					<th>Dilaporkan Pada</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

		<div class="overlay">
			<i class="fa fa-spinner fa-pulse"></i>
		</div>
	</div>
</form>

<div id="modal-report-retailers" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Penjualan kepada Pengecer</h4>
			</div>
			<div class="modal-body">
				<div class="form-group retailer-fields">
					<div class="row">
						<div class="col-sm-6">
							<b>Nama Pengecer</b>
						</div>
						<div class="col-sm-4">
							<b>Jumlah</b>
						</div>
						<div class="col-sm-2">
							<b>Hapus</b>
						</div>
					</div>
				</div>
			</div>			
			<div class="modal-footer">
				<button type="button" id="btn-retailer-add" class="btn btn-default pull-left"><i class="fa fa-plus"></i> Tambah Pengecer</button>
				<button type="button" id="btn-retailer-done" class="btn btn-danger"><i class="fa fa-check"></i> Selesai</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/reports/index.js') !!}"></script>
@endsection