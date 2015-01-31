@extends('admin.layout')
@section('content')
    <div class="main">
        <div class="breadcrumb">
            <span><i class="fa fa-home"></i>Home</span>
            <span>/</span>
            <span>Add Table</span>
        </div>
        <div class="col-md-6 main-view">
            <div class="form-layout">
                <div class="form-title">
                @if($action === 'create')
                Add Table
                @else
                Modify Table
                @endif
                </div>
                @if(isset($msg))
                <div class="msg">
                <i class="fa fa-check"></i> {!! $msg !!}
                </div>
                @endif
                {!! Form::model($table, array('files' => true, 'url' => 'admin/table/'.$action, $table->id)) !!}
                {!! Form::hidden('id') !!}
                <div class="form-group first-group">
                {!! Form::label('table_name', 'Table Name', array('class' => 'col-lg-3')) !!}
                {!! Form::text('table_name', $table->table_name, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-group">
                {!! Form::label('capacity', 'Capacity', array('class' => 'col-lg-3')) !!}
                {!! Form::text('capacity', $table->capacity, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-group">
                {!! Form::label('major', 'iBeacon Major', array('class' => 'col-lg-3')) !!}
                {!! Form::text('major', $table->major, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-group">
                {!! Form::label('minor', 'iBeacon Minor', array('class' => 'col-lg-3')) !!}
                {!! Form::text('minor', $table->minor, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-bottom">
                    <input class="btn" type="submit">
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop