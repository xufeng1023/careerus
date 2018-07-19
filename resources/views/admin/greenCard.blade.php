@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.green card') }}</h1>
</div>

<div>
    <button type="button" class="btn btn-primary crawl-inventory">{{ __('admin.crawl visa inventory') }}</button>
</div>

<div class="table-responsive mb-5">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th></th><th>EB1</th><th>EB2</th><th>EB3</th><th>EB4</th><th>EB5</th><th>OTHER</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>China</th>
                @foreach($inventories as $i)
                    <td>{{ $i->amount }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<div>
    <button type="button" class="btn btn-primary crawl-visa">{{ __('admin.crawl visa bulletin') }}</button>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>{{ __('admin.visa type') }}</th>
                <th>{{ __('admin.country') }}</th>
                <th>{{ __('admin.filing date') }}</th>
                <th>{{ __('admin.action date') }}</th>
                <th>{{ __('admin.crawl date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bulletins as $bulletin)
                <tr>
                    <td>{{ $bulletin->title }}</td>
                    <td>{{ $bulletin->country }}</td>
                    <td>{{ $bulletin->filing_at }}</td>
                    <td>{{ $bulletin->action_at }}</td>
                    <td>{{ $bulletin->check_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('script')
<script>
    var visa = [];
    //var countires = ['all', 'china', 'india', 'mexico', 'philippines'];
    var countires2 = ['all', 'china', 'el', 'india', 'mexico', 'philippines', 'vietnam'];
    var countires3 = ['all', 'china', 'el', 'india', 'mexico', 'philippines'];

    $('.crawl-visa').click(function() {
        $(this).attr('disabled', true).addClass('loading');
        $.ajax('/admin/visa', {
            context: $(this),
            error: function(data) {
                toastr.warning(data.responseText);
            },
            success: function(data) {
                var html = $.parseHTML(data);
                var tables = $(html).find('table');

                tables = tables.filter(function(index, val) {
                    return $(val).find('tr').length === 9;
                });

                // const familyVisaTableIndex = {'3': 'filing_at', '0': 'action_at'};
                // for(var prop in familyVisaTableIndex) {
                //     var trs = $(tables[prop]).find('tr');
                //     for(var i = 1; i < trs.length; i++) {
                //         var tds = $(trs[i]).find('td');

                //         for(var j = 0; j < countires.length; j++) {
                //             var data = {
                //                 'title': $(tds[0]).text(),
                //                 'country': countires[j],
                //             }
                //             data[familyVisaTableIndex[prop]] = $(tds[j + 1]).text()
                //             visa.push(data);
                //         }
                //     }
                // };
                
                var trs = $(tables[0]).find('tr');
                for(var i = 1; i < trs.length; i++) {
                    var tds = $(trs[i]).find('td');

                    for(var j = 0; j < countires2.length; j++) {
                        if( !['china', 'all'].includes(countires2[j]) ) continue;
                        var data = {
                            'title': $(tds[0]).text(),
                            'country': countires2[j],
                            'action_at': $(tds[j + 1]).text()
                        }
                        visa.push(data);
                    }
                }
                
                var trs = $(tables[1]).find('tr');
                for(var i = 1; i < trs.length; i++) {
                    var tds = $(trs[i]).find('td');

                    for(var j = 0; j < countires3.length; j++) {
                        if( !['china', 'all'].includes(countires3[j]) ) continue;
                        var data = {
                            'title': $(tds[0]).text(),
                            'country': countires3[j],
                            'filing_at': $(tds[j + 1]).text()
                        }
                        visa.push(data);
                    }
                }

                //console.log(visa);
                if(visa.length) {
                    $.post('/admin/visa', {visa: visa}, function() {
                        //location.reload();
                    });
                }
            },
            complete: function() {
                $(this).attr('disabled', false).removeClass('loading');
            }
        });
    });

    $('.crawl-inventory').click(function() {
        $(this).attr('disabled', true).addClass('loading');
        $.ajax('/admin/visa/inventory', {
            context: $(this),
            error: function(data) {
                toastr.warning(data.responseText);
            },
            success: function() {
               location.reload();
            },
            complete: function() {
                $(this).attr('disabled', false).removeClass('loading');
            }
        });
    });
</script>
@endsection
