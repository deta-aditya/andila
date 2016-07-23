@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker3.css') }}">
@endsection

@section('title', $db['station']->name)
@section('head', $db['station']->name)
@section('description', 'Stasiun')

@section('breadcrumbs')
<li><a href="{{ url('admin/station') }}">Stasiun</a></li>
<li class="active">{{ $db['station']->id }}</li>
@endsection

@section('content')
<div class="row">

	<div class="col-xs-12">
		
		<div class="box box-default box-agen-chart" data-url="{{ url('api/monthly?planned_month=this&station='. $db['station']->id) }}">
			<div class="box-header with-border">
				<h3 class="box-title">Grafik Alokasi LPG Bulan Ini</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<canvas id="agen-chart" style="width:100%"></canvas>
			</div>
			<!--div class="overlay">
				<i class="fa fa-refresh fa-spin"></i>
			</div-->
		</div>

	</div>

	<div class="col-xs-12 col-lg-4">
		<div class="box box-default box-profile">
			<div class="box-header">
				<h3 class="box-title">Profil Stasiun</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-condensed">
					<tbody>
						<tr><th>ID</th><td>{{ $db['station']->id }}</td></tr>
						<tr><th>ID Registrasi</th><td>{{ $db['station']->id_reg }}</td></tr>
						<tr><th>Nama</th><td>{{ $db['station']->name }}</td></tr>
						<tr><th>E-mail</th><td>{{ $db['station']->email }}</td></tr>
						<tr><th>No. Telepon</th><td>{{ $db['station']->phone }}</td></tr>
						<tr><th>Tipe Stasiun</th><td>{{ $db['station']->type }}</td></tr>
						<tr><th>Terdaftar Sejak</th><td>{{ Helper::indonesianDate($db['station']->created_at) }}</td></tr>
						<tr><th>Alamat Lengkap</th><td>{{ $db['station']->address->street }}</td></tr>
						<tr><th>Kelurahan/Desa</th><td>{{ $db['station']->address->subdistrict }}</td></tr>
						<tr><th>Kecamatan</th><td>{{ $db['station']->address->district }}</td></tr>
						<tr><th>Kota/Kabupaten</th><td>{{ $db['station']->address->region }}</td></tr>
						<tr><th>Provinsi</th><td>{{ $db['station']->address->province }}</td></tr>
						<tr><th>Kode Pos</th><td>{{ $db['station']->address->postal_code }}</td></tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box box-default gmap-box">
			<div class="box-header with-border">
				<h3 class="box-title">Lokasi Pada Peta</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div id="gmap-location" class="box-body google-map" data-latLng="{{ $db['station']->location }}">
				
			</div>
			<div class="overlay">
				<p>Klik untuk membuka peta.</p>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-lg-8">

		<div class="alert-placeholder-monthly"></div>

		<form id="form-monthly-create" class="box box-default box-monthly" method="post" action="{{ url('api/monthly/batch') }}">
			{!! csrf_field() !!}
			<input type="hidden" name="station_id" value="{{ $db['station']->id }}">
			<div class="box-header with-border">
				<h3 class="box-title">Rencana Distribusi LPG Bulan Ini</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<table id="table-monthly-this-month" class="table table-condensed table-striped table-hover bs19-data-table">
					<thead>
						<tr>
							<th>Agen</th>
							<th>Alokasi</th>
							<th title="Rencana Pengiriman">Rcn. Pengiriman</th>
							<th title="Waktu Dilaporkan">Wkt. Dilaporkan</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($db['station']->agens as $a)
						<tr @if($a->monthlyDistributions->first()) data-planned @endif>
							<td>{{ $a->name }}</td>
							<td>{{ $a->quota_monthly }}</td>
							<td>
								@if($a->monthlyDistributions->first())
									@unless(is_null($a->monthlyDistributions->first()->date_planned))
										{{ Helper::indonesianDate($a->monthlyDistributions->first()->date_planned, 'l, j F Y', '') }}
									@endunless
								@else
									<div class="label label-warning">Belum Direncanakan</div>
									<div class="plan-date input-group hidden">
										<input type="text" class="form-control datepicker" name="date_planned[{{ $a->id }}]">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								@endif
							</td>
							<td>
								@if($a->monthlyDistributions->first())
									@unless(is_null($a->monthlyDistributions->first()->date_confirmed))
										{{ Helper::indonesianDate($a->monthlyDistributions->first()->date_confirmed, 'l, j F Y', '') }}
									@else
										<div class="label label-info">Belum Dilaporkan</div>
									@endunless
								@else
									<div class="label label-info">Belum Dilaporkan</div>
								@endif
							</tr>
						</tr>
					@endforeach
					</tbody>
				</table>

			</div>
			<div class="box-footer">
				<button id="edit-mode-on" type="button" class="btn btn-primary btn-flat"><i class="fa fa-file-text-o"></i> Tambah Rencana</button>
				<button type="submit" class="btn btn-primary btn-flat hidden"><i class="fa fa-send"></i> Simpan</button>
				<button id="edit-mode-off" type="button" class="btn btn-link hidden"><i class="fa fa-close"></i> Batal</button>
				<a href="{{ url('admin/agen/create?station='. $db['station']->id) }}" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> Tambah Agen</a>
				<a href="{{ url('admin/monthly/this-month?station='. $db['station']->id) }}" class="btn btn-link pull-right"><i class="fa fa-th"></i> Lihat Semua</a>
			</div>
		</form>

	</div>

	<div class="col-xs-12">
		<a href="{{ url('admin/station/'. $db['station']->id . '/edit') }}" class="btn btn-lg btn-warning"><i class="fa fa-edit" style="margin-right:10px"></i> Ubah Stasiun</a>
		&nbsp;<a href="#modal-delete" class="btn btn-lg btn-danger" data-toggle="modal"><i class="fa fa-trash" style="margin-right:10px"></i> Hapus Stasiun</a>
		<a href="{{ url('admin/station') }}" class="btn btn-lg btn-link">Kembali</a>
	</div>

</div>

<!-- Delete Modal -->
<div id="modal-delete" class="modal modal-danger fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus Stasiun?</h4>
			</div>
			<div class="modal-body">
				<p>Apakah benar Anda akan menghapus stasiun ini?</p>
			</div>
			<form id="form-station-delete" class="modal-footer" method="post" action="agen">
				{!! csrf_field() !!}
				{!! method_field('DELETE') !!}
				<input type="hidden" name="action-to" value="{{ url('api/station/'. $db['station']->id) }}">
				<button type="submit" class="btn btn-default"><i class="fa fa-trash"></i> Benar</button>
				<button type="button" class="btn btn-outline" data-dismiss="modal"><i class="fa fa-remove"></i> Tidak</button>
			</form>			
		</div>
	</div>
</div>
<!-- /Delete Modal -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="{!! asset('vendor/datepicker/bootstrap-datepicker.js') !!}"></script>
<script src="{!! asset('vendor/select2/select2.full.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>

<script src="{!! asset('js/app/admin/index.js') !!}"></script>
<script src="{!! asset('js/app/admin/station/show.js') !!}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo&callback=initMap" async defer></script>
@endsection
