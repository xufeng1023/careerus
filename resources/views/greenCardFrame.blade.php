<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<table class="table table-striped">
    <caption>2018年7月 职业移民绿卡排期 - 批准日期</caption>
    <thead>
        <tr>
            <th>签证类别</th>
            <th>上月排期</th>
            <th>中国大陆本月排期</th>
            <th>485库存存量</th>
            <th>全球/港澳台</th>
        </tr>
    </thead>
    <tbody>
        @php
            $inventory_key = 0;
        @endphp
        @foreach($visas->where('country', 'china')->take(8) as $key => $visa)
            <tr>
                <td>{{ $visa->title }}<br>{{ $visa->titleToChinese($visa->title) }}</td>
                <td>{{ isset($visas[$key + 16])? $visas[$key + 16]->action_at : '' }}</td>
                <td>{{ $visa->action_at }}</td>
                <td>{{ isset($inventories[$inventory_key])? $inventories[$inventory_key]->amount : '' }}</td>
                <td>{{ $visas->where('country', 'all')->where('title', $visa->title)->first()->action_at }}</td>
            </tr>
            @php
                $inventory_key++;
            @endphp
        @endforeach
    </tbody>
</table>
