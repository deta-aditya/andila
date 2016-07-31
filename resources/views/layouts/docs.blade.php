@extends('layouts.app')

@section('page')
<div class="container">

  <div class="box box-danger docs-box">

    <div class="box-header with-border">
      
      <h1 class="box-title">
        Andila
        <small>
          / Panduan
          @hasSection('breadcrumbs')
             / @yield('breadcrumbs')
          @endif
        </small>

        <div class="pull-right">
          <a href="{{ route('web.default') }}" class="btn btn-link "><i class="fa fa-angle-double-left"></i> Kembali</a>
        </div>
      </h1>

    </div>

    <div class="box-body no-padding">

      <div class="row">
        <div class="col-sm-3">
          <ul class="nav nav-pills nav-stacked docs-nav">
            @if($category === 'admin')
            <li @if($page === 'overview') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'overview']) }}">Ikhtisar</a></li>
            <li @if($page === 'login') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'login']) }}">Login</a></li>
            <li @if($page === 'dashboard') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'dashboard']) }}">Dasbor</a></li>
            <li @if($page === 'users') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'users']) }}">Pengguna</a></li>
            <li @if($page === 'stations') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'stations']) }}">Stasiun</a></li>
            <li @if($page === 'agents') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'agents']) }}">Agen</a></li>
            <li @if($page === 'subagents') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'subagents']) }}">Subagen</a></li>
            <li @if($page === 'schedules') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'schedules']) }}">Jadwal</a></li>
            <li @if($page === 'reports') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'reports']) }}">Laporan</a></li>
            <li @if($page === 'errors') class="active" @endif ><a href="{{ route('web.docs.read', ['admin', 'errors']) }}">Pesan Error</a></li>
            @elseif($category === 'agent')
            <li @if($page === 'overview') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'overview']) }}">Ikhtisar</a></li>
            <li @if($page === 'login') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'login']) }}">Login</a></li>
            <li @if($page === 'subagents') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'subagents']) }}">Subagen</a></li>
            <li @if($page === 'orders') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'orders']) }}">Pesanan</a></li>
            <li @if($page === 'subschedules') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'subschedules']) }}">Penyaluran</a></li>
            <li @if($page === 'reports') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'reports']) }}">Laporan</a></li>
            <li @if($page === 'profile') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'profile']) }}">Profil</a></li>
            <li @if($page === 'errors') class="active" @endif ><a href="{{ route('web.docs.read', ['agent', 'errors']) }}">Pesan Error</a></li>
            @elseif($category === 'subagent')
            <li @if($page === 'overview') class="active" @endif ><a href="{{ route('web.docs.read', ['subagent', 'overview']) }}">Ikhtisar</a></li>
            <li @if($page === 'login') class="active" @endif ><a href="{{ route('web.docs.read', ['subagent', 'login']) }}">Login</a></li>
            <li @if($page === 'reports') class="active" @endif ><a href="{{ route('web.docs.read', ['subagent', 'reports']) }}">Laporan</a></li>
            <li @if($page === 'profile') class="active" @endif ><a href="{{ route('web.docs.read', ['subagent', 'profile']) }}">Profil</a></li>
            <li @if($page === 'errors') class="active" @endif ><a href="{{ route('web.docs.read', ['subagent', 'errors']) }}">Pesan Error</a></li>
            @elseif($category === 'api')

            @endif
          </ul>
        </div>

        <div class="col-sm-9">
          <div class="docs-content">
            @yield('content')
          </div>
        </div>
      </div>

    </div>

  </div>

  <p style="margin-bottom:25px;" class="text-center"><strong>&copy; 2016 <a href="https://facebook.com/deta.aditya">Muhammad Deta Aditya</a>.</strong> Panduan ini merupakan bagian dari Andila.</p>

</div>
@endsection
