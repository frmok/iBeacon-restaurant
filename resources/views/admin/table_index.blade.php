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
            <span>Table Management</span>
        </div>
        <div class="col-md-8 main-view">
            <div class="list_layout">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Table Name</th>
                            <th>Capacity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tables as $table)
                        <tr>
                            <td>{!! $table->id !!}</td>
                            <td>{!! $table->table_name !!}</td>
                            <td>{!! $table->capacity !!}</td>
                            <td><a href="/admin/table/detail/{!! $table->id !!}"><div class="btn">Modify</div></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
