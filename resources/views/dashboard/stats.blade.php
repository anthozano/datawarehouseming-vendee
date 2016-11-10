@extends('default')

@section('content')
    <h2>Statistiques</h2>
    <H2>Nombre de personnes dans la base de données:</H2>
    <table class="table">
        <tr>
            <th>Total</th>
            <th>Mariés</th>
            <th>Morts</th>
        </tr>
        <tr>
            <th>{{ $nbPers }}</th>
            <th>{{ $nbMaries }}</th>
            <th>{{ $nbMorts }}</th>
        </tr>
    </table>
    <div style="width:100%;">
        <canvas id="moyenneDeces"></canvas>
    </div>
@endsection

@section('script')
    <script>
        var randomColorFactor = function () {
            return Math.round(Math.random() * 255);
        };
        var randomColor = function (opacity) {
            return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
        };
        var config = {
            type: 'line',
            data: {
                labels: [
                    @foreach($ageMoyenDeces as $amd)
                    {{ $amd->annee }},
                    @endforeach
                ],
                datasets: [{
                    label: "Moyenne d'age de decès par année",
                    data: [
                        @foreach($ageMoyenDeces as $amd)
                        {{ $amd->age }},
                        @endforeach
                    ],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                hover: {
                    mode: 'label'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Année'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Age moyen'
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Moyenne d\'age de decès par année'
                }
            }
        };

        $.each(config.data.datasets, function (i, dataset) {
            var background = randomColor(0.5);
            dataset.borderColor = background;
            dataset.backgroundColor = background;
            dataset.pointBorderColor = background;
            dataset.pointBackgroundColor = background;
            dataset.pointBorderWidth = 1;
        });

        window.onload = function () {
            var ctx = document.getElementById("moyenneDeces").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

    </script>
@endsection