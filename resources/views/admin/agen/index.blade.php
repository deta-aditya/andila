@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Agen')
@section('head', 'Lihat Semua Agen')

@section('breadcrumbs')
<li class="active">Agen</li>
@endsection

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<a href="{{ url('admin/agen/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Agen</a>
		<a href="{{ url('admin/dashboard') }}" class="btn btn-link pull-right"><i class="fa fa-angle-double-left"></i>&nbsp;Kembali</a>
	</div>
	<div class="box-body">

		<table class="table table-condensed table-striped table-hover bs19-data-table table-clickable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nama Agen</th>
					<th>Telepon</th>
					<th class="hidden-xs hidden-sm hidden-md">Alamat</th>
					<th>Stasiun</th>
					<th>Aktif</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($db['agens'] as $a)
				<tr class="clickable" data-href="{{ url('admin/agen/'. $a->id) }}">
					<td class="agen-id">{{ $a->id }}</td>
					<td class="agen-name">{{ $a->name }}</td>
					<td class="agen-phone">{{ $a->phone or 'N/A' }}</td>
					<td class="agen-address hidden-xs hidden-sm hidden-md">{{ $a->address->street or 'N/A' }}</td>
					<td class="agen-station"><a href="{{ url('admin/station/'. $a->station->id) }}">{{ $a->station->name }}</a></td>
					<td class="agen-active">@if($a->active) Ya @else Tidak @endif</td>
					<td class="agen-actions">
						<a href="{{ url('admin/agen/'. $a->id .'/edit') }}" class="btn btn-xs btn-link text-yellow">
							<i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md">Ubah</span>
						</a>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

	</div>
</div>
@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>

<script src="{!! asset('js/app/admin/index.js') !!}"></script>
<script src="{!! asset('js/app/admin/agen/index.js') !!}"></script>
@endsection
