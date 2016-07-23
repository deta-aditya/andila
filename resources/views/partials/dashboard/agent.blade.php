
@section('meta')
<meta name="agent-id" content="{{ $user->handleable->id }}">
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

<div class="row">

	<div class="col-sm-8">

		<div class="row">

			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ $subagentCount }}</h3>
						<p>Subagen Terdaftar</p>
					</div>
					<div class="icon">
						<i class="fa fa-shopping-cart"></i>
					</div>
					<div class="small-box-footer">
					</div>
				</div>
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3>{{ $orderCount }}</h3>
						<p>Pesanan Terdaftar</p>
					</div>
					<div class="icon">
						<i class="fa fa-cube"></i>
					</div>
					<div class="small-box-footer">
					</div>
				</div>
			</div>

			<div class="col-xs-12">

				<div id="andila-annual-box" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Grafik Rekap Penyaluran per Bulan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body no-padding">
						<canvas id="chart-annual-dist-chart"></canvas>
					</div>
				</div>

				<div id="andila-map-box" class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Peta Penyebaran Subagen</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div>
					<div class="box-body no-padding">
						<div class="google-map" id="gmap-see-location" style="height:300px"></div>
					</div>
					<div class="overlay">
						<p>Klik untuk membuka peta.</p>
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="col-sm-4">

		<div id="andila-quicklinks-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Navigasi Cepat</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<a href="{{ route('web.subagents.index') }}" class="btn btn-app">
					<i class="fa fa-shopping-cart"></i> Subagen
				</a>
				<a href="{{ route('web.orders.index') }}" class="btn btn-app">
					<i class="fa fa-cube"></i> Pesanan
				</a>
				<a href="{{ route('web.subschedules.index') }}" class="btn btn-app">
					<i class="fa fa-truck"></i> Penyaluran
				</a>
				<a href="{{ route('web.reports.index') }}" class="btn btn-app">
					<i class="fa fa-book"></i> Laporan
				</a>
			</div>
		</div>

		<div id="andila-schedules-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Jadwal Menunggu Pesanan</h3>
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
						<tr><th>ID</th><th>Tanggal Terjadwal</th></tr>
					</thead>
					<tbody>
					@foreach ($pendingSchedules as $schedule)

						<tr class="clickable">
							<td>{{ $schedule->id }}</td>
							<td>{{ Helper::indonesianDate($schedule->scheduled_date, 'j F Y', false) }}</td>
						</tr>

					@endforeach
					</tbody>
				</table>
			</div>
		</div>

	</div>

</div>

<div id="modal-order-create" class="modal fade" tabindex="-1">
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

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/dashboard/agent.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection