<?php

  // echo "<br>";
  // echo "<br>";
  // echo "<br>";
  // echo "<br>";
  // Get content
  $key  = '';
  $date = date('Y-m-d');
  // $date = '2017-05-12';
  $url  = 'https://api.nasa.gov/planetary/apod?api_key=iT01pDuV5s8EI6zlRUTH65aam9r6O7kdeBE3tymO&date='. $date;
  $name = 'potd';
  $path = './cache/'.md5($name.date('Y-m-d'));

  // From cache
  if(file_exists($path))
  {
    echo "cache";
    $potd = file_get_contents($path);
  }
  // From API
  else
  {
    echo "API";
    $potd = file_get_contents($url);
    file_put_contents($path, $potd);
  }

  // Json decode
  $potd = file_get_contents($url);
  $potd = json_decode($potd);


  // if (!empty($potd->code))
  // {
  //   echo "probleme";
  // }
  // else
  // {
  //   echo "good";
  // }

  // echo "<pre>";
  // print_r($potd);
  // echo "</pre>";

  // echo $potd->url;


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <title>EARTH IMPACT</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="containerIntro">

    <div class="headerPage">
      <a href="#"><img class="logo" src="assets/img/logo.png"alt="#"></a>
    </div>

    <div class="introBlock">
      <h2 class="intro titleIntro">Welcome to <span class="colorTitleIntro">EarthImpact</span></h2>
      <p class="intro txtIntro">Here is a new experience showing the meteorites which have fallen on Earth these past two thousands years. You can watch where they have hit the floor, how big they were and how many they were. This way we can see how the universe is big and how small we are. Enjoy the show !</p>
      <a href="views/pages/home.php" class="buttonIntro">Start the experience</a>
    </div>
    <img class="imgBackground" src="<?= $potd->url; ?>" alt="">
  </div>
  <div class="infoPotd">
    <p><?= $potd->explanation; ?></p>
  </div>


</body>
</html>
