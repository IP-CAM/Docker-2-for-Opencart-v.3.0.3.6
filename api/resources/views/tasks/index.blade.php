<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
    <title>TwTech</title>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">UltraEG. Technical Support</a>
    </div>
    <ul class="nav navbar-nav">
      
    </ul>
  </div>
</nav>
    <ul class='list-group'>
    @foreach ($task as $t)
    <a href="http://ultraeg.com/ultra/public/taskDetails/{{ $t->id }}">
    <li class='list-group-item'>
    {{ $t->title }}
    </li>

    </a>
    @endforeach
    </ul>

</body>
</html>