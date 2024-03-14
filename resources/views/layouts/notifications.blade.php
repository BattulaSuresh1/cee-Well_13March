<div id="notifications" class="row no-print">
    <div class="col-md-12">
        @if($errors->any())
        <div class="noti-alert pad no-print">
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        <div class="noti-alert pad no-print">
            @foreach (['success', 'danger', 'warning', 'info'] as $msg)
                @if($message = Session::get($msg))
                    <div class="alert alert-{{ $message == 'fail' ? 'danger' : $msg }}">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                    </div>
                    @break
                    @endif
            @endforeach
            @foreach (['fail'] as $msg)
                @if($errorMessage = Session::get($msg))
                    <div class="alert alert-{{ $msg == 'fail' ? 'danger' : $msg }}">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $errorMessage }}</strong>
                    </div>
                    @break
                @endif
            @endforeach
        </div>
    </div>
</div>

