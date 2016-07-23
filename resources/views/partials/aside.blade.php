<aside class="main-sidebar">
	<section class="sidebar">

		<!-- User Panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>@if($user->isAdmin()) {{ $user->email }} @else {{ $user->handleable->name }} @endif</p>
				<a href="#">{{ $user->handling }}</a>
			</div>
		</div>
		<!-- /User Panel -->

		<!-- Menu -->
		<ul class="sidebar-menu">
			<li class="header">MENU UTAMA</li>

			@unless($user->isSubagent())
			<li class="@if(Request::is('dashboard')) {{ 'active' }} @endif"><a href="{{ route('web.dashboard.index') }}"><i class="fa fa-dashboard"></i> <span>Dasbor</span></a></li>
			@endunless

			@if($user->isAdmin())

			<li class="treeview @if(Request::is('users*')) {{ 'active' }} @endif">
				<a href="#"><i class="fa fa-user"></i> <span>Pengguna</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class="@if(Request::is('users')) {{ 'active' }} @endif"><a href="{{ url('users') }}"><i class="fa fa-th"></i> <span>Lihat</span></a></li>
					<li class="@if(Request::is('users/create')) {{ 'active' }} @endif"><a href="{{ url('users/create') }}"><i class="fa fa-plus"></i> <span>Tambah</span></a></li>
				</ul>
			</li>

			<li class="treeview @if(Request::is('stations*')) {{ 'active' }} @endif">
				<a href="#"><i class="fa fa-industry"></i> <span>Stasiun</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class="@if(Request::is('stations')) {{ 'active' }} @endif"><a href="{{ route('web.stations.index') }}"><i class="fa fa-th"></i> <span>Lihat</span></a></li>
					<li class="@if(Request::is('stations/create')) {{ 'active' }} @endif"><a href="{{ route('web.stations.create') }}"><i class="fa fa-plus"></i> <span>Tambah</span></a></li>
				</ul>
			</li>

			<li class="treeview @if(Request::is('agents*')) {{ 'active' }} @endif">
				<a href="#"><i class="fa fa-truck"></i> <span>Agen</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class="@if(Request::is('agents')) {{ 'active' }} @endif"><a href="{{ route('web.agents.index') }}"><i class="fa fa-th"></i> <span>Lihat</span></a></li>
					<li class="@if(Request::is('agents/create')) {{ 'active' }} @endif"><a href="{{ route('web.agents.create') }}"><i class="fa fa-plus"></i> <span>Tambah</span></a></li>
				</ul>
			</li>

			<li class="treeview @if(Request::is('subagents*')) {{ 'active' }} @endif">
				<a href="#"><i class="fa fa-shopping-cart"></i> <span>Subagen</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class="@if(Request::is('subagents')) {{ 'active' }} @endif"><a href="{{ url('subagents') }}"><i class="fa fa-th"></i> <span>Lihat</span></a></li>
					<li class="@if(Request::is('subagents/create')) {{ 'active' }} @endif"><a href="{{ url('subagents/create') }}"><i class="fa fa-plus"></i> <span>Tambah</span></a></li>
				</ul>
			</li>

			<li class="@if(Request::is('schedules')) {{ 'active' }} @endif"><a href="{{ url('schedules') }}"><i class="fa fa-calendar"></i> <span>Jadwal</span></a></li>

			<li class="@if(Request::is('reports')) {{ 'active' }} @endif"><a href="{{ url('reports') }}"><i class="fa fa-book"></i> <span>Laporan</span></a></li>

			@elseif($user->isAgent())

			<li class="treeview @if(Request::is('subagents*')) {{ 'active' }} @endif">
				<a href="#"><i class="fa fa-shopping-cart"></i> <span>Subagen</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li class="@if(Request::is('subagents')) {{ 'active' }} @endif"><a href="{{ url('subagents') }}"><i class="fa fa-th"></i> <span>Lihat</span></a></li>
					<li class="@if(Request::is('subagents/create')) {{ 'active' }} @endif"><a href="{{ url('subagents/create') }}"><i class="fa fa-plus"></i> <span>Tambah</span></a></li>
				</ul>
			</li>

			<li class="@if(Request::is('orders')) {{ 'active' }} @endif"><a href="{{ url('orders') }}"><i class="fa fa-cube"></i> <span>Pesanan</span></a></li>
			<li class="@if(Request::is('subschedules')) {{ 'active' }} @endif"><a href="{{ url('subschedules') }}"><i class="fa fa-truck"></i> <span>Penyaluran</span></a></li>
			<li class="@if(Request::is('reports')) {{ 'active' }} @endif"><a href="{{ url('reports') }}"><i class="fa fa-book"></i> <span>Laporan</span></a></li>

			@elseif($user->isSubagent())

			<li class="@if(Request::is('reports')) {{ 'active' }} @endif"><a href="{{ url('reports') }}"><i class="fa fa-book"></i> <span>Pelaporan</span></a></li>

			@endif
		</ul>
		<!-- /Menu -->

	</section>
</aside>
