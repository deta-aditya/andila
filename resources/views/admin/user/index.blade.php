@extends('layouts.inner')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendor/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('title', 'Lihat Pengguna')
@section('head', 'Lihat Semua Pengguna')

@section('breadcrumbs')
<li class="active">Pengguna</li>
@endsection

@section('content')
<div class="box box-default">
	<div class="box-header with-border">
		<a href="{{ url('admin/user/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Pengguna</a>
		<a href="{{ url('admin/dashboard') }}" class="btn btn-link pull-right"><i class="fa fa-angle-double-left"></i>&nbsp;Kembali</a>
	</div>
	<div class="box-body">

		<table class="table table-condensed table-striped table-hover bs19-data-table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nama Pengguna</th>
					<th class="hidden-xs hidden-sm hidden-md">Nama Awal</th>
					<th class="hidden-xs hidden-sm hidden-md">Nama Akhir</th>
					<th>Peran</th>
					<th>Waktu Bergabung</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($db['users'] as $u)
				<tr class="clickable" data-href="{{ url('admin/user/'. $u->id) }}">
					<td class="user-id">{{ $u->id }}</td>
					<td class="user-username">{{ Helper::userify($u->email) }}</td>
					<td class="user-first_name hidden-xs hidden-sm hidden-md">{{ $u->first_name or 'N/A' }}</td>
					<td class="user-last_name hidden-xs hidden-sm hidden-md">{{ $u->last_name or 'N/A' }}</td>
					<td class="user-role">{{ $u->roles[0]->name }}</td>
					<td class="user-created_at">{{ Helper::indonesianDate($u->created_at, 'j F Y', '') }}</td>
					<td>
						<a href="{{ url('admin/user/'. $u->id .'/edit') }}" class="btn btn-xs btn-link text-yellow">
							<i class="fa fa-edit"></i> <span class="hidden-xs hidden-sm hidden-md">Ubah</span>
						</a>
						@unless($user->id == $u->id)
						<a href="#modal-delete" class="btn btn-xs btn-link text-red" data-toggle="modal">
							<i class="fa fa-trash"></i> <span class="hidden-xs hidden-sm hidden-md">Hapus</span>
						</a>
						@endunless
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

	</div>
</div>

<!-- Delete Modal -->
<div id="modal-delete" class="modal modal-danger fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Hapus Pengguna?</h4>
			</div>
			<div class="modal-body">
				<p>Apakah benar Anda akan menghapus pengguna di bawah ini?</p>
				<p class="modal-desc">Deskripsi..</p>
			</div>
			<form id="form-user-delete" class="modal-footer" method="post" action="user">
				{!! csrf_field() !!}
				{!! method_field('DELETE') !!}
				<input type="hidden" name="action-to" value="{{ url('user/') }}">
				<button type="submit" class="btn btn-default"><i class="fa fa-trash"></i> Benar</button>
				<button type="button" class="btn btn-outline" data-dismiss="modal"><i class="fa fa-remove"></i> Tidak</button>
			</form>			
		</div>
	</div>
</div>
<!-- /Delete Modal -->
@endsection

@section('scripts')
<script src="{!! asset('vendor/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('vendor/datatables/dataTables.bootstrap.min.js') !!}"></script>

<script src="{!! asset('js/app/admin/index.js') !!}"></script>
<script src="{!! asset('js/app/admin/user/index.js') !!}"></script>
@endsection
