@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.applies') }}</h1>
</div>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="job-list" role="tabpanel" aria-labelledby="job-list-tab">
        <div class="table-responsive">
            <applies-table></applies-table>
        </div>
    </div>

    <!-- <div class="tab-pane fade" id="applies-add" role="tabpanel" aria-labelledby="applies-add-tab">
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
    </div> -->
</div>
@endsection

@section('script')
<script>
    // function onSubmit(e) {
    //     e.preventDefault();
    //     let uri = e.target.getAttribute('action');
    //     let fd = new FormData(e.target);
    //     $.ajax(uri, {
    //         type: 'post',
    //         data: fd,
    //         dataType: 'json',
    //         processData: false,
    //         contentType: false,
    //         success(data) {
    //             $(e.target).parents('tr').find('td.status').html('<span class="badge badge-pill badge-success">'+data.status+'</span>');
    //             $(e.target).remove();
    //         }
    //     });
    // }
</script>
@endsection
