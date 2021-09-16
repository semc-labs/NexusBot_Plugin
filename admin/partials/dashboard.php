<?php 
  $nexusbot_url = get_option('nexusbot_url');
?>
<div class="wrap">
  <h1>Dashboard</h1>
  <div id="container" style="width:100%; height:400px;"></div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    fetch('<?php echo $nexusbot_url; ?>/messages')
    .then(response => response.json())
    .then(series => {

      Highcharts.chart('container', {
          title: {
            text: 'Channel Messages'
          },

          subtitle: {
            text: 'Source: Nexus Aurora Discord'
          },

          yAxis: {
            title: {
              text: 'Number of Messages'
            }
          },

          xAxis: {
            type: "datetime",
            labels: {
              formatter: function() {
                return Highcharts.dateFormat('%b %e', this.value);
              }
            }
          },

          plotOptions: {
            series: {
              label: {
                connectorAllowed: false
              }
            }
          },

          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
          },

          series:series,

          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom'
                }
              }
            }]
          }

          });
    });

    
    });
</script>