@extends('layouts.app')

@section('page')
<div class="wrapper">

	<!-- Header -->
	@include('partials.header')
	<!-- /Header -->

	<!-- Aside -->
	@include('partials.aside')
	<!-- /Aside -->

	<!-- Content Wrapper -->
	<div class="content-wrapper">
		
		<!-- Content Header -->
		<section class="content-header">
			
			<div class="alert-placeholder">
				@include('partials.alerts')
			</div>

			<h1>
				@hasSection('head')
					@yield('head')
				@else
					Sebuah Halaman
				@endif
				<small>
					@hasSection('description')
						@yield('description')					
					@endif
				</small>
			</h1>

			<ol class="breadcrumb">
				<li><a href="{{ route('web.dashboard.index') }}"><i class="fa fa-dashboard"></i> Dasbor</a></li>
				@hasSection('breadcrumbs')
					@yield('breadcrumbs')
				@else
					<li class="active">Halaman Ini</li>
				@endif
			</ol>
		    
		</section>
		<!-- /Content Header -->

		<!-- Content Body -->
		<section class="content">
			@yield('content')
		</section>
		<!-- /Content Body -->

	</div>
	<!-- /Content Wrapper -->

	<!-- Footer -->
	@include('partials.footer')
	<!-- /Footer -->

	<!-- Control -->

	<!-- /Control -->

</div>
@endsection
