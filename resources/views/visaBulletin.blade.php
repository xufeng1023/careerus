@extends('layouts.app')

@section('title')
{{ '绿卡排期 -' }}
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