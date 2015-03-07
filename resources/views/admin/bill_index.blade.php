@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Bill Management</span>
    </div>
    <div class="col-md-8 main-view">
        <div class="list_layout">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Table</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bills as $bill)
                    <tr>
                        <td>{!! $bill->id !!}</td>
                        <td>{!! $bill->table->table_name !!}</td>
                        <td>${!! $bill->tempAmount() !!}</td>
                        <td>{!! $bill->statusText !!}</td>
                        <td>
                            <a href="/admin/bill/detail/{!! $bill->id !!}"><div class="btn">Detail</div></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
    var conn = new WebSocket('ws://<?=\App\Setting::getIP()?>:<?=\App\Setting::getPort()?>');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };
    conn.onmessage = function(e) {
        console.log(e.data);
    }
</script>
@stop
