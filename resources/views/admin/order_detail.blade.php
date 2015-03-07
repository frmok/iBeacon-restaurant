@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Add Order</span>
    </div>
    <div class="col-md-6 main-view">
        <div class="form-layout">
            <div class="form-title">
                Modify Order
            </div>
            @if(isset($msg))
            <div class="msg">
                <i class="fa fa-check"></i> {!! $msg !!}
            </div>
            @endif
            {!! Form::model($order, array('files' => true, 'url' => 'admin/order/'.$action, $order->id)) !!}
            {!! Form::hidden('id') !!}
            <div class="form-group first-group">
                {!! Form::label('item_id', 'Item', array('class' => 'col-xs-3')) !!}
                {!! Form::select('item_id', $itemOptions, $order->item_id) !!}
            </div>
            <div class="form-group">
                {!! Form::label('quantity', 'Quantity', array('class' => 'col-lg-3')) !!}
                {!! Form::text('quantity', $order->quantity, array('class' => 'col-lg-7')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('order_status', 'Item', array('class' => 'col-xs-3')) !!}
                {!! Form::select('order_status', $statusOptions, $order->order_status) !!}
            </div>
            <div class="form-bottom">
                <input class="btn" type="submit">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop