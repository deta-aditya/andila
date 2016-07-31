@extends('layouts.app')

@section('title', 'Panduan')

@section('page')

@include('partials.front.navbar')

<div class="container-fluid">
    <h1 class="text-center">Panduan</h1>
    <p class="lead text-center">Silahkan pilih kategori panduan di bawah ini.</p>

    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-xs-12">
            <div class="row docs-item-container">
            
                <div class="col-md-3 col-sm-6">
                    <div class="box box-danger docs-item">
                        <div class="box-header with-border">
                            <span class="fa fa-dashboard"></span>
                            <h3 class="box-title">Panduan Administrator</h3>
                        </div>
                        <div class="box-body">
                            Panduan umum Andila untuk Administrator.
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-link btn-block" href="{{ route('web.docs.read', ['admin', 'overview']) }}">
                                Baca <span class="fa fa-angle-double-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3 col-sm-6">
                    <div class="box box-danger docs-item">
                        <div class="box-header with-border">
                            <span class="fa fa-truck"></span>
                            <h3 class="box-title">Panduan Agen</h3>
                        </div>
                        <div class="box-body">
                            Panduan wajib Andila untuk pengguna agen.
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-link btn-block" href="{{ route('web.docs.read', ['agent', 'overview']) }}">
                                Baca <span class="fa fa-angle-double-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3 col-sm-6">
                    <div class="box box-danger docs-item">
                        <div class="box-header with-border">
                            <span class="fa fa-shopping-cart"></span>
                            <h3 class="box-title">Panduan Subagen</h3>
                        </div>
                        <div class="box-body">
                            Panduan wajib Andila untuk pengguna subagen.
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-link btn-block" href="{{ route('web.docs.read', ['subagent', 'overview']) }}">
                                Baca <span class="fa fa-angle-double-right"></span>
                            </a>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3 col-sm-6">
                    <div class="box box-danger docs-item">
                        <div class="box-header with-border">
                            <span class="fa fa-code"></span>
                            <h3 class="box-title">Panduan REST API</h3>
                        </div>
                        <div class="box-body">
                            Panduan untuk menggunakan REST API Andila.
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-link btn-block" href="{{ route('web.docs.read', ['api', 'overview']) }}">
                                Baca <span class="fa fa-angle-double-right"></span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@include('partials.front.footer')

@endsection