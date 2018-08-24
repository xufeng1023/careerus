@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="justify-content-between d-flex align-items-center">
                        <h5 class="card-title m-0">艺术与人文法语德语西班牙...</h5>
                        <a href="#" class="btn btn-success btn-sm">点选直申
                        <div style="width:15px;height:15px;border-radius:50%;" class="bg-white d-inline-flex justify-content-center align-items-center text-success">&#10004;</div>
                        </a>
                    </div>
                    
                    <div>
                        <span class="badge badge-pill badge-secondary">教育</span>
                        <span class="badge badge-pill badge-secondary">文学</span>
                        <span class="badge badge-pill badge-secondary">团体</span>
                    </div>
                    <div class="justify-content-between d-flex align-items-center">
                        <small class="text-muted">发布于1天前</small>
                    </div>
                </div>

                <div class="card-body">
                    <p class="card-text">需要15年以上经验的大学历史教授，会说8种语言，并且有能力组织学生到世界各地学习。</p>
                    <!-- <canvas id="lineChart"></canvas> -->
                    <div><span data-feather="home"></span> New York University</div>
                    <div class="small d-flex justify-content-between">
                        <span>地点: <span class="text-secondary">New York</span></span>
                        <span>规模: <span class="text-secondary">100人以上</span></span>
                        <span>2017 H1B: <span class="text-secondary">1329人</span></span>
                    </div>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-around text-secondary">
                    <i class="fas fa-heart"></i>
                    <i class="fab fa-weixin"></i>
                    <i class="fas fa-globe"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>
var ctx = document.getElementById('lineChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: ["2015", "2016", "2017"],
        datasets: [{
            label: "该职位被公司SPONSOR的近三年统计",
            backgroundColor: 'rgba(255, 99, 132, .5)',
            borderColor: 'rgb(255, 99, 132)',
            data: [232, 455, 311],
        }]
    },

    // Configuration options go here
    options: {
        legend: {
            labels: {
                boxWidth: 0
            }
        },
    }
});
</script> -->
@endsection
