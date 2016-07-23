@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker3.css') }}">
@endsection

@section('title', $db['agen']->name)
@section('head', $db['agen']->name)
@section('description', 'Agen')

@section('breadcrumbs')
<li><a href="{{ url('admin/agen') }}">Agen</a></li>
<li class="active">{{ $db['agen']->id }}</li>
@endsection

@section('content')
<div class="row">

	<div class="col-xs-12">
		
		<div class="box box-default box-pangkalan-chart" data-url="{{ url('api/daily?planned_day=this&agen='. $db['agen']->id) }}">
			<div class="box-header with-border">
				<h3 class="box-title">Grafik Alokasi LPG Hari Ini</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<canvas id="pangkalan-chart" style="width:100%"></canvas>
			</div>
			<!--div class="overlay">
				<i class="fa fa-refresh fa-spin"></i>
			</div-->
		</div>

	</div>

	<div class="col-xs-12 col-lg-4">
		<div class="box box-default box-profile">
			<div class="box-header">
				<h3 class="box-title">Profil Agen</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<table class="table table-condensed">
					<tbody>
						<tr><th>ID</th><td>{{ $db['agen']->id }}</td></tr>
						<tr><th>ID Registrasi</th><td>{{ $db['agen']->id_reg }}</td></tr>
						<tr><th>Stasiun Terkait</th><td><a href="{{ url('admin/station/'. $db['agen']->station->id) }}">{{ $db['agen']->station->name }}</a></td></tr>
						<tr><th>Nama</th><td>{{ $db['agen']->name }}</td></tr>
						<tr><th>E-mail</th><td>{{ $db['agen']->email }}</td></tr>
						<tr><th>No. Telepon</th><td>{{ $db['agen']->phone }}</td></tr>
						<tr><th>Terdaftar Sejak</th><td>{{ Helper::indonesianDate($db['agen']->created_at) }}</td></tr>
						<tr><th>NPWP</th><td>{{ $db['agen']->npwp }}</td></tr>
						<tr><th>Sales Executive</th><td>{{ $db['agen']->sales_exec }}</td></tr>
						<tr><th>Sales Group</th><td>{{ $db['agen']->sales_group }}</td></tr>
						<tr><th>Aktif</th><td>@if($db['agen']->active) Ya @else Tidak @endif</td></tr>
						<tr><th>Alokasi/Bulan</th><td>{{ $db['agen']->quota_monthly }} tabung</td></tr>
						<tr><th>Alamat Lengkap</th><td>{{ $db['agen']->address->street }}</td></tr>
						<tr><th>Kelurahan/Desa</th><td>{{ $db['agen']->address->subdistrict }}</td></tr>
						<tr><th>Kecamatan</th><td>{{ $db['agen']->address->district }}</td></tr>
						<tr><th>Kota/Kabupaten</th><td>{{ $db['agen']->address->region }}</td></tr>
						<tr><th>Provinsi</th><td>{{ $db['agen']->address->province }}</td></tr>
						<tr><th>Kode Pos</th><td>{{ $db['agen']->address->postal_code }}</td></tr>
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
			<div id="gmap-location" class="box-body google-map" data-latLng="{{ $db['agen']->location }}">
				
			</div>
			<div class="overlay">
				<p>Klik untuk membuka peta.</p>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-lg-8">
		
		<div class="alert-placeholder-distribution"></div>

		<form id="form-daily-create" class="box box-default box-daily" method="post" action="{{ url('api/daily/batch') }}">
			{!! csrf_field() !!}
			<input type="hidden" name="agen_id" value="{{ $db['agen']->id }}">
			<input type="hidden" name="range" value="{{ Helper::getThisWeekDateRange() }}">
			<div class="box-header with-border">
				<h3 class="box-title">Rencana Distribusi LPG Minggu Ini</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<table id="table-daily-this-week" class="table table-condensed table-striped table-hover bs19-data-table">
					<thead>
						<tr>
							<th>Pangkalan</th>
							@foreach (Helper::getThisWeekDateArray() as $day)
								<th>{{ Helper::indonesianDate($day, 'D (j)', '') }}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
					@foreach ($db['agen']->pangkalans as $p)
						<tr data-id="{{ $p->id }}"
							@if($p->dailyDistributions->first())
								@if($p->dailyDistributions->first()->allocation_week == ($p->quota_daily * 6))
									data-planned
								@endif
							@endif 
						>
							<td>{{ $p->name }}</td>
							@foreach (Helper::getThisWeekDateArray() as $day)
								<td class="day">
								@foreach ($p->dailyDistributions as $d)
									@if($d->date_planned == $day)
										<span class="allocation">
											{{ $d->allocation }}
											@if($d->allocated)
												/ {{ $d->allocated }}
											@endif
										</span>
									@endif
								@endforeach
								</td>
							@endforeach
						</tr>
					@endforeach
					</tbody>
				</table>

			</div>
			<div class="box-footer">
				<button id="edit-mode-on" type="button" class="btn btn-primary btn-flat"><i class="fa fa-file-text-o"></i> Tambah Rencana</button>
				<button type="submit" class="btn btn-primary btn-flat hidden"><i class="fa fa-send"></i> Simpan</button>
				<button id="edit-mode-off" type="button" class="btn btn-link hidden"><i class="fa fa-close"></i> Batal</button>
				<a href="{{ url('admin/pangkalan/create?agen='. $db['agen']->id) }}" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> Tambah Pangkalan</a>
				<a href="{{ url('admin/daily/this-week?agen='. $db['agen']->id) }}" class="btn btn-link pull-right"><i class="fa fa-th"></i> Lihat Semua</a>
			</div>
		</form>

	</div>

	<div class="col-xs-12">
		<a href="{{ url('admin/agen/'. $db['agen']->id . '/edit') }}" class="btn btn-lg btn-warning"><i class="fa fa-edit" style="margin-right:10px"></i> Ubah Agen</a>
		&nbsp;<a href="#modal-delete" class="btn btn-lg btn-danger" data-toggle="modal"><i class="fa fa-trash" style="margin-right:10px"></i> Hapus Agen</a>
		<a href="{{ url('admin/agen') }}" class="btn btn-lg btn-link">Kembali</a>
	</div>

</div>

<!-- Delete Modal -->
<div id="modal-delete" class="modal modal-danger fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus Agen?</h4>
			</div>
			<div class="modal-body">
				<p>Apakah benar Anda akan menghapus agen ini?</p>
			</div>
			<form id="form-agen-delete" class="modal-footer" method="post" action="{{ url('api/agen/'. $db['agen']->id) }}" data-callback="{{ url('admin/agen') }}">
				{!! csrf_field() !!}
				{!! method_field('DELETE') !!}
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
<script src="{!! asset('js/app/admin/agen/show.js') !!}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo&callback=initMap" async defer></script>
@endsection
