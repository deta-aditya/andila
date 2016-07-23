@extends('layouts.app')

@section('page')

<div class="container-fluid">
	<div class="row">
		
		<div class="col-sm-3">
			<h2>Andila 
				<small> | <a href="{{ url()->previous() }}">Kembali</a></small>
			</h2>
		</div>

		<div class="col-sm-9 text-right">
			<p style="margin-top:20px">Laporan penyaluran pada:</p>
			<table class="table table-condensed pull-right" style="width:auto; display:inline-table">
				<tr>
					@if (! is_null($station)) <th>Stasiun :</th><td>{{ $station->name }}</td> @endif
					@if (! is_null($agent)) <th>Agen :</th><td>{{ $agent->name }}</td> @endif
					@if (! is_null($subagent)) <th>Subagen :</th><td>{{ $subagent->name }}</td> @endif
					@if (! is_null($schedule)) <th>Jadwal :</th><td>{{ Helper::indonesianDate($schedule->scheduled_date, 'j F Y', false) }}</td> @endif
					@if (! is_null($range)) <th>Rentang Waktu :</th><td>{{ Helper::indonesianDate($range[0], 'j F Y', false) }} - {{ Helper::indonesianDate($range[1], 'j F Y', false) }}</td> @endif
				</tr>
			</table>
		</div>

	</div>

	<div class="row">

		<div class="col-sm-12">
			<table class="table table-bordered table-condensed" style="font-size:9pt">
				<tr>
					<th rowspan="3" title="ID Laporan">ID Lap.</th>
					<th rowspan="3" title="ID Pesanan">ID Ord.</th>
					<th rowspan="3">Stasiun</th>
					<th rowspan="3">Agen</th>
					<th rowspan="3">Subagen</th>
					<th rowspan="3" title="Tanggal Terjadwal">Tgl. Terjadwal</th>
					<th rowspan="3" title="Alokasi">Alo.</th>
					<th colspan="5">Penjualan</th>
					<th colspan="2">Stok (Tbg)</th>
					<th rowspan="3" title="Waktu Pelaporan">Wkt. Pelaporan</th>
				</tr>
				<tr>
					<th rowspan="2">Rumah Tangga (Tbg)</th>
					<th rowspan="2">Mikrobisnis (Tbg)</th>
					<th colspan="2">Pengecer</th>
					<th rowspan="2">Total (Tbg)</th>
					<th rowspan="2">Kosong</th>
					<th rowspan="2">Berisi</th>
				</tr>
				<tr>
					<th>Nama</th>
					<th title="Jumlah">Jml. (Tbg)</th>
				</tr>

				@foreach ($reports as $report)

				<tr>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->id }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->subschedule->order->id }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->subschedule->order->schedule->station->name }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->subschedule->order->schedule->agent->name }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->subschedule->subagent->name }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ Helper::indonesianDate($report->subschedule->scheduled_date, 'j F Y', false) }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->allocated_qty }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->sales_household_qty }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->sales_microbusiness_qty }}</td>
					<td>{{ $report->retailers[0]->name }}</td>
					<td>{{ $report->retailers[0]->pivot->sales_qty }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->sales_household_qty + $report->sales_microbusiness_qty + $report->retailers->sum('pivot.sales_qty') }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->stock_empty_qty }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ $report->stock_filled_qty }}</td>
					<td rowspan="{{ $report->retailers->count() }}">{{ Helper::indonesianDate($report->reported_at) }}</td>

				@for ($i = 1; $i < $report->retailers->count(); $i++)
				<tr>
					<td>{{ $report->retailers[$i]->name }}</td>
					<td>{{ $report->retailers[$i]->pivot->sales_qty }}</td>
				</tr>
				@endfor

				</tr>

				@endforeach

			</table>
		</div>

	</div>

</div>

@endsection