<div class="login-box-body">
    
    @if ($message)
    <p class="login-box-msg">Silahkan login untuk lanjut.</p>
    @endif

    <form id="form-login" method="post" action="{{ route('api.v0.users.auth') }}">
        {!! csrf_field() !!}

        <input type="hidden" name="_redirect" value="{{ route('web.auth.login') }}">

        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="email" placeholder="Nama Pengguna/E-mail" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <a href="#modal-forgot-password" data-toggle="modal" class="text-red">Saya Lupa Kata Sandi</a>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-danger btn-block btn-flat">Login</button>
            </div>
        </div>
    </form>
</div>

<div id="modal-forgot-password" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Lupa Kata Sandi?</h4>
            </div>
            <div class="modal-body">
                <p class="lead">Silahkan hubungi administrator untuk mengatur kembali kata sandi Anda.</p>
            </div>
        </div>
    </div>
</div>