@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Pangkalan')
@section('head', 'Lihat Semua Pangkalan')

@section('breadcrumbs')
<li class="active">Pangkalan</li>
@endsection

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<a href="{{ url('admin/pangkalan/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Pangkalan</a>
		<a href="{{ url('admin/dashboard') }}" class="btn btn-link pull-right"><i class="fa fa-angle-double-left"></i>&nbsp;Kembali</a>
	</div>
	<div class="box-body">
		<table class="table table-condensed table-striped table-hover bs19-data-table table-clickable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nama Pangkalan</th>
					<th>Telepon</th>
					<th class="hidden-xs hidden-sm hidden-md">Alamat</th>
					<th>Agen</th>
					<th>Aktif</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($db['pangkalans'] as $p)
				<tr class="clickable" data-href="{{ url('admin/pangkalan/'. $p->id) }}">
					<td class="pangkalan-id">{{ $p->id }}</td>
					<td class="pangkalan-name">{{ $p->name }}</td>
					<td class="pangkalan-phone">{{ $p->phone or 'N/A' }}</td>
					<td class="pangkalan-address hidden-xs hidden-sm hidden-md">{{ $p->address->street or 'N/A' }}</td>
					<td class="pangkalan-agen"><a href="{{ url('admin/agen/'. $p->agen->id) }}">{{ $p->agen->name }}</a></td>
					<td class="pangkalan-active">@if($p->active) Ya @else Tidak @endif</td>
					<td class="pangkalan-actions">
						<a href="{{ url('admin/pangkalan/'. $p->id .'/edit') }}" class="btn btn-xs btn-link text-yellow">
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
<script src="{!! asset('js/app/admin/pangkalan/index.js') !!}"></script>
@endsection
