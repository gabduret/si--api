<?php

// Get content

$compteur = 0;
$potd     = 0;

function myfunction($a)
{
  global $potd;
  $date     = date('Y-m-d');
  $key      = 'iT01pDuV5s8EI6zlRUTH65aam9r6O7kdeBE3tymO';
  $url      = 'https://api.nasa.gov/planetary/apod?api_key=' . $key . '&date='. $date;
  $name     = 'potd';
  $path     = './cache/'.md5($name.date('Y-m-d'));

  // From cache
  if(file_exists($path))
  {
    echo '<p class="probleme-none"><p>';
    $potd = file_get_contents($path);
  }
  // From API
  else
  {
    echo '<p class="probleme-none"><p>';
    $potd = file_get_contents($url);
    file_put_contents($path, $potd);
  }

  // Json decode
  $potd = file_get_contents($url);
  $potd = json_decode($potd);
}

myfunction(0);

// Verification 
if (!empty($potd->code))
{
  $compteur += 1;
  potd();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <title>EARTH IMPACT</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

  <div class="containerIntro">
    <div class="headerPage">
      <a href="#"><img class="logo" src="../assets/img/logo.png"alt="#"></a>
    </div>

    <div class="introBlock">
      <h2 class="intro titleIntro">Welcome to <span class="colorTitleIntro">EarthImpact</span></h2>
      <p class="intro txtIntro">Here is a new experience showing the meteorites which have fallen on Earth these past two thousands years. You can watch where they have hit the floor, how big they were and how many they were. This way we can see how the universe is big and how small we are. Enjoy the show !</p>
      <a href="views/pages/home.php" class="buttonIntro">Start the experience</a>
    </div>
    <img class="imgBackground" src="<?= $potd->url; ?>" alt="">
  </div>


  <section class="infoPotdFull">
    <img class="infoPotdIcon" src="../assets/img/int.png">
    <div class="infoPotd">
      <h2>Picture of the day</h2>
      <p><?= $potd->explanation; ?></p>
    </div>
  </section>


</body>
</html>
