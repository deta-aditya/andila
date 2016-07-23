@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Stasiun')
@section('head', 'Lihat Semua Stasiun')

@section('breadcrumbs')
<li class="active">Stasiun</li>
@endsection

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<a href="{{ url('admin/station/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Stasiun</a>
		<a href="{{ url('admin/dashboard') }}" class="btn btn-link pull-right"><i class="fa fa-angle-double-left"></i>&nbsp;Kembali</a>
	</div>
	<div class="box-body">

		<table class="table table-condensed table-striped table-hover bs19-data-table table-clickable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nama Stasiun</th>
					<th>Telepon</th>
					<th class="hidden-xs hidden-sm hidden-md">Alamat</th>
					<th>Tipe Stasiun</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($db['stations'] as $s)
				<tr class="clickable" data-href="{{ url('admin/station/'. $s->id) }}">
					<td class="station-id">{{ $s->id }}</td>
					<td class="station-name">{{ $s->name }}</td>
					<td class="station-phone">{{ $s->phone or 'N/A' }}</td>
					<td class="station-address hidden-xs hidden-sm hidden-md">{{ $s->address->street or 'N/A' }}</td>
					<td class="station-type">{{ $s->type }}</td>
					<td class="station-actions">
						<a href="{{ url('admin/station/'. $s->id .'/edit') }}" class="btn btn-xs btn-link text-yellow">
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
<script src="{!! asset('js/app/admin/station/index.js') !!}"></script>
@endsection
