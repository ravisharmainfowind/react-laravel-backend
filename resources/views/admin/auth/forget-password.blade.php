<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	 <title>Admin Forget Password | {{ __('message.APP_NAME') }}</title>
	   <link rel="icon" type="image/png" sizes="16x16" href="{{ url('public/favicon.png') }}">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="{{ asset('public/Admin/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ asset('public/Admin/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
	<!-- Ionicons -->
	<link rel="stylesheet" href="{{ asset('public/Admin/AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('public/Admin/AdminLTE/dist/css/AdminLTE.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/Admin/AdminLTE/dist/css/AdminLTE.css') }}">
	<!-- iCheck -->
	<link rel="stylesheet" href="{{ asset('public/Admin/AdminLTE/plugins/iCheck/square/blue.css') }}">

  	<!-- Google Font -->
  	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  	<?php  $locale = \App::getLocale(); ?>
  	<script type="text/javascript" src='{{ url("lang/$locale") }}'></script> 

  	 <!-- jQuery 3 -->
	<script src="{{ asset('public/Admin/AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="{{ asset('public/Admin/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

	<script src="{{ asset('public/Admin/custom-scripts/jquery.validate.min.js') }}"></script>
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="#"><b>{{ __('message.APP_NAME') }}</b></a>
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">{{ __('message.FORGET_PASSWORD') }}</p>

			<form action="{{ url('admin/recover-password') }}" name="forgot-password" id="forgot-password" method="post">
				{{ csrf_field() }}

				@if(Session::has('success'))
                <p style="text-align: center;color: green;">{{Session::get('success')}}</p>
                @endif

                <p class="error help-block" style="text-align: center;color: red;" id="error">
                    @if ($errors->has('error'))
                    <i class="fa fa-times-circle-o"></i> {{ $errors->first('error') }} @endif
                </p>
                
				<div class="form-group has-feedback">
					<input type="text" name="email" id="email" class="form-control" placeholder="{{ __('message.EMAIL') }}">
					@if ($errors->has('email'))
						<p class="error help-block" style="color: red;" id="error">
		                    <i class="fa fa-times-circle-o"></i> {{ $errors->first('email') }} 
		                </p>
	                @endif
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>

				<div class="row">
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('message.SEND_EMAIL') }}</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	
	<!-- iCheck -->
	<script src="{{ asset('public/Admin/AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>

	<script src="{{ asset('public/Admin/custom-scripts/form-validation.js') }}"></script>
	
</body>
</html>
