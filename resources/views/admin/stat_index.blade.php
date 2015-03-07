@extends('admin.layout')
@section('content')
<div class="main">
    <div class="breadcrumb">
        <span><i class="fa fa-home"></i>Home</span>
        <span>/</span>
        <span>Statistics</span>
    </div>
    <div class="col-lg-12 main-view">
        <div class="col-lg-6 chart ">
            <div>
                <div class="chart-header">
                    Best Selling Items
                </div>
                <div class="chart-content">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 chart ">
            <div>
                <div class="chart-header">
                    Profit of this month
                </div>
                <div class="chart-content">
                    <canvas id="myChart2"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
Chart.defaults.global.responsive = true;
var Barchart = {
    datasets: [
    {
        label: "My First dataset",
        fillColor: "#437DD7",
        strokeColor: "#437DD7",
        highlightFill: "rgba(67, 125, 215, 0.71)",
        highlightStroke: "#437DD7"
    }
    ]
};
var Linechart = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "#3A3E44",
            strokeColor: "#3A3E44",
            pointColor: "#fff",
            pointStrokeColor: "#303641",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        }
    ]
};
$.get( "/stat/ajax_best_selling_item", function( data ) {
  data = $.parseJSON( data );
  console.log(data);
  Barchart.labels = data.labels
  Barchart.datasets[0].data = data.datasets
  var ctx = document.getElementById("myChart").getContext("2d");
  var myBarChart = new Chart(ctx).Bar(Barchart, { tooltipTemplate : "<%if (label){%><%=label%>: <%}%><%= value %>" });
});

$.get( "/stat/ajax_profit", function( data ) {
  data = $.parseJSON( data );
  console.log(data);
  Linechart.labels = data.labels
  Linechart.datasets[0].data = data.datasets
  var ctx = document.getElementById("myChart2").getContext("2d");
  var myBarChart = new Chart(ctx).Line(Linechart, { tooltipTemplate : "$<%= value %>" });
});
</script>
@stop