@extends('layouts.inner')

@section('title', 'Dasbor')
@section('head', 'Dasbor')

@section('breadcrumbs')
&nbsp;
@endsection

@section('content')

@if($user->isAdmin())

	@include('partials.dashboard.admin')

@elseif($user->isAgent())

	@include('partials.dashboard.agent')

@elseif($user->isSubagent())

	@include('partials.dashboard.subagent')

@endif

@endsection
