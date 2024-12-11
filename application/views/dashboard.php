<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content mt-3">
    <div class="card">
        <div class="card-header">
            <div class="date form-inline">
                <button id="dailyFilter"  class="btn btn-info btn-sm ml-3 btn-print"><i class="fa fa-calendar"></i> Daily</button>
                <button id="weeklyFilter"  class="btn btn-info btn-sm ml-3 btn-print"><i class="fa fa-calendar"></i> Weekly</button>
                <button id="monthlyFilter" class="btn btn-info btn-sm ml-3 btn-print"><i class="fa fa-calendar"></i> Monthly</button>
                <div class="ml-3"></div> <!-- Add space between buttons and inputs -->
                <input type="text" name="start" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
                <span class="mx-2">-</span>
                <input type="text" name="end" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
            </div>
        </div>
    </div>
</div>

<div class="content mt-3">
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-success">
                <div class="card-body pb-0">
                    <div class="float-right">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?=rupiah($today_income);?></span>
                    </h4>
                    <p class="text-light">Pendapatan Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-info">
                <div class="card-body pb-0">
                    <div class="float-right">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?=$today_service;?></span>
                    </h4>
                    <p class="text-light">Service Selesai Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-warning">
                <div class="card-body pb-0">
                    <div class="float-right">
                        <i class="fa fa-share"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?=$today_items_sold;?></span>
                    </h4>
                    <p class="text-light">Item Terjual Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-danger">
                <div class="card-body pb-0">
                    <div class="float-right">
                        <i class="fa fa-warning"></i>
                    </div>
                    <h4 class="mb-0">
                        <span class="count"><?=$items_sold_out;?></span>
                    </h4>
                    <p class="text-light">Stock Telah Habis</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Grafik Jasa Service
                </div>
                <div class="card-body">
                    <canvas id="myChart1" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Grafik Penjualan Sparepart
                </div>
                <div class="card-body">
                    <canvas id="myChart2" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    const elem = document.querySelector('.date');
    const datepicker = new daterangepicker(elem, {
        format: "yyyy-mm-dd",
        autoApply: true,  // Automatically apply date range
        locale: {
            format: 'YYYY-MM-DD'  // Set date format
        }
    });

    $("input[name=end]").on("change", function () {
        const start = $("input[name=start]").val();
        const end = $("input[name=end]").val();
        updateCharts('date_range', start, end);
    });
});

    const labels = <?= json_encode($title) ?>;
    const serviceData = <?= json_encode($valueService) ?>;
    const sparepartData = <?= json_encode($valueSparepart) ?>;

    const ctx1 = document.getElementById('myChart1').getContext('2d');
    const ctx2 = document.getElementById('myChart2').getContext('2d');

    const myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Service',
                data: serviceData,
                backgroundColor: "rgba(255, 99, 132, 0.2)",
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    const myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sparepart',
                data: sparepartData,
                backgroundColor: "rgba(99, 255, 132, 0.2)",
                borderColor: "rgba(99, 255, 132, 1)",
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    document.getElementById('dailyFilter').addEventListener('click', function () {
        updateCharts('daily');
    });

    document.getElementById('weeklyFilter').addEventListener('click', function () {
        updateCharts('weekly');
    });

    document.getElementById('monthlyFilter').addEventListener('click', function () {
        updateCharts('monthly');
    });

    function updateCharts(filter, start = null, end = null) {
    const data = { filter: filter };
    if (start !== null && start !== '') {
        data.start_date = start;
    }
    if (end !== null && end !== '') {
        data.end_date = end;
    }
    $.ajax({
        url: '<?= base_url("Dashboard/filter"); ?>',
        type: 'POST',
        data: data,
        success: function (data) {
            const responseData = JSON.parse(data);
            
            $(".count:eq(0)").text(rupiah(responseData.today_income));
            $(".count:eq(1)").text(responseData.today_service);
            $(".count:eq(2)").text(responseData.today_items_sold);
            $(".count:eq(3)").text(responseData.items_sold_out);

            myChart1.data.labels = responseData.title;
            myChart1.data.datasets[0].data = responseData.valueService;
            myChart1.update();

            myChart2.data.labels = responseData.title;
            myChart2.data.datasets[0].data = responseData.valueSparepart;
            myChart2.update();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching chart data:', error);
        }
    });
}
</script>
