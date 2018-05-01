@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.company') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
            <li class="nav-item">
                <a class="nav-link active" href="/admin/company">
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
                <a class="nav-link" data-toggle="pill" href="#company-add" role="tab" aria-controls="company-add" aria-selected="false">
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
                        <th>{{ __('admin.company name') }}</th>
                        <th>{{ __('admin.company hr') }}</th>
                        <th>{{ __('admin.company email') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td><a href="?id={{ $company->id }}">{{ $company->name }}</a></td>
                            <td>{{ $company->hr }}</td>
                            <td>{{ $company->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="company-add" role="tabpanel" aria-labelledby="company-add-tab">
        @if(session('updated'))
            <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
        @endif
        <form method="POST" class="mb-3" action="/admin/company/{{ request('id')? 'update/'.request('id') : 'add' }}">
            @csrf

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.company name') }}</label>

                <input type="text" class="form-control" name="name" value="{{ request('id')? $companies[0]->name: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.company hr') }}</label>

                <input type="text" class="form-control" name="hr" value="{{ request('id')? $companies[0]->hr: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.company email') }}</label>

                <input type="email" class="form-control" name="email" value="{{ request('id')? $companies[0]->email: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.company website') }}</label>

                <input type="text" class="form-control" name="website" value="{{ request('id')? $companies[0]->website: '' }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
        </form>

        @if(request('id'))
            <div class="card text-white bg-dark">
                <div class="card-header">{{ __('admin.company visa history') }}</div>
                <div class="card-body">
                    <form class="row" method="post" action="/admin/company/{{ request('id') }}/visajob" onsubmit="onSubmit(event)">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="col-form-label">{{ __('admin.year') }}</label>

                                <select v-model="form.year" class="form-control" name="year" value="{{ request('id')? $companies[0]->email: '' }}">
                                    <option value=""></option>
                                    @for($i = date('Y'); $i > date('Y') - 10; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="col-form-label">{{ __('admin.company numberOfVisa') }}</label>

                                <input v-model="form.numberOfVisa" type="number" class="form-control" name="number_of_visa">
                            </div>

                            <div class="form-group">
                                <label class="col-form-label">{{ __('admin.company jobs') }}</label>

                                <textarea v-model="form.jobs" class="form-control" name="jobs" rows="5" placeholder="eg: Application Developer(1100)"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-default">{{ __('admin.job add') }}</button>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <table class="table">
                                <tr v-if="visas" v-cloak>
                                    <td v-text="visas.year"></td>
                                    <td v-text="visas.numberOfVisa"></td>
                                    <td v-text="visas.jobs"></td>
                                </tr>
                                @foreach($companies[0]->visaJobs as $visa)
                                    <tr>
                                        <td>{{ $visa->year }}</td>
                                        <td>{{ $visa->number_of_visa }}</td>
                                        <td>{{ $visa->jobs }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    const app = new Vue({
        el: '#app',
        data: {
            visas: '',
            form: {
                year: '',
                numberOfVisa: '',
                jobs: ''
            }
        }
    });

    function onSubmit(e) {
        e.preventDefault();
        $.ajax(e.target.getAttribute('action'), {
            type: 'post',
            data: $(e.target).serialize(),
            error: function(data) {
                window.toastr.error(data.responseText);
            },
            success(data) {
                window.toastr.success(data);
                app.visas = app.form;
                app.form = '';
                e.target.reset();
            }
        });
    }
</script>
@endsection
