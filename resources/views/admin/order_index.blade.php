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
                    <?
                    $class = '';
                    if($order->order_status === '0'){
                        $class = "new";
                    }
                    ?>
                    <tr id="{!! $order->id !!}" class="{!! $class !!}">
                        <td>{!! $order->bill->table->table_name !!}</td>
                        <td>{!! $order->item->item_name !!}</td>
                        <td>{!! $order->quantity !!}</td>
                        <td id="status-{!! $order->id !!}">{!! $order->status_text !!}</td>
                        <td>{!! $order->created_at !!}</td>
                        <td>
                            @if($order->order_status == 2 || $order->order_status == 3)
                            <a style="display:none" data-id="{!! $order->id !!}" id="orderUpdate" href="#">
                                <div class="btn">
                                </div>
                            </a>
                            @else
                            <a data-id="{!! $order->id !!}" id="orderUpdate" href="#">
                                <div class="btn">
                                    @if($order->order_status == 0)
                                    Receive
                                    @else
                                    Deliver
                                    @endif
                                </div>
                            </a>
                            @endif
                            <a href="/admin/order/detail/{!! $order->id !!}"><div class="btn">Modify</div></a>
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
    //websocket stuff
    var conn = new WebSocket('ws://localhost:9999');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };
    conn.onmessage = function(e) {
        var data = $.parseJSON( e.data );
        console.log(data);
        if(data.action === 'order.create'){
            if ($("tr"+data.order.id).length === 0){
                $('tbody').prepend('<tr id="'+data.order.id+'" class="new"><td>'+data.order.bill.table.table_name+'</td><td>'+data.order.item.item_name+'</td><td>'+data.order.quantity+'</td><td id="status-'+data.order.id+'">Ordered</td><td>'+data.order.created_at+'</td><td><a href="#" data-id="'+data.order.id+'" id="orderUpdate"><div class="btn">Receive</div></a> <a href="/admin/order/detail/'+data.order.id+'"><div class="btn">Modify</div></a></td></tr>');
            }
        }else if(data.action === 'order.update'){
            var id = data.order.id;
            if(data.order.order_status === '0'){
                $('#status-'+id).parent().removeClass().addClass('new');
                $('#status-'+id).parent().find('#orderUpdate').show().find('div').html('Receive');
            }else if(data.order.order_status === '1'){
                $('#status-'+id).parent().removeClass();
                $('#status-'+id).parent().find('#orderUpdate').show().find('div').html('Deliver');
            }else{
                $('#status-'+id).parent().removeClass();
                $('#status-'+id).parent().find('#orderUpdate').hide();
            }
            $('#status-'+id).html(data.order.status_text);
        }
    };
    $('body').on('click', '#orderUpdate', function(e){
        var id = $(this).attr('data-id');
        var status_text = $(this).find('div').text().trim();
        var $this = $(this);
        if(status_text === 'Receive'){
            var order_status = 1;
            var to_status = 'Deliver';
        }else{
            var order_status = 2;
            var to_status = 'Done';
        }
        $.ajax({
          type: "POST",
          url: "/api/order",
          data: {id: id, order_status: order_status},
      });
        e.preventDefault();
    });

</script>
@stop
