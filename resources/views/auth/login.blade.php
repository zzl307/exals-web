@extends('common.login')

@section('content')
    <div class="box">
        <div class="content">
            <form class="form-vertical login-form" action="{{ route('login') }}" method="post">
                {{ csrf_field() }}

                <h3 class="form-title">
                    登录
                </h3>
                <div class="alert fade in alert-danger" style="display: none;">
                    <i class="icon-remove close" data-dismiss="alert">
                    </i>
                    输入任何用户名密码进入.
                </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="input-icon">
                        <i class="icon-user">
                        </i>
                        <input id="email" type="text" name="email" class="form-control" placeholder="用户名" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="input-icon">
                        <i class="icon-lock">
                        </i>
                        <input id="password" type="password" name="password" class="form-control" placeholder="密码" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-actions">
                    <label class="checkbox pull-left">
                        <input type="checkbox" class="uniform" name="remember" { old('remember') ? 'checked' : '' }}>
                        记住密码
                    </label>
                    <button type="submit" class="submit btn btn-primary pull-right">
                        登录
                        <i class="icon-angle-right">
                        </i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop