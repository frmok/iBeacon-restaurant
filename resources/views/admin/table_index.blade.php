@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Table Management</span>
    </div>
    <div class="table-layout main-view">
        <div class="table-control">
            Availbility: 
            <select id="avil_filter">
                <option value="all">All</option>
                <option value="avil">Available</option>
            </select>
            Capacity: 
            <select id="cap_filter">
                <option value="all">All</option>
                <option value="2">2</option>
                <option value="4">4</option>
                <option value="8">8</option>
            </select>
        </div>
        @foreach($tables as $table)
        <?
        $class = 'empty';
        if($table->table_status === '1'){
            $class = 'locked';
        }
        ?>
        <div data-capacity="{!! $table->capacity !!}" class="table-cell {!! $class !!}">
            <div class="table-header"><i class="fa fa-table"></i> {!! $table->table_name !!}</div>
            <div class="table-content">
                <div class="table-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="table-content-text">
                    Capacity: {!! $table->capacity !!}<br />
                    @if($table->table_status === '1')
                    <a href="/admin/bill/detail/{!! $table->openedBill()->id !!}"><div class="btn">Bill</div></a>
                    @endif
                    <a href="/admin/table/detail/{!! $table->id !!}"><div class="btn">Modify</div></a>
                    <a href="/admin/table/delete/{!! $table->id !!}"><div class="btn">Delete</div></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>
@stop

@section('script')
<script>
    $(document).ready(function(){
        $('#avil_filter').change(function(){
            var val = $(this).val();
            $('.table-layout').removeClass('no-empty');
            if(val === 'avil'){
                $('.table-layout').addClass('no-empty');
            }
        });

        $('#cap_filter').change(function(){
            var val = $(this).val();
            if(val === 'all'){
                $('.table-cell').show();
            }else{
                $('.table-cell').hide();
                $('.table-cell[data-capacity="'+val+'"]').show();
            }
        });
    });
</script>
@stop