<head>
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>
    google.charts.load('current', {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      $.getJSON('reviewData.php', function(jsonData){
        var data = new google.visualization.arrayToDataTable(jsonData);

        var options = {
          title: 'Review Test Chart',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, {width: 400, height: 240});

      });
    }
  </script>
</head>
<!--Div that will hold the pie chart-->
<div id="chart" style="width: 900px; height: 500px"></div>
