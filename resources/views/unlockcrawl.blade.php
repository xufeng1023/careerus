@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <form action="/unlockcrawl" method="post">
        {{ csrf_field() }}
            <input type="text" class="form-control mb-3" name="c" required>
            <input type="hidden" name="r" value="{{ urlencode(request('r')) }}">
            <button type="submit" class="btn btn-default">submit</button>
            {{ urlencode(request('r')) }}
        </form>
    </div>
</div>

@endsection