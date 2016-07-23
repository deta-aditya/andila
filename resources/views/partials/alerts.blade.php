<!-- Error Alerts -->
@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><span class="glyphicon glyphicon-exclamation-sign"></span> Tunggu Sebentar!</h4>
    <p>Sistem mendeteksi kesalahan saat memproses permintaan Anda.</p>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<!-- /Error Alerts -->

<!-- Success Alerts -->
@if (session('success'))
<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><span class="glyphicon glyphicon-ok-sign"></span> Berhasil!</h4>
    <p>{{ session('success') }}</p>
</div>
@endif
<!-- /Success Alerts -->