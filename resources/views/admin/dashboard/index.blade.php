@extends('layouts.admin-layout')

@section('content')
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <h5 class="m-b-10">Dashboard</h5>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted">Monthly User Registration Statistics</h6>
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
            setTimeout(renderUserChart, 500);
        });

        function renderUserChart() {
            // From controller
            var monthNames = @json($monthNames);
            var monthlyData = @json($monthlyData);

            var options = {
                chart: {
                    height: 450,
                    type: "bar",
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: "Users Registered",
                    data: monthlyData
                }],
                xaxis: {
                    categories: monthNames,
                    title: {
                        text: "Months"
                    }
                },
                yaxis: {
                    title: {
                        text: "Number of Users"
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded"
                    }
                },
                colors: ["#3498DB"],
                legend: {
                    position: "top"
                },
                dataLabels: {
                    enabled: false
                }
            };

            new ApexCharts(
                document.querySelector("#visitor-chart"),
                options
            ).render();
        }
    </script>
@endsection
