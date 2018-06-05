@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.job list') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="/job/{{ $posts[0]->title }}?i={{ $posts[0]->identity }}">
                    {{ __('admin.view') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/admin/jobs">
                    {{ __('admin.return') }}
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#job-list" role="tab" aria-controls="job-list" aria-selected="true">
                    {{ __('admin.list') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#job-add" role="tab" aria-controls="job-add" aria-selected="false">
                    {{ __('admin.job add') }}
                </a>
            </li>
        @endif
    </ul>
</div>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade {{ !request('id')? 'show active' : '' }}" id="job-list" role="tabpanel" aria-labelledby="job-list-tab">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>标题</th>
                        <th>管理员</th>
                        <th>添加日期</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td class="post-td">
                                <div><a href="?id={{ $post->id }}">{{ $post->title }}</a></div>
                                <ul class="list-inline m-0 px-0 invisible">
                                    <li class="list-inline-item">
                                        <a class="text-muted" target="_blank" href="/job/{{ str_slug($post->title) }}?i={{ $post->identity }}">{{ __('admin.view') }}</a>
                                    </li>
                                    <li class="list-inline-item">
                                        <form action="/admin/job/delete/{{ $post->id }}" onsubmit="onSubmit(event)">
                                            @method('DELETE')
                                            <button type="submit" class="text-muted delete btn btn-link btn-sm">{{ __('admin.delete') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                            <td>{{ $post->creator->name }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="job-add" role="tabpanel" aria-labelledby="job-add-tab">
        @if(session('updated'))
            <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
        @endif
        <form method="POST" action="/admin/post/{{ request('id')? 'update/'.request('id') : 'add' }}" autocomplete="off">
            @csrf
            <div class="form-group">
                <label class="col-form-label">{{ __('admin.job title') }}</label>

                <input type="text" class="form-control" name="title" value="{{ request('id')? $posts[0]->title: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.job desc') }}</label>

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.css">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.js"></script>
                <input id="description" type="hidden" name="description" value="{{ request('id')? $posts[0]->description: '' }}" required>
                <trix-editor input="description"></trix-editor>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="col-form-label">{{ __('admin.company') }}</label>

                    <select class="form-control" name="company_id">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('id') && ($posts[0]->company_id == $company->id)? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <label class="col-form-label">{{ __('admin.catagory') }}</label>
                    <select class="form-control" name="catagory_id">
                        @foreach($catagories as $catagory)
                            <option value="{{ $catagory->id }}" {{ request('id') && ($posts[0]->catagory_id == $catagory->id)? 'selected' : '' }}>{{ $catagory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="col-form-label">{{ __('admin.job location') }}</label>
                    <div class="row">
                        <div class="col">
                            <select class="form-control" name="state" onchange="chooseState(event)">
                                <option value="">{{ __('admin.choose a state') }}</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->STATE_CODE }}" {{ request('id') && (ends_with($posts[0]->location, ','.$state->STATE_CODE))? 'selected' : '' }}>{{ $state->STATE_CODE }} - {{ $state->STATE_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-column">
                                <input class="form-control" name="city" placeholder="{{ __('admin.choose a city') }}" {{ request('id')? '' : 'disabled' }}
                                value="{{ request('id')? explode(',', $posts[0]->location)[0] : '' }}">
                                <div class="alert alert-secondary invisible" role="alert">ff</div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>

                <div class="col-sm-6">
                    <label class="col-form-label">{{ __('admin.job sponsor rate') }}</label>

                    <select class="form-control{{ $errors->has('sponsor_rate') ? ' is-invalid' : '' }}" name="sponsor_rate">
                        <option value="0"></option>
                        <option value="80" {{ request('id') && $posts[0]->sponsor_rate == 80 ? 'selected' : '' }}>80%</option>
                        <option value="65" {{ request('id') && $posts[0]->sponsor_rate == 65 ? 'selected' : '' }}>65%</option>
                        <option value="50" {{ request('id') && $posts[0]->sponsor_rate == 50 ? 'selected' : '' }}>50%</option>
                        <option value="35" {{ request('id') && $posts[0]->sponsor_rate == 35 ? 'selected' : '' }}>35%</option>
                        <option value="20" {{ request('id') && $posts[0]->sponsor_rate == 20 ? 'selected' : '' }}>20%</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="col-form-label">{{ __('admin.job url') }}</label>

                    <input type="url" name="url" 
                    class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" 
                    value="{{ request('id')? $posts[0]->url : old('url') ?: '' }}">
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label class="col-form-label col-sm-12">{{ __('admin.tags') }}</label>
                    <div class="input-group col-sm-3 mb-3">
                        <input type="text" id="new-tag-input" class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" onclick="onClick(event)">{{ __('admin.tag add') }}</button>
                        </div>
                    </div>
                </div>
                <div id="tags-row">
                    @foreach($tags as $tag)
                        <div class="form-check form-check-inline">
                            <input name="tags[]" class="form-check-input" type="checkbox" id="Checkbox{{ $tag->name }}" value="{{ $tag->id }}" {{ request('id') && ($posts[0]->tags->contains($tag->id))? 'checked' : '' }}>
                            <label class="form-check-label" for="Checkbox{{ $tag->name }}">{{ $tag->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <div><label class="col-form-label mb-2">{{ __('admin.job type') }}</label></div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="job_type" id="radio-fulltime" value="Full-time" {{ request('id') && ($posts[0]->job_type == "Full-time")? 'checked' : '' }} required>
                        <label class="form-check-label" for="radio-fulltime">Full Time</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="job_type" id="radio-parttime" value="Part-time" {{ request('id') && ($posts[0]->job_type == "Part-time")? 'checked' : '' }} required>
                        <label class="form-check-label" for="radio-parttime">Part Time</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="job_type" id="radio-internship" value="Internship" {{ request('id') && ($posts[0]->job_type == "Internship")? 'checked' : '' }} required>
                        <label class="form-check-label" for="radio-internship">Internship</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    var cities;
    var state = $('[name=state]').val();

    if(state) {
        $.get('/admin/cities?s=' + state, function(data) {
            cities = data;
        });
    }

    function chooseState(e) {
        state = e.target.value.trim();
        if(state) {
            $.get('/admin/cities?s=' + state, function(data) {
                cities = data;
            });
        }
        $('[name=city]').attr('disabled', false);
        $('[name=city]').val('');
        $('[name=city]').siblings('.alert').addClass('invisible').text('');
    }

    function onClick(e) {
        $.ajax('/admin/tag/add', {
            type: 'post',
            data: {name: $('#new-tag-input').val()},
            error: function(data) {
                window.toastr.error(data.responseText);
            },
            success(data) {
                window.toastr.success(data.msg);
                var tag = '<div class="form-check form-check-inline"><input name="tags[]" class="form-check-input" type="checkbox" id="Checkbox'+data.data.name+'" value="'+data.data.id+'" checked><label class="form-check-label" for="Checkbox'+data.data.name+'">'+data.data.name+'</label></div>';
                $('#tags-row').prepend(tag);
                $('#new-tag-input').val('');
            }
        });
    }

    function onSubmit(e) {
        e.preventDefault();
        if(confirm('与此工作关联的申请也会一起删除')) {
            $.post($(e.target).attr('action'), $(e.target).serialize(), function() {
                $(e.target).parents('tr').remove();
                toastr.success('删除成功');
            });
        }
    }

    $('[name=city]').keyup(function() {
        var s = $(this).val().trim();
        if(s) {
            var filteredCities = cities.filter(function(val) {
                return val.CITY.toLowerCase().indexOf(s.toLowerCase()) !== -1;
            });
            if(filteredCities.length) {
                $(this).siblings('.alert').removeClass('invisible').text(filteredCities[0].CITY);
            } else {
                $(this).siblings('.alert').addClass('invisible').text('');
            }
        } else {
            $(this).siblings('.alert').addClass('invisible').text('');
        }
    });

    $('[name=city]').siblings('.alert').click(function() {
        var city = $(this).text();
        if(city != '') {
            $('[name=city]').val(city);
            $(this).addClass('invisible').text('');
        }
    });

    $('td.post-td').mouseover(function() {
        $(this).find('ul').removeClass('invisible');
    }).mouseout(function() {
        $(this).find('ul').addClass('invisible');
    });
</script>
@endsection