<html lang="zh">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
.table th, .table td {padding: 0.75rem 7px !important;}
h3{font-size:18px !important;}
</style>

<div class="table-responsive">
<h3 class="text-center">{{ $first->check_at->year }}年{{ $first->check_at->month }}月职业移民绿卡批准日期</h3>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>类别</th>
            <th>上月</th>
            <th>本月</th>
            <th>485库存</th>
            <th>港澳台</th>
        </tr>
    </thead>
    <tbody>
        @php
            $inventory_key = 0;
        @endphp
        @foreach($visas->where('country', 'china')->take(8) as $key => $visa)
            <tr>
                <td>
                    <h6><strong>{{ $visa->titleToEb() }}</strong><br>{{ $visa->titleToChinese() }}</h6>
                </td>
                <td>
                    @php
                        if(isset($visas[$key + 16]) && $visas[$key + 16]->action_at) echo $last = $visas[$key + 16]->action_at;
                        else echo '无需排期';
                    @endphp
                </td>
                <td>
                @php
                    if($visa->action_at) {
                        echo $visa->action_at;
                        if(($last && $visa->action_at) && ($last != $visa->action_at)) echo '<br>'.$visa->changesInDays($last);
                    }
                    else echo '无需排期';
                @endphp
                </td>
                <td>{{ isset($inventories[$inventory_key])? $inventories[$inventory_key]->amount : '' }}</td>
                <td>{{ $visas->where('country', 'all')->where('title', $visa->title)->first()->action_at ?: '无需排期' }}</td>
            </tr>
            @php
                $inventory_key++;
            @endphp
        @endforeach
    </tbody>
</table>
</div>

<div class="table-responsive">
<h3 class="text-center">{{ $first->check_at->year }}年{{ $first->check_at->month }}月职业移民绿卡递件日期</h3>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>类别</th>
            <th>上月</th>
            <th>本月</th>
            <th>485库存</th>
            <th>港澳台</th>
        </tr>
    </thead>
    <tbody>
        @php
            $inventory_key = 0;
        @endphp
        @foreach($visas->where('country', 'china')->take(8) as $key => $visa)
            <tr>
                <td>
                    <h6><strong>{{ $visa->titleToEb() }}</strong><br>{{ $visa->titleToChinese() }}</h6>
                </td>
                <td>
                    @php
                        if(isset($visas[$key + 16]) && $visas[$key + 16]->filing_at) echo $last = $visas[$key + 16]->filing_at;
                        else echo '无需排期';
                    @endphp
                </td>
                <td>
                @php
                    if($visa->filing_at) {
                        echo $visa->filing_at;
                        if(($last && $visa->filing_at) && ($last != $visa->filing_at)) echo '<br>'.$visa->changesInDays($last, 'filing_at');
                    }
                    else echo '无需排期';
                @endphp
                </td>
                <td>{{ isset($inventories[$inventory_key])? $inventories[$inventory_key]->amount : '' }}</td>
                <td>{{ $visas->where('country', 'all')->where('title', $visa->title)->first()->filing_at ?: '无需排期' }}</td>
            </tr>
            @php
                $inventory_key++;
            @endphp
        @endforeach
    </tbody>
</table>
</div>

</html>