@extends('layouts.blank')

@section('title')
login | 
@parent
@stop

@section('content')
<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<div class="login-panel panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Prosím prihláste sa</h3>
			</div>
			<div class="panel-body">
				{{ Form::open(array('action' => 'AuthController@postLogin', 'method' => 'post', 'id' => 'loginForm')) }}
					@if($errors->any())
		                <div class="alert alert-danger">
		                    <a href="#" class="close" data-dismiss="alert">&times;</a>
		                    {{ implode('', $errors->all('<li class="error">:message</li>')) }}
		                </div>
		            @endif
					<fieldset>
						<div class="form-group">
			                {{ Form::text('username', '', array('class' => 'form-control', 'placeholder'=>'username')) }}
						</div>
						<div class="form-group">
	                		{{ Form::password('password', array('class' => 'form-control', 'placeholder'=>'heslo')) }}
						<div class="checkbox">
							<label>
								<input name="remember" type="checkbox" value="Remember Me">Zapamätať</label>
						</div>
						<!-- Change this to a button or input when using this as a form -->
						{{ Form::submit('Prihlásiť', array('class' => 'btn btn-lg btn-success btn-block')) }}
					</fieldset>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@stop
