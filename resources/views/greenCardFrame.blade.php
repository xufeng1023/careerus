<html lang="zh">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
iframe .table .thead-dark th {background-color:#4aba78 !important;}
.table th, .table td {padding: 0.75rem 7px !important;}
h3{font-size:18px !important;}
</style>
{{ request()->url() }}
@include('_greenCardTable')

</html>