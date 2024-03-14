@extends('layouts.mainlayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                   <div class="row">
                       <div class="col-lg-12 text-center">
                            <h4>Register User</h4>
                       </div>
                   </div>
                </div>

                <div class="body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10">
                                        <div class="form-line @error('name') error @enderror">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="" required>
                                        </div>
                                        @error('name')
                                            <label id="name-error" class="error" for="name">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email ID</label>
                                    <div class="col-sm-10">
                                        <div class="form-line @error('email') error @enderror">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="email" value="" required>
                                        </div>
                                        @error('email')
                                            <label id="email-error" class="error" for="email">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <div class="form-line @error('password') error @enderror">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="password" value="" required>
                                        </div>
                                        @error('password')
                                            <label id="password-error" class="error" for="password">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="col-sm-2 control-label">Password Confirm</label>
                                    <div class="col-sm-10">
                                        <div class="form-line">
                                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" placeholder="Password confirm" value="" required>
                                        </div>
                                      
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>


                            </form>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
