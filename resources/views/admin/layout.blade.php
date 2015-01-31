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
            <li><a href="/admin/table/detail">Add Table</a></li>
            <li><a href="/admin/table/">Table Management</a></li>
            <li class="cat">Bill</li>
            <li><a href="/admin/bill/">Bill Management</a></li>
            <li class="cat">Order</li>
            <li><a href="/admin/order/">Order Management</a></li>
            <li class="cat">MENU</li>
            <li><a href="/admin/category/detail">Add Category</a></li>
            <li><a href="/admin/item/detail">Add Item</a></li>
            <li><a href="/admin/category/">Category Management</a></li>
            <li><a href="/admin/item/">Item management</a></li>
        </ul>
    </nav>
    @yield('content')
</body>
</html>
