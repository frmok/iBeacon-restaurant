@extends('admin.layout')
@section('content')
    <div class="main">
        <div class="breadcrumb">
            <span><i class="fa fa-home"></i>Home</span>
            <span>/</span>
            <span>Category Management</span>
        </div>
        <div class="col-md-8 main-view">
            <div class="list_layout">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>{!! $category->id !!}</td>
                            <td>{!! $category->category_name !!}</td>
                            <td>
                                <a href="/admin/category/detail/{!! $category->id !!}"><div class="btn">Modify</div></a>
                                <a href="/admin/category/delete/{!! $category->id !!}"><div class="btn btn-warning">Delete</div></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
@stop
