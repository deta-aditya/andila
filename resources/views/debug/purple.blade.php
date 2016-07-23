@extends('layouts.app')

@section('page')
<div class="container">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Tester Page</h1>
            <p>Do not use any link in this page!</p>

            <div class="row">

                <div class="col-md-3 col-sm-6">
                    <h3>Sentinel Tester: Register</h3>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="email" value="detaditya@pertamina.dist">
                        <input type="hidden" name="password" value="detaditya">
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-send"></i>Run
                        </button>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h3>Sentinel Tester: Login</h3>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="email" value="detaditya@pertamina.dist">
                        <input type="hidden" name="password" value="detaditya">
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-send"></i>Run
                        </button>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h3>Sentinel Tester: Login with ID</h3>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="1">
                        <input type="hidden" name="password" value="detaditya">
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-send"></i>Run
                        </button>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h3>Sentinel Tester: Logout</h3>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/logout') }}">
                        {!! csrf_field() !!}

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-send"></i>Run
                        </button>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h3>Sentinel Tester: Register With Role</h3>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('user') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="email" value="admin@pertamina.dist">
                        <input type="hidden" name="password" value="purplebubblegum">
                        <input type="hidden" name="first_name" value="Admin">
                        <input type="hidden" name="last_name" value="Admin">
                        <select class="form-control" name="role">
                            <option value="admin">Administrator</option>
                            <option value="agen">Agen</option>
                            <option value="pangkalan">Pangkalan</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-send"></i>Run
                        </button>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h3>Sentinel Tester: Store Role</h3>
                    <form id="purple-store-role" class="form-horizontal" role="form" method="POST" action="{{ url('route') }}">
                        {!! csrf_field() !!}
                        <select class="form-control" name="name" required>
                            <option value="null" selected>-- Please Choose --</option>
                            <option value="Administrator">Administrator</option>
                            <option value="Agen">Agen</option>
                            <option value="Pangkalan">Pangkalan</option>
                        </select>
                        <input class="form-control" type="text" name="slug" readonly required>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-btn fa-send"></i>Run (BE CAREFUL!)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
