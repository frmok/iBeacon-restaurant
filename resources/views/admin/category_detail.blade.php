@extends('admin.layout')
@section('content')
    <div class="main">
        <div class="breadcrumb">
            <span><i class="fa fa-home"></i>Home</span>
            <span>/</span>
            <span>Add Category</span>
        </div>
        <div class="col-md-6 main-view">
            <div class="form-layout">
                <div class="form-title">
                @if($action === 'create')
                Add Category
                @else
                Modify Category
                @endif
                </div>
                @if(isset($msg))
                <div class="msg">
                <i class="fa fa-check"></i> {!! $msg !!}
                </div>
                @endif
                {!! Form::model($category, array('files' => true, 'url' => 'admin/category/'.$action, $category->id)) !!}
                {!! Form::hidden('id') !!}
                @if(!empty($category->category_img))
                <div class="form-thumbnail" style="background: url('/assets/category/{!! $category->category_img !!}')">
                </div>
                @endif
                <div class="form-group first-group">
                {!! Form::label('category_name', 'Category Name', array('class' => 'col-lg-3')) !!}
                {!! Form::text('category_name', $category->category_name, array('class' => 'col-lg-7')) !!}
                </div>
                <div class="form-group">
                {!! Form::label('category_img', 'Category Image', array('class' => 'col-lg-3')) !!}
                <div class="upload-btn col-lg-3">
                    <span>UPLOAD</span>
                    {!! Form::file('category_img', $attributes = array()); !!}
                </div>
                </div>
                <div class="form-bottom">
                    <input class="btn" type="submit">
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop