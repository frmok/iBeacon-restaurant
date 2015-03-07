@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Queue - {!! $name !!}</span>
    </div>
    <div class="col-md-8 main-view">
        <div class="list_layout">
            <table>
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>People</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                    <tr id="{!! $ticket->id !!}">
                        <td>{!! $ticket->ticket_number !!}</td>
                        <td>{!! $ticket->people !!}</td>
                        <td id="status-{!! $ticket->id !!}">{!! $ticket->status_text !!}</td>
                        <td>
                        @if($ticket->ticket_status < 2)
                        <a data-id="{!! $ticket->id !!}" id="ticketUpdate" href="#"><div class="btn">
                                @if($ticket->ticket_status == 0)
                                Dequeue
                                @else
                                Enter
                                @endif</div></a>
                        @endif
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
            var data = $.parseJSON( e.data );
            console.log(data);
            if(data.action === 'ticket.create'){
                if ($("tr"+data.ticket.id).length === 0){
                    $('tbody').append('<tr id="'+data.ticket.id+'"><td>'+data.ticket.ticket_number+'</td><td>'+data.ticket.people+'</td><td id="status-'+data.ticket.id+'">Waiting</td><td><a href="#" data-id="'+data.ticket.id+'" id="tickerUpdate"><div class="btn">Dequeue</div></a></td></tr>');
                }
            }else if(data.action === 'ticket.update'){
                var id = data.ticket.id;
                if(parseInt(data.ticket.ticket_status) === 1){
                    $('#status-'+id).parent().find('#ticketUpdate').show().find('div').html('Enter');
                }else{
                    $('#status-'+id).parent().find('#ticketUpdate').hide();
                }
                $('#status-'+id).html(data.ticket.status_text);
            }
        };
        $('body').on('click', '#ticketUpdate', function(e){
            var id = $(this).attr('data-id');
            var status_text = $(this).find('div').text().trim();
            var $this = $(this);
            if(status_text === 'Dequeue'){
                var ticket_status = 1;
            }else{
                var ticket_status = 2;
            }
            $.ajax({
                type: "POST",
                url: "/api/ticket",
                data: {id: id, ticket_status: ticket_status},
            });
            e.preventDefault();
        });
    </script>
    @stop
