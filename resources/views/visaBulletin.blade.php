@extends('layouts.app')

@section('title')
{{ '绿卡排期 -' }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-body">
                    @include('_greenCardTable')
                </div>
            </div>
        </div>
    </div>
            
    <div class="row justify-content-center my-5">
        <div class="col-sm-6">
            <form action="/green-card-subscriber" method="post" onsubmit="subscribe(event)">
                <label class="text-center d-block">第一时间收到绿卡排期更新的提醒</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">您的邮箱</span>
                    </div>

                    <input type="email" class="form-control" name="email">
                    <input type="hidden" name="url" value="https://careerus.com">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">订阅</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function subscribe(e) {
    e.preventDefault();
    $.ajax('/green-card-subscriber', {
        type: 'post',
        data: $(e.target).serialize(),
        error: function(data) {
            toastr.error(JSON.parse(data.responseText).errors.email);
        },
        success: function(data) {
            toastr.success(data);
            e.target.reset();
        }
    });
}
</script>
@endsection