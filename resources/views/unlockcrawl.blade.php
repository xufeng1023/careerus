@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <form action="/unlockcrawl" method="post">
        {{ csrf_field() }}
            <input type="text" class="form-control mb-3" name="code" required>
            <button type="submit" class="btn btn-default">submit</button>
        </form>
    </div>
</div>

@endsection