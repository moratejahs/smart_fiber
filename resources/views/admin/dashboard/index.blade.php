@extends('layouts.admin-layout')

@section('links')
@endsection

@section('content')
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Dashboard</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-2 f-w-400 text-muted">Monthly Grade Statistics</h6>
                        <div id="visitor-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        "use strict";
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                renderBarChart();
            }, 500);
        });

        function renderBarChart() {
            (function() {
                // Get data passed from the controller
                var barangayNames = @json($barangayNames); // Array of barangay names
                var totalDataset = @json($totalDataset); // Array of grade totals

                // Prepare series data for the bar chart
                var seriesData = [{
                        name: "S2 (Machine Strip)",
                        data: totalDataset.map(item => item.total_S2),
                    },
                    {
                        name: "JK (Hand Strip)",
                        data: totalDataset.map(item => item.total_JK),
                    },
                    {
                        name: "M1 (Bakbak ng JK)",
                        data: totalDataset.map(item => item.total_M1),
                    },
                    {
                        name: "S3 (Bakbak ng S2)",
                        data: totalDataset.map(item => item.total_S3),
                    },
                ];

                var options = {
                    chart: {
                        height: 450,
                        type: "bar",
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    colors: ["#14BC23", "#FF5733", "#FFC300", "#3498DB"],
                    series: seriesData,
                    xaxis: {
                        categories: barangayNames, // Use barangay names as categories
                        title: {
                            text: "Barangays",
                        },
                    },
                    yaxis: {
                        title: {
                            text: "Total Grades",
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "55%",
                            endingShape: "rounded",
                        },
                    },
                    legend: {
                        position: "top",
                    },
                };

                var chart = new ApexCharts(document.querySelector("#visitor-chart"), options);
                chart.render();
            })();
        }
    </script>
@endsection
