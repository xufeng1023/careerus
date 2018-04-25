@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.applies') }}</h1>
</div>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="job-list" role="tabpanel" aria-labelledby="job-list-tab">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>{{ __('admin.student') }}</th>
                        <th>{{ __('admin.company') }}</th>
                        <th>{{ __('admin.job list') }}</th>
                        <th>{{ __('admin.applied date') }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applies as $apply)
                        <tr>
                            <td>
                                <a href="/admin/user?id={{ $apply->user->id }}">{{ $apply->user->name }}</a>
                            </td>
                            <td>
                                <div>{{ $apply->post->company->name }}</div>
                                <div class="text-muted small">{{ $apply->post->company->hr }} ({{ $apply->post->company->email }})</div>
                            </td>
                            <td>
                                <div>
                                    <a target="_blank" href="{{ $apply->post->link() }}">{{ $apply->post->title }}</a>
                                </div>
                                <div class="text-muted small">{{ $apply->post->jobType() }} ({{ $apply->post->location }})</div>
                            </td>
                            <td>{{ $apply->created_at }}</td>
                            <td class="status">
                                @if($apply->is_applied)
                                    <span class="badge badge-pill badge-success">{{ __('admin.applied') }}</span>
                                @else
                                    {{ __('admin.not applied') }}
                                @endif
                            </td>
                            <td>
                            <a title="{{ __('front.resume download') }}" href="/dashboard/resume/download?r={{ $apply->user->resume }}">
                                <span data-feather="download"></span>
                            </a>
                            </td>
                            <td>
                                @if(!$apply->is_applied)
                                <form method="post" action="/admin/applied/notify/{{ $apply->id }}" onsubmit="onSubmit(event)">
                                    <button type="submit" class="btn btn-light btn-sm" title="{{ __('admin.notify student') }}">
                                        <span data-feather="mail"></span>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade" id="applies-add" role="tabpanel" aria-labelledby="applies-add-tab">
        <form method="POST" action="/admin/applies/add">
            @csrf

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.applies name') }}</label>

                <div>
                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function onSubmit(e) {
        e.preventDefault();
        let uri = e.target.getAttribute('action');
        let fd = new FormData(e.target);
        $.ajax(uri, {
            type: 'post',
            data: fd,
            dataType: 'json',
            processData: false,
            contentType: false,
            success(data) {
                $(e.target).parents('tr').find('td.status').html('<span class="badge badge-pill badge-success">'+data.status+'</span>');
                $(e.target).remove();
            }
        });
    }
</script>
@endsection
