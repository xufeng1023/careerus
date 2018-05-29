@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.catagory') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="pill" href="#job-list" role="tab" aria-controls="job-list" aria-selected="true">
                {{ __('admin.list') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="pill" href="#catagory-add" role="tab" aria-controls="catagory-add" aria-selected="false">
                {{ __('admin.job add') }}
            </a>
        </li>
    </ul>
</div>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="job-list" role="tabpanel" aria-labelledby="job-list-tab">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>{{ __('admin.catagory name') }}</th>
                        <th>{{ __('admin.rfe pass rate') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($catagories as $catagory)
                        <tr>
                            <td>{{ $catagory->name }}</td>
                            <td>{{ $catagory->rfe }}</td>
                            <td>
                                <form action="/admin/category/delete/{{ $catagory->id }}" onsubmit="onSubmit(event)">
                                    @method('DELETE')
                                    <button type="submit" class="text-muted delete btn btn-link btn-sm">{{ __('admin.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade" id="catagory-add" role="tabpanel" aria-labelledby="catagory-add-tab">
        <form method="POST" action="/admin/catagory/add">
            @csrf

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.catagory name') }}</label>

                <div>
                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.rfe pass rate') }}</label>

                <div>
                    <select class="form-control{{ $errors->has('rfe') ? ' is-invalid' : '' }}" name="rfe" required>
                        <option value=""></option>
                        <option value="100">100%</option>
                        <option value="95">95%</option>
                        <option value="80">80%</option>
                        <option value="65">65%</option>
                        <option value="50">50%</option>
                    </select>

                    @if ($errors->has('rfe'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('rfe') }}</strong>
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
        if(confirm('与此领域关联的工作也会一起删除')) {
            $.post($(e.target).attr('action'), $(e.target).serialize(), function() {
                $(e.target).parents('tr').remove();
                toastr.success('删除成功');
            });
        }
    }
</script>
@endsection
