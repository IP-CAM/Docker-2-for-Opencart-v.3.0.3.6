<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
    <title>Document</title>
    <style>
    /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
       #map {
        height: 300px;
        width:100%; 
      }
  
    </style>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/newtask">UltraEG. Technical Support</a>
    </div>

  </div>
</nav>
<div class="container">
  <h1>وصف الطلب</h1>    
  <p>معلومات الطلب المقدم:</p>
  <dl>
    <dt>العنوان</dt>
    <dd>- {{@$task->title}}</dd>
    <dt>الوصف</dt>
    <dd>- {{@$task->content}}</dd>
    <dt>الجوال</dt>
    <dd>- {{@$task->phone}}</dd>
    <a href="https://www.google.com/maps/search/?api=1&query={{ @$task->taskLat }},{{ @$task->taskLng }}">رابط الخريطه</a>
    <hr>
    <div id="map"></div>
    <script>
      function initMap() {
        var uluru = {lat: {{ @$task->taskLat }}, lng: {{ @$task->taskLng }}};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: uluru,
        });
       
        var geocoder = new google.maps.Geocoder;
        var infowindow = new google.maps.InfoWindow;
        var latlng = uluru;

        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              map.setZoom(11);
              var marker = new google.maps.Marker({
                position: latlng,
                map: map
              });
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
            } else {
              window.alert('No results found');
            }
          } else {
            window.alert('Geocoder failed due to: ' + status);
          }
        });

      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZVG77QxBPI-tBHpa56diOaufxR866gOs&callback=initMap">
    </script>
  </dl>     
</div>
</body>
</html>