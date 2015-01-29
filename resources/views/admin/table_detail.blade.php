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
    <nav class="sidebar">
        <ul>
            <li class="cat">Table</li>
            <li>Add Table</li>
            <li>Table Management</li>
            <li class="cat">Bill</li>
            <li>Bill Management</li>
            <li class="cat">MENU</li>
            <li>Add Category</li>
            <li>Add Item</li>
            <li>Category management</li>
            <li>Item management</li>
        </ul>
    </nav>
    <div class="main">
        <div class="breadcrumb">
            <span><i class="fa fa-home"></i>Home</span>
            <span>/</span>
            <span>Add Table</span>
        </div>
        <div class="col-md-6 main-view">
            <div class="form-layout">
                <div class="form-title">
                @if($action === 'create')
                Add Table
                @else
                Modify Table
                @endif
                </div>
                @if(isset($msg))
                <div class="msg">
                <i class="fa fa-check"></i> {!! $msg !!}
                </div>
                @endif
                {!! Form::model($table, array('files' => true, 'url' => 'admin/table/'.$action, $table->id)) !!}
                {!! Form::hidden('id') !!}
                <div class="form-group first-group">
                {!! Form::label('table_name', 'Table Name', array('class' => 'col-lg-3')) !!}
                {!! Form::text('table_name', $table->table_name, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-group">
                {!! Form::label('capacity', 'Capacity', array('class' => 'col-lg-3')) !!}
                {!! Form::text('capacity', $table->capacity, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-bottom">
                    <input class="btn" type="submit">
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</body>
</html>
