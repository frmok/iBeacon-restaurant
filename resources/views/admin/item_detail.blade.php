@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Add Item</span>
    </div>
    <div class="col-md-6 main-view">
        <div class="form-layout">
            <div class="form-title">
                @if($action === 'create')
                Add Item
                @else
                Modify Item
                @endif
            </div>
            @if(isset($msg))
            <div class="msg">
                <i class="fa fa-check"></i> {!! $msg !!}
            </div>
            @endif
            {!! Form::model($item, array('files' => true, 'url' => 'admin/item/'.$action, $item->id)) !!}
            {!! Form::hidden('id') !!}
            @if(!empty($item->item_img))
            <div class="form-thumbnail" style="background: url('/assets/item/{!! $item->item_img !!}')">
            </div>
            @endif
            <div class="form-group first-group">
                {!! Form::label('item_name', 'Item Name', array('class' => 'col-lg-3')) !!}
                {!! Form::text('item_name', $item->item_name, array('class' => 'col-lg-7')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('item_img', 'Item Image', array('class' => 'col-lg-3')) !!}
                <div class="upload-btn col-lg-3">
                    <span>UPLOAD</span>
                    {!! Form::file('item_img', $attributes = array()); !!}
                </div>

            </div>
            <div class="form-group">
                {!! Form::label('category_id', 'Category', array('class' => 'col-xs-3')) !!}
                {!! Form::select('category_id', $categories, $item->category_id) !!}
            </div>
            <div class="form-bottom">
                <input class="btn" type="submit">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop