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
</head>
<body>
    <div class="login">
        {!! Form::open(array('id' => 'adminLogin')) !!}
        <h1>iBeacon Restaurant</h1>
        @if(isset($wrong))
        <div class="warning"><i class="fa fa-exclamation-triangle"></i>&nbsp;Wrong Credentials</div>
        @endif
        <div class="form-group">
            <label for="email"><i class="fa fa-envelope"></i></label>
            {!! Form::text('email', '', array('id' => 'email', 'placeholder' => 'Email', 'class' => 'col-xs-3')) !!}
        </div>
        <div class="form-group">
            <label for="password"><i class="fa fa-key"></i></label>
            {!! Form::password('password', array('id' => 'password', 'placeholder' => 'Password', 'class' => 'col-xs-3')) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('LOGIN', array('class' => 'btn')) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(document).ready(function(){
            $('#adminLogin').submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: '/mobile/userLogin',
                    data: {
                        email: $('#email').val(),
                        password: $('#password').val()
                    },
                    success: function(data){
                        localStorage.setItem('token', data.token);
                        window.location.replace("/backend");
                    },
                    error: function(data){
                        alert('Wrong password.');
                    }
                });
            });
        });
    </script>
</body>
</html>
