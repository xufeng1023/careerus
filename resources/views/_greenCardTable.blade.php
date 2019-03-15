<style>
.table th, .table td {padding: 0.75rem 7px !important;}
h3{font-size:18px !important;}
</style>
<div class="table-responsive">
<h3 class="text-center">{{ $first->check_at->year }}年{{ $first->check_at->month }}月-绿卡排期-批准日期</h3>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>类别</th>
            <th>上月</th>
            <th>本月</th>
            <th>库存</th>
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
                        $filtered = $visas->filter(function($item) use($visa) {
                            return $item->check_at == $visa->check_at->subMonth(1) && $item->title == $visa->title;
                        })->toArray();
  
                        $action_at = array_pop($filtered)['action_at'];
                        $last = '';
                        if($action_at != null && $action_at != 'U') echo $last = $action_at;
                        elseif($action_at == null) echo '无需排期';
                        elseif($action_at == 'U') echo '排期撤销';
                    @endphp
                </td>
                <td>
                @php
                    if($visa->action_at != null && $visa->action_at != 'U') {
                        echo $visa->action_at;
                        if($last != '' && ($last != $visa->action_at)) {
                            if($last == null || $last == 'U') echo '出现排期';
                            else echo '<br>'.$visa->changesInDays($last);
                        }
                    }
                    elseif($visa->action_at == null) echo '无需排期';
                    elseif($visa->action_at == 'U') echo '排期撤销';
                @endphp
                </td>
                <td>{{ isset($inventories[$inventory_key])? $inventories[$inventory_key]->amount : '' }}</td>
                <!-- <td>{{ $visas->where('country', 'all')->where('title', $visa->title)->first()->action_at ?: '无需排期' }}</td> -->
            </tr>
            @php
                $inventory_key++;
            @endphp
        @endforeach
    </tbody>
</table>
</div>

<div class="table-responsive">
<h3 class="text-center">{{ $first->check_at->year }}年{{ $first->check_at->month }}月-绿卡排期-递件日期</h3>
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th>类别</th>
            <th>上月</th>
            <th>本月</th>
            <th>库存</th>
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
                        $filtered = $visas->filter(function($item) use($visa) {
                            return $item->check_at == $visa->check_at->subMonth(1) && $item->title == $visa->title;
                        })->toArray();
  
                        $filing_at = array_pop($filtered)['filing_at'];
                        $last = '';
                        if($filing_at != null && $filing_at != 'U') echo $last = $filing_at;
                        elseif($filing_at == null) echo '无需排期';
                        elseif($filing_at == 'U') echo '排期撤销';
                    @endphp
                </td>
                <td>
                @php
                    if($visa->filing_at) {
                        echo $visa->filing_at;
                        if(isset($last) && ($last != $visa->filing_at)) {
                            if(!$last || $last == 'U') echo '出现排期';
                            else echo '<br>'.$visa->changesInDays($last, 'filing_at');
                        }
                    }
                    else echo '无需排期';
                @endphp
                </td>
                <td>{{ isset($inventories[$inventory_key])? $inventories[$inventory_key]->amount : '' }}</td>
                <!-- <td>{{ $visas->where('country', 'all')->where('title', $visa->title)->first()->filing_at ?: '无需排期' }}</td> -->
            </tr>
            @php
                $inventory_key++;
            @endphp
        @endforeach
    </tbody>
</table>
</div>