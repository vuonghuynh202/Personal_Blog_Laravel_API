@extends('admin.partials.master')

@section('title')
<title>Trang quản trị</title>
@endsection

@section('content')
<h2 class="mb-5">Thống Kê</h2>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-center card-rounded">
            <div class="card-body">
                <h4 class="card-title card-title-dash text-white mb-4">Bài viết</h4>
                <h2 class="dashboard-qty text-info">{{ $quantity['posts'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-center card-rounded">
            <div class="card-body">
                <h4 class="card-title card-title-dash text-white mb-4">Danh mục</h4>
                <h2 class="dashboard-qty text-primary">{{ $quantity['cats'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-dark text-center card-rounded">
            <div class="card-body">
                <h4 class="card-title card-title-dash text-white mb-4">Thẻ</h4>
                <h2 class="dashboard-qty text-info">{{ $quantity['tags'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-center card-rounded">
            <div class="card-body">
                <h4 class="card-title card-title-dash text-white mb-4">Người dùng</h4>
                <h2 class="dashboard-qty text-primary">{{ $quantity['users'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-info text-center card-rounded">
            <div class="card-body">
                <h4 class="card-title card-title-dash text-white mb-4">Lượt bình luận</h4>
                <h2 class="dashboard-qty text-dark">{{ $quantity['comments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-danger text-center card-rounded">
            <div class="card-body">
                <h4 class="card-title card-title-dash text-white mb-4">Lượt thích</h4>
                <h2 class="dashboard-qty text-primary">{{ $quantity['likes'] }}</h2>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col-lg-12">
        <div id="lineChart"></div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        var options = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Lượt truy cập',
                data: @json($data) // Dữ liệu số lượt truy cập
            }],
            xaxis: {
                categories: @json($labels), // Ngày
                title: {
                    text: 'Ngày'
                }
            },
            title: {
                text: 'Số lượt truy cập trong 7 ngày gần nhất'
            },
            stroke: {
                curve: 'smooth'
            }
        };

        var chart = new ApexCharts($("#lineChart")[0], options);
        chart.render();
    });
</script>

<script>
    $(document).ready(function() {
        $('.dashboard-qty').each(function() {
            var $visitCountElement = $(this);
            var targetCount = parseInt($visitCountElement.text());
            var currentCount = 0;

            var incrementCount = function() {
                if (currentCount < targetCount) {
                    currentCount += Math.ceil(targetCount / 100); // Tăng 1% của giá trị mục tiêu mỗi lần
                    $visitCountElement.text(Math.min(currentCount, targetCount)); // Đảm bảo không vượt quá targetCount
                    requestAnimationFrame(incrementCount);
                } else {
                    $visitCountElement.text(targetCount); // Đảm bảo số cuối cùng chính xác
                }
            };

            incrementCount();
        });
    });
</script>
@endsection