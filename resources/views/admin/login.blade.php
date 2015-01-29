<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>iBeacon restaurant admin panel</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!--jQuery 1.11.0-->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.5.1.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.5.1.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="login">
        {!! Form::open(array('url' => 'admin/')) !!}
        <h1>iBeacon Restaurant</h1>
        @if(isset($wrong))
        <div class="warning"><i class="fa fa-exclamation-triangle"></i>&nbsp;Wrong Credentials</div>
        @endif
        <div class="form-group">
            <label for="email"><i class="fa fa-envelope"></i></label>
            {!! Form::text('email', '', array('placeholder' => 'Email', 'class' => 'col-xs-3')) !!}
        </div>
        <div class="form-group">
            <label for="password"><i class="fa fa-key"></i></label>
            {!! Form::password('password', array('placeholder' => 'Password', 'class' => 'col-xs-3')) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('LOGIN', array('class' => 'btn')) !!}
        </div>
        {!! Form::close() !!}
    </div>
</body>
</html>
