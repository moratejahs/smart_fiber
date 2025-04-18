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
                        <h6 class="mb-2 f-w-400 text-muted">Statistics</h6>
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
                floatchart();
            }, 500);
        });

        function floatchart() {
            (function() {
                // Get data passed from the controller
                var barangayNames = @json($barangayNames); // Array of barangay names
                var totalUsers = @json($totalUsers); // Array of total users

                var options = {
                    chart: {
                        height: 450,
                        type: "area",
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    colors: ["#14BC23"],
                    series: [{
                        name: "Total Users",
                        data: totalUsers,
                    }],
                    stroke: {
                        curve: "smooth",
                        width: 2,
                    },
                    xaxis: {
                        categories: barangayNames, // Use barangay names as categories
                    },
                };

                var chart = new ApexCharts(document.querySelector("#visitor-chart"), options);
                chart.render();
            })();
        }
    </script>
@endsection
