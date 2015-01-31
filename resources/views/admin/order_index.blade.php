@extends('admin.layout')
@section('content')
    <div class="main">
        <div class="breadcrumb">
            <span><i class="fa fa-home"></i>Home</span>
            <span>/</span>
            <span>Order Management</span>
        </div>
        <div class="col-md-8 main-view">
            <div class="list_layout order_list_layout">
                <table>
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{!! $order->bill->table->table_name !!}</td>
                            <td>{!! $order->item->item_name !!}</td>
                            <td>{!! $order->quantity !!}</td>
                            <td>{!! $order->status_text !!}</td>
                            <td>{!! $order->created_at !!}</td>
                            <td>
                                <a href="/admin/order/detail/{!! $order->id !!}"><div class="btn">Receive</div></a>
                                <a href="/admin/order/detail/{!! $order->id !!}"><div class="btn">Modify</div></a>
                                <a href="/admin/order/delete/{!! $order->id !!}"><div class="btn btn-warning">Delete</div></a>
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
