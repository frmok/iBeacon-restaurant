@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Bill Detail</span>
    </div>
    <div class="col-md-8 main-view">
        <div class="form-layout bill-layout">
            <div class="form-title">
                Bill Detail (ID: {!! $bill->id !!})
            </div>
            <div class="bill-header">
                <div class="col-xs-3">
                    <div class="bill-header-label">Date</div>
                    <div class="bill-header-value">{!! date('d M Y', strtotime($bill->created_at)) !!}</div>
                </div>
                <div class="col-xs-3">
                <div class="bill-header-label">Table</div>
                    <div class="bill-header-value">{!! $bill->table->table_name !!}</div>
                </div>
                <div class="col-xs-3">
                    <div class="bill-header-label">Amount</div>
                    <div class="bill-header-value">${!! $bill->tempAmount() !!}</div>
                </div>
                <div class="col-xs-3">
                    <div class="bill-header-label">Status</div>
                    <div class="bill-header-value">{!! $bill->status !!}</div>
                </div>
            </div>
            <div class="bill-content">
                <div class="bill-content-header">
                    Item Summary
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->orders as $order)
                        <tr>
                            <td>{!! $order->item->item_name !!}</td>
                            <td>${!! $order->item->price !!}</td>
                            <td>{!! $order->quantity !!}</td>
                            <td>${!! ($order->item->price * $order->quantity) !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bill-footer">
                <div class="col-xs-12">
                    Total: ${!! $bill->tempAmount() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop