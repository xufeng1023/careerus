@extends('layouts.admin')

@section('style')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.job list') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
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
                        <th>公司</th>
                        <th>添加日期</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr class="{{ $post->recommended? 'table-success' : '' }} toggle-on-hover">
                            <td>
                                <div><a href="?id={{ $post->id }}">{{ $post->title }} / {{ $post->chinese_title }}</a></div>
                                <ul class="list-inline m-0 px-0 invisible">
                                    <li class="list-inline-item">
                                        <a class="text-muted" target="_blank" href="{{ $post->link() }}">{{ __('admin.view') }}</a>
                                    </li>
                                    <li class="list-inline-item">
                                        <form action="/admin/job/delete/{{ $post->id }}" onsubmit="onSubmit(event)">
                                            @method('DELETE')
                                            <button type="submit" class="text-muted p-0 btn btn-link btn-sm">{{ __('admin.delete') }}</button>
                                        </form>
                                    </li>
                                    <li class="list-inline-item">
                                        <form action="/admin/job/recommend/{{ $post->id }}" onsubmit="recommend(event)">
                                            <button type="submit" class="text-muted p-0 btn btn-link btn-sm to-toggle-text">{{ $post->recommended? '取消推荐' : '推荐' }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                            <td>{{ $post->company->name }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="job-add" role="tabpanel" aria-labelledby="job-add-tab">
        <div class="row">
            <div class="col-sm-8">
                <form id="postForm" method="POST" action="/admin/post/{{ request('id')? 'update/'.request('id') : 'add' }}" autocomplete="off">
                    @if(session('updated'))
                        <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
                    @endif
                    @csrf
                    <div class="form-group">
                        <label class="col-form-label">{{ __('admin.job title chinese') }}(优先显示)</label>

                        <input type="text" class="form-control" name="chinese_title" value="{{ request('id')? $posts[0]->chinese_title: '' }}">
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">英文标题(必填)</label>

                        <input type="text" class="form-control" name="title" value="{{ request('id')? $posts[0]->title: '' }}" required>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">中文简介(优先显示)</label>
                        <textarea  class="form-control" name="chinese_description" row="3" required>{{ request('id')? $posts[0]->chinese_description: '' }}</textarea>
                        <!-- <input id="chinese_description" type="hidden" name="chinese_description" value="{{ request('id')? $posts[0]->chinese_description: '' }}" required>
                        <trix-editor input="chinese_description"></trix-editor> -->
                    </div>

                    <!-- <div class="form-group">
                        <label class="col-form-label">{{ __('admin.job desc') }}</label>

                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.css">
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.js"></script>
                        <input id="description" type="hidden" name="description" value="{{ request('id')? $posts[0]->description: '' }}">
                        <trix-editor input="description"></trix-editor>
                    </div> -->

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="col-form-label">{{ __('admin.company') }}</label>

                            <select class="company-select" name="company_id" style="width: 100%" required>
                                @if(request('id'))
                                    <option value="{{ $posts[0]->company->id }}" selected>{{ $posts[0]->company->name }}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label class="col-form-label">HR邮箱</label>
                            <input type="email" class="form-control" name="email" value="{{ request('id')? $posts[0]->company->email: '' }}" id="hr-email" required>
                        </div>

                        <div class="col-sm-4">
                            <label class="col-form-label">公司网站</label>
                            <input type="url" class="form-control" name="website" value="{{ request('id')? $posts[0]->company->website: '' }}" id="website">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <label class="col-form-label">公司地址</label>
                            <input type="text" class="form-control" id="full-address" value="{{ request('id')? $posts[0]->company->fullAddress : '' }}" readonly>
                        </div>

                        <div class="col">
                            <label class="col-form-label">公司简名</label>
                            <input type="text" class="form-control" id="short_name" name="short_name" value="{{ request('id')? $posts[0]->company->short_name : '' }}" required>
                        </div>

                        <!-- <div class="col">
                            <label class="col-form-label">工作转自</label>
                            <input type="text" class="form-control" name="copied_from" value="{{ request('id')? $posts[0]->copied_from : '' }}">
                        </div> -->
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
                                        <input class="form-control" name="city" placeholder="{{ __('admin.choose a city') }}" value="{{ request('id')? explode(',', $posts[0]->location)[0] : '' }}">
                                        <div class="alert alert-secondary invisible" role="alert">ff</div>
                                    </div>
                                    
                                </div>
                            </div>
                            <input type="hidden" name="location">
                        </div>

                        <div class="col-sm-6">
                            <label class="col-form-label">{{ __('admin.catagory') }}</label>
                            <select class="form-control" name="catagory_id">
                                @foreach($catagories as $catagory)
                                    <option value="{{ $catagory->id }}" {{ request('id') && ($posts[0]->catagory_id == $catagory->id)? 'selected' : '' }}>{{ $catagory->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <div class="col-sm-6">
                            <label class="col-form-label">{{ __('admin.job sponsor rate') }}</label>
                            <select class="form-control{{ $errors->has('sponsor_rate') ? ' is-invalid' : '' }}" name="sponsor_rate">
                                <option value="0"></option>
                                <option value="80" {{ request('id') && $posts[0]->sponsor_rate == 80 ? 'selected' : '' }}>80%</option>
                                <option value="65" {{ request('id') && $posts[0]->sponsor_rate == 65 ? 'selected' : '' }}>65%</option>
                                <option value="50" {{ request('id') && $posts[0]->sponsor_rate == 50 ? 'selected' : '' }}>50%</option>
                                <option value="35" {{ request('id') && $posts[0]->sponsor_rate == 35 ? 'selected' : '' }}>35%</option>
                                <option value="20" {{ request('id') && $posts[0]->sponsor_rate == 20 ? 'selected' : '' }}>20%</option>
                            </select>
                        </div> -->
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="col-form-label">{{ __('admin.job url') }}</label>

                            <input type="url" name="url" 
                            class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" 
                            value="{{ request('id')? $posts[0]->url : old('url') ?: '' }}">
                        </div>
                    </div> -->

                    <div class="form-group">
                        <div class="row">
                            <label class="col-form-label col-sm-12">{{ __('admin.tags') }}</label>
                            <div class="input-group col-sm-6 mb-3">
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

                        <!-- <div class="col-sm-3">
                            <label class="col-form-label">申请截止日期</label>

                            <input type="date" class="form-control" name="end_at" value="{{ request('id')? $posts[0]->end_at: '' }}" required>
                        </div> -->
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-4">
                <div class="position-fixed">
                    <div class="form-group">
                        <label class="form-check-label" for="radio-internship">实图</label>
                    </div>
                    <div class="card sample" style="width:337px;">
                        <div class="card-header">
                            <div class="justify-content-between d-flex align-items-center flex-sm-wrap">
                                <h6 class="card-title chinese_title m-0">{{ request('id')? $posts[0]->chinese_title: '' }}</h6>
                                <button class="btn btn-success btn-sm">HR内推</button>
                            </div>
                            
                            <div class="text-muted text-truncate title">{{ request('id')? $posts[0]->title: '' }}</div>
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between fancy-load-more">
                            <p class="card-text chinese_description">{{ request('id')? $posts[0]->chinese_description: '' }}</p>
                            <div>
                                <a href="#">
                                    <div class="text-truncate text-info short_name">{{ request('id')? $posts[0]->company->short_name: '' }}</div>
                                </a>
                                <div class="text-secondary location">{{ request('id')? $posts[0]->company->city.','.$posts[0]->company->state: '' }}</div>
                                <div class="tags">
                                    @if(request('id'))
                                        @foreach($posts[0]->tags as $tag)
                                            <span class="badge badge-pill badge-secondary">{{ $tag->name }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <small class="text-muted job_type">{{ request('id')? $posts[0]->job_type: '' }}</small>
                            <small class="text-muted sponsor_total">{{ request('id')? '近三年H1B人数:'.$posts[0]->company->totalSponsor: '' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/select2.js') }}"></script>
<script>
    var sample = $('.card.sample');
    $(document).ready(function() {
        $('.company-select').select2({
            minimumInputLength: 1,
            ajax: {
                url: '/admin/companies',
                data: function (params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function (data) {
                    var data = $.map(data, function (obj) {
                        obj.text = obj.text || obj.name;
                        return obj;
                    });

                    return {
                        results: data
                    };
                }
            },
        });

        $('.company-select').on('select2:select', function (e) {
            var data = e.params.data;
            $('#hr-email').val(data.email);
            $('#website').val(data.website);
            $('#full-address').val(data.fullAddress);
            $('#short_name').val(data.short_name);
            $('[name=location]').val(data.city+','+data.state);
            sample.find('.short_name').text(data.short_name);
            sample.find('.location').text(data.city+','+data.state);
            sample.find('.sponsor_total').text('近三年H1B人数:'+data.totalSponsor);
        });
    });

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
                sample.find('.tags').append('<span class="badge badge-pill badge-secondary">'+data.data.name+'</span>');
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

    function recommend(e) {
        e.preventDefault();
        $.post($(e.target).attr('action'), $(e.target).serialize(), function() {
            var tr = $(e.target).parents('tr');

            tr.toggleClass('table-success');

            var btn = tr.find('button.to-toggle-text');
            if(btn.text() == '推荐') btn.text('取消推荐');
            else btn.text('推荐');
        });
    }

    $('[name=city]').keyup(function() {
        if(!cities) return false;

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

    $('tr.toggle-on-hover').mouseover(function() {
        $(this).find('ul').removeClass('invisible');
    }).mouseout(function() {
        $(this).find('ul').addClass('invisible');
    });
    
    $('#postForm input[type=text],#postForm textarea').keyup(function() {
        sample.find('.'+$(this).attr('name')).text($(this).val());
    });

    $('#postForm [type=radio]').change(function() {
        sample.find('.'+$(this).attr('name')).text($(this).val());
    });

    $('#postForm [type=checkbox]').change(function() {
        sample.find('.tags').children().remove();
        $('#postForm [type=checkbox]:checked').each(function() {
            sample.find('.tags').append('<span class="badge badge-pill badge-secondary">'+$(this).next().text()+'</span>');
        })
    });
</script>
@endsection