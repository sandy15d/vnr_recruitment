<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "MRF : {{$job_code}}",
                },
                theme: "light2",
                animationEnabled: true,
                toolTip: {
                    shared: true,
                    reversed: true
                },
                axisY: {
                    title: "Count",
                    /*   suffix: " MW"*/
                },
                axisX: {
                    labelAngle: -35 // Change the angle as per your requirement
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                data: [

                    {
                        type: "stackedColumn",
                        name: "CV Received",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",

                        dataPoints: <?php echo json_encode($cv_receive, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "CV Screening",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($resume_screening, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "HR Screening",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($hr_screening, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "Technical Screening",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($tech_screening, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        type: "stackedColumn",
                        name: "Interview",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($interview_arr, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "Second Round Interview",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($second_interview, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "Job Offered",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($job_offer, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "Offer Accepted",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($offer_accepted, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }, {
                        type: "stackedColumn",
                        name: "Joined",
                        showInLegend: true,
                        yValueFormatString: "#",
                        indexLabel: "{y}",
                        indexLabelPlacement: "inside",
                        indexLabelFontWeight: "bolder",
                        indexLabelFontColor: "white",
                        dataPoints: <?php echo json_encode($joined, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });

            chart.render();

            function toggleDataSeries(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                e.chart.render();
            }

        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>