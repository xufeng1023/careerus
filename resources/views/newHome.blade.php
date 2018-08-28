@extends('layouts.app')

@section('title')
{{ '提供H1B工作查找并且有HR内推机会 - ' }}
@endsection

@section('description')
<meta name="description" content="每日都有最新的H1B工作介绍,尤其是在纽约和加州这种华人公司较多的大城市,并且可直接向HR递交简历申请并有机会得到内推,同时还有不断更新的原创美国求职攻略可参考.">
@endsection

@section('content')
    <div id="job-list">
        <job-list></job-list>
    </div>
    <div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalTitle">工作申请</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @guest
                    <!-- <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">新用户</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">账号登录</a>
                        </li>
                    </ul> -->
                    @include('_loginForm')

                    @else
                    @include('_applyForm', ['user' => auth()->user()])
                    @endguest
                    <!-- <div class="tab-content">
                        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            @include('_applyForm', ['user' => auth()->user()])
                        </div>
                        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            @include('_loginForm')
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/job-list.js') }}"></script>
<script>
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        $.ajax('/login', {
            data: $(e.target).serialize(),
            type: 'post',
            //dataType: 'html',
            context: $(this),
            error(data) {
                var msg = JSON.parse(data.responseText)
                toastr.error(msg.errors.email[0]);
            },
            success(data) {
                $(this).parent().children().fadeOut(function() {
                    $(this).parent().append(data);
                });
            }
        })
    });

    $('#resume').change(function(e) {
        let file = e.target.files[0];
        $(e.target).siblings('label').text(file.name);
    });

    $('body').on('submit', '#applyForm', function(e) {
        e.preventDefault();
        var job = window.jobToApply;
        $(this).find('input#job').val(job.title);
        $(this).find('input#identity').val(job.identity);
        var form = new FormData(e.target);
        $.ajax('/apply', {
            data: form,
            type: 'post',
            context: $(this),
            contentType: false,
            processData: false,
            error(data) {
                var errors = JSON.parse(data.responseText).errors;
                for(prop in errors) {
                    toastr.error(errors[prop]);
                }
            },
            success(data) {
                location.assign(data)
            }
        })
    });
</script>
@endsection