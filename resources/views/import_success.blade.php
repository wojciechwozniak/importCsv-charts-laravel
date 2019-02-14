@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 mt-5">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        var headers = {!! $headers !!};
        var data = {!! $data !!};
        var randomColorGenerator = function () {
            var i = 0;
            var colors = [];
            while(i < data.length){
                colors.push('#' + (Math.random().toString(16) + '0000000').slice(2, 8));
                i++;
            }
                return colors;
        };
        console.log(headers);
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: headers,
                datasets: [{
                    data: data,
                    backgroundColor: randomColorGenerator(),
                    borderColor: randomColorGenerator(),
                    borderWidth: 1
                }]
            },

            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });

    </script>
@endsection
