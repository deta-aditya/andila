@inject('users', 'App\Repositories\UserRepository')

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="app-location" content="{{ url('/') }}">
<meta name="api-location" content="{{ url('api/v0/') }}">

@if (isset($user))
<meta name="access-token" content="{{ $users->getAccessToken($user)['token'] }}">
<meta name="access-expiry" content="{{ $users->getAccessToken($user)['expires'] }}">
@endif

@hasSection('meta')
	@yield('meta')
@endif

<title>
	@hasSection('title')
		@yield('title') - Andila
	@else
		Andila - Aplikasi Integrasi Distribusi LPG Pertamina
	@endif
</title>

<link rel="stylesheet" href="{!! asset('css/bootstrap.min.css') !!}">
<link rel="stylesheet" href="{!! asset('css/AdminLTE.min.css') !!}">
<link rel="stylesheet" href="{!! asset('css/skins/_all-skins.min.css') !!}">

<link rel="stylesheet" href="{!! asset('css/app.css') !!}">

@hasSection('styles')
	@yield('styles')
@endif

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>

<body class="hold-transition @if(isset($user) and $user->isAdmin()) {{ 'skin-black' }} @else {{ 'skin-red' }} @endif {{ $layout or 'fixed' }} {{ $bodyClass or 'bs19-body-default' }}">
    
@yield('page')

<script src="{!! asset('js/jquery-2.1.4.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/AdminLTE.min.js') !!}"></script>

<script src="{!! asset('vendor/slimScroll/jquery.slimscroll.min.js') !!}"></script>
<script src="{!! asset('vendor/momentjs/moment-with-locales.min.js') !!}"></script>
<script src="{!! asset('vendor/numeraljs/numeral.min.js') !!}"></script>

<script src="{!! asset('js/andila.js') !!}"></script>

@hasSection('scripts')
	@yield('scripts')
@endif

<script src="{!! asset('js/debug.js') !!}"></script>

</body>
</html>
