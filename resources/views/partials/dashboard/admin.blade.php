
@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

<div class="row">

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="small-box bg-red">
			<div class="inner">
				<h3>{{ $stationCount }}</h3>
				<p>Stasiun Terdaftar</p>
			</div>
			<div class="icon">
				<i class="fa fa-industry"></i>
			</div>
			<div class="small-box-footer">
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="small-box bg-primary">
			<div class="inner">
				<h3>{{ $agentCount }}</h3>
				<p>Agen Terdaftar</p>
			</div>
			<div class="icon">
				<i class="fa fa-truck"></i>
			</div>
			<div class="small-box-footer">
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6 col-xs-12">
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

	<div class="col-md-3 col-sm-6 col-xs-12">
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
	</div>

</div>

<div class="row">

	<div class="col-sm-8">

		<div id="andila-quicklinks-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Navigasi Cepat</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<a href="{{ route('web.users.index') }}" class="btn btn-app">
					<i class="fa fa-user"></i> Pengguna
				</a>
				<a href="{{ route('web.stations.index') }}" class="btn btn-app">
					<i class="fa fa-industry"></i> Stasiun
				</a>
				<a href="{{ route('web.agents.index') }}" class="btn btn-app">
					<i class="fa fa-truck"></i> Agen
				</a>
				<a href="{{ route('web.subagents.index') }}" class="btn btn-app">
					<i class="fa fa-shopping-cart"></i> Subagen
				</a>
				<a href="{{ route('web.schedules.index') }}" class="btn btn-app">
					<i class="fa fa-calendar"></i> Jadwal
				</a>
				<a href="{{ route('web.reports.index') }}" class="btn btn-app">
					<i class="fa fa-book"></i> Laporan
				</a>
			</div>
		</div>

		<div id="andila-map-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Peta Penyebaran Distributor</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body no-padding">
				<div class="google-map" id="gmap-see-location" style="height:500px"></div>
			</div>
			<div class="overlay">
				<p>Klik untuk membuka peta.</p>
			</div>
		</div>

	</div>

	<div class="col-sm-4">

		<div id="andila-orders-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Pesanan Menunggu Penerimaan</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="input-group">
					<input class="form-control" type="text" data-table="search" data-target="orders" placeholder="Cari...">
					<span class="input-group-addon">
						<span class="fa fa-search"></span>
					</span>
				</div>
			</div>
			<div class="box-body no-padding">
				<table id="andila-orders-datatable" class="table table-condensed table-striped table-hover table-clickable andila-datatable">
					<thead>
						<tr><th>ID</th><th>Tanggal Terjadwal</th><th>Agen</th></tr>
					</thead>
					<tbody>
					@foreach ($pendingOrders as $order)

						<tr class="clickable">
							<td>{{ $order->schedule->id }}</td>
							<td>{{ Helper::indonesianDate($order->schedule->scheduled_date, 'j F Y', false) }}</td>
							<td>{{ $order->schedule->agent->name }}</td>
						</tr>

					@endforeach
					</tbody>
				</table>
			</div>
		</div>

		<div id="andila-subagents-box" class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Subagen Menunggu Aktifasi</h3>
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
						<tr><th>ID</th><th>Nama Subagen</th><th>Dibuat Pada</th></tr>
					</thead>
					<tbody>
					@foreach ($pendingSubagents as $subagent)

						<tr class="clickable">
							<td>{{ $subagent->id }}</td>
							<td>{{ $subagent->name }}</td>
							<td>{{ Helper::indonesianDate($subagent->created_at, 'j F Y H:i:s', false) }}</td>
						</tr>

					@endforeach
					</tbody>
				</table>
			</div>
		</div>

	</div>

</div>

<div id="modal-order" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>				
				<h4><span class="order-scheduled-date">Sebuah Jadwal</span> <small class="order-id"></small></h4>
				<p class="order-allocated">Teralokasi</p>
				<div class="row">
					<div class="col-sm-6">
						<h5>Stasiun</h5>
						<p class="order-station" style="font-weight:bold">Sebuah Stasiun</p>
					</div>
					<div class="col-sm-6">
						<h5>Agen</h5>
						<p class="order-agent" style="font-weight:bold">Sebuah Agen</p>
					</div>
				</div>
			</div>			
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-order-accept" class="col-sm-12 text-center" data-toggle="popover" title="Terima Pesanan?"><i class="fa fa-check"></i> Terima</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-subagent" class="modal fade text-center" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><span class="subagent-name">Sebuah Subagen</span> <small class="subagent-id"></small></h4>
				<p class="subagent-province">Provinsi</p>
			</div>
			<div class="modal-footer">
				<div class="row">
					<a href="#" id="btn-subagent-show" class="col-sm-6 text-center"><i class="fa fa-eye"></i> Detail</a>
					<a href="#" id="btn-subagent-activate" class="col-sm-6 text-center"><i class="fa fa-link"></i> Aktifasi</a>
				</div>
			</div>
		</div>
	</div>
</div>

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/app/inner/dashboard/admin.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyMEwjE39_RGQofFdPDIvVK42CiDK3Eeo"></script>
@endsection
