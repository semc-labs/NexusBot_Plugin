<?php 
  $nexusbot_url = get_option('nexusbot_url');

  $request = wp_remote_get($nexusbot_url.'/messages');
  $messages = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);

  global $wpdb;

  $messages = $wpdb->get_results("SELECT nam.*, nac.name AS channel FROM na_channel_messages AS nam
                                  LEFT JOIN na_channels AS nac ON nac.channelId = nam.channelId 
                                  ORDER BY nac.name ASC", ARRAY_A);

  $series = [];
  $seriesChannel = [];
  if(! empty($messages)){
    foreach($messages as $message){
      if( isset($seriesChannel[$message['channel']]) ) {
        $series[$seriesChannel[$message['channel']]]['data'][] = [strtotime($message['date']),intval($message['usageCount'])];
      }else{
        $seriesChannel[$message['channel']] = count($series);
        
        $series[] = [
          'name' => $message['channel'],
          'data' => [
            [strtotime($message['date']),intval($message['usageCount'])]
          ]
        ];
        
      }

    }
  }

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

    fetch('<?php echo $nexusbot_url; ?>')
    .then(response => response.json())
    .then(series => {
      document.getElementById('online').style.display = 'block';
    })
    .catch((e) => {
      document.getElementById('offline').style.display = 'block';
    });

    // fetch('<?php //echo $nexusbot_url; ?>/messages')
    // .then(response => response.json())
    // .then(series => {
      var series = <?php echo json_encode($series, JSON_HEX_TAG); //json_encode($messages, JSON_HEX_TAG); ?>;

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
    // })
    // .catch((e) => {
    //   document.getElementById('offline').style.display = 'block';
    // });

  });
</script>