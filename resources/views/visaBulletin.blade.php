@extends('layouts.app')

@section('title')
{{ 'visa bulletin -' }}
@endsection

@section('style')
    <style>
        .table th, .table td {padding: 0.75rem 7px !important;}
        h3{font-size:18px !important;}
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            @include('_greenCardTable')
        </div>
    </div>
</div>
@endsection