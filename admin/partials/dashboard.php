<?php 
  $nexusbot_url = get_option('nexusbot_url');

  $request = wp_remote_get($nexusbot_url.'/messages');
  $messages = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);
?>
<div class="wrap">
  <h1>Dashboard</h1>
  <div id="offline" style="display: none;" class="error notice"><p>Bot Offline</p></div>
  <div id="online" style="display: none;" class="updated notice"><p>Bot Online</p></div>
  <div id="container" style="width:100%; height:400px;"></div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    // fetch('<?php //echo $nexusbot_url; ?>/messages')
    // .then(response => response.json())
    // .then(series => {
      var messages = <?php echo json_encode($messages, JSON_HEX_TAG); ?>;
      document.getElementById('online').style.display = 'block';

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
          series:messages,
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
    // })
    // .catch((e) => {
    //   document.getElementById('offline').style.display = 'block';
    // });

  });
</script>