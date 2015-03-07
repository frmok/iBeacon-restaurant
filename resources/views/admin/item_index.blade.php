@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Item Management</span>
    </div>
    <div class="col-md-8 main-view">
        <div class="list_layout">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{!! $item->id !!}</td>
                        <td>{!! $item->item_name !!}</td>
                        <td>${!! $item->price !!}</td>
                        <td>
                            <a href="/admin/item/detail/{!! $item->id !!}"><div class="btn">Modify</div></a>
                            <a href="/admin/item/delete/{!! $item->id !!}"><div class="btn btn-warning">Delete</div></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
