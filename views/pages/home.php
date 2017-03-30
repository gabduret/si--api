<?php

  include '../includes/config.php';
  include '../includes/handle_form.php';

  $query = $pdo->query('SELECT * FROM subscribers');
  $todolist= $query->fetchAll();

  // Get content
  $url  = 'https://data.nasa.gov/resource/y77d-th95.json';
  $path   = './../../cache/'.md5('nasa');

  // From cache
  if(file_exists($path))
  {
    $forecast = file_get_contents($path);
    echo '<p class="probleme-none"><p>';
  }
  // From API
  else
  {
    echo "API";
    $forecast = file_get_contents($url);
    file_put_contents($path, $forecast);
  }

  // Json decode
  $forecast = json_decode($forecast);

  // Delete empty data
  for($i = 0; $i <= count($forecast); $i++)
  {
    if (empty($forecast[$i]->mass))
    {
      unset($forecast[$i]);
    }
    if (empty($forecast[$i]->reclong))
    {
      unset($forecast[$i]);
    }
    if (empty($forecast[$i]->reclat))
    {
      unset($forecast[$i]);
    }
    if (empty($forecast[$i]->year))
    {
      unset($forecast[$i]);
    }
  }

  // Sorting function cmp
  function cmp($a, $b)
  {
    print_r($a->year);
      return strcmp($a->year, $b->year);
  }

  // Duplicate tab
  $asteroids = new stdClass();
  $asteroids = $forecast;


  // Call Sorting function cmp
  usort($asteroids, function($a, $b)
  {
    if (!empty($a->year) && !empty($b->year))
    {
      return strcmp($a->year, $b->year);
    }
  });

  // Find the year
  for($i = 0; $i <= count($asteroids); $i++)
  {
    if (!empty($asteroids[$i]->year))
    {
      // echo "<br>";
      $foo = $asteroids[$i]->year;
      $rest = substr($foo, 0, 4);
      $asteroids[$i]->year = $rest;
    }
  }
?>

<!DOCTYPE HTML>
<html>
  <head>
    <meta name="robots" content="index, all" />
    <title>EARTH IMPACT</title>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
    <link rel="stylesheet" href="../../assets/css/timeline.css" />
    <link rel="stylesheet" href="../../assets/css/main.css" />
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
    <script src="http://www.webglearth.com/v2/api.js"></script>
  </head>
  <body>

    <script type="text/javascript">
      var asteroids = <?= json_encode($asteroids)  ?>;

      // get API nasa data
      var astrs = <?= json_encode($asteroids) ?>;
    </script>

  <header>
    <div class="headerPage">
      <h1 class="logocontainer"><a href="#"><img class="logo" src="../../assets/img/logo.png"alt="#"></a></h1>
    </div>
  </header>


  <!--<div class="blocMessages">
    <!-- ERROR/SUCCESS MESSAGES -->
    <!--<div class="messages success">
      <?php foreach($success_messages as $_message): ?>
        <p><?= $_message ?></p>
      <?php endforeach ?>
    </div>

    <div class="messages errors">
      <?php foreach($error_messages as $_key => $_message): ?>
        <p><?= "$_key : $_message" ?></p>
      <?php endforeach ?>
    </div>
  </div>-->

  
  <!-- CONTAINER TIMELINE-->
  <div class="containerLine">
    <section class="time-line">
      <div class="line-data">
        <div class="data-year">
          <p>Depuis </p>
          <p class="data-year-item"></p>
        </div>
        <div class="data-number">
          <p class="data-number-item"></p>
          <p> astéroïdes sont tombés</p>
        </div>
      </div>
      <div class="time-line-item">
        <div class="progress-line"></div>
      </div>
    </section>
  </div>
  <!--CONTAINER MAP-->
  <div class="containerMap">
    <section class="full-map">
      <div id="mapL"></div>
      <div id="coords"></div>
    </section>
  </div>

  <!--Line and text about space-->
  <div class="containerTxtInfo">
    <div class="infoLine"></div>
    <div class="txtInfo">
      Environ 100 tonnes de matière extraterrestre frappent la Terre chaque jour au sommet de l'atmosphère. La plupart sont vaporisées entre 100 et 20 km d'altitude. Quelques tonnes atteignent tout de même le sol. Les deux tiers plongent dans les océans, le reste est le plus souvent perdu.
      L'entrée dans l'atmosphère d'un météore provoque un échauffement considérable qui vaporise sa partie externe et le ralentit considérablement. Le fer présent dans la composition de la météorite se consume à plusieurs milliers de degrés Celsius et fait brûler sa surface. Il ne reste au final qu'environ 1/100 à 1/1000 de sa masse initiale. Les météorites d'une taille supérieure à une dizaine de mètres ne sont pratiquement pas ralenties et arrivent au sol avec une vitesse de plusieurs dizaines de kilomètres par seconde.
      Mais pourquoi ces bouts de planètes vivant paisiblement entre Mars et Jupiter décident de venir voir du côté de chez nous risquant au passage de faire pas mal de dégâts ?
    </div>
  </div>

  <!--Three block of description -->
  <div class="containerInfo">
    <div id="blocCanvas1" class="asteBloc blocInfo">
      <h2 class="titleBlocInfo">Astéroïdes</h2>
      <p>Les astéroïdes sont des corps de forme patatoïde gravitant principalement dans la ceinture principale d’astéroïdes située entre Mars et Jupiter et sont composés en majeure partie de carbone, de silicone et de métal. Plus d’un million d’entre eux font plus de un kilomètre et  sont déjà identifiés et surveillés. Ils sont, en effet, considérés comme “potentiellement dangereux” lorsqu’ils font plus de 130 mètres.</p>
      <canvas id="canvasInfo" width=450 height=360></canvas>
    </div>
    <div class="blocCanvas2 meteBloc blocInfo">
      <h2 class="titleBlocInfo orangeTitleMete">Météorites</h2>
      <p>Les météores sont des débris dérivant dans l’espace pouvant provenir de comètes, d’astéroïdes ou de planètes fragmentées. Les débris deviennent météores lorsqu’ils entrent dans l’atmosphère terrestre : ils sont aussi appelés étoiles filantes à cause du flash de lumières qu’ils causent. Si une partie du météore survit à son arrivée dans l’atmosphère et atterrit sur la Terre, cette partie est appelée météorite et peut peser de quelques grammes à près de 100 kilos.
      </p>
    </div>
    <div class="blocCanvas3 comBloc blocInfo">
      <h2 class="titleBlocInfo">Comètes</h2>
      <p>Les comètes sont des corps composés en majorité de glace et sont facilement reconnaissable à la “chevelure” de glace qu’elles libèrent derrière elles lorsqu’elles s’approchent du Soleil. Celles qui reviennent dans le système solaire tous les moins de 200 ans sont dites périodiques. Elles parcourent le système solaire de manière périodique et sont un peu plus petites que les astéroïdes.</p>
    </div>
    <!--<img class="imgBackground" src="http://apod.nasa.gov/apod/image/1703/KingOfWings_Pinkston_7360.jpg" alt="">-->
  </div>

  <!--Footer-->

  <footer>
    <div class="footer">

      <!--Form newsletter block-->
      <div class="newsletterBloc">
        <form method="post" lass="newsletter" >
          <div class="txtNews">
            subscribe to our newsletter !
          </div>
          <div class="margForm">

            <div class="<?= array_key_exists('first-name', $error_messages) ? 'error' : '' ?>">
              <input class="subPut" type="text" name="first-name" value="<?= $_POST['first-name'] ?>" placeholder="First Name" id="first-name"/>
            </div>
            <div class="<?= array_key_exists('last-name', $error_messages) ? 'error' : '' ?>">
              <input class="subPut" type="text" name="last-name" value="<?= $_POST['last-name'] ?>" placeholder="Last Name" id="last-name"/>
            </div>
            <div class="<?= array_key_exists('email-sub', $error_messages) ? 'error' : '' ?>">
              <input class="subPut mailForm" type="email" name="email-sub" value="<?= $_POST['email-sub'] ?>" placeholder="Enter your email" id="email-sub"/>
            </div>
          </div>
          <div>
            <input class="validForm" type="submit" name="submit_add" value="ADD">

          </form>
        </div>


      </div>
      <!--SocialNetwork bloc-->
      <div class="socialBloc">
        <div class="social">
          <a class="socialLink" href="#"><img class="socialIcon" src="../../assets/img/fb.png" alt="facebook"></a>
          <a class="socialLink" href="#"><img class="socialIcon" src="../../assets/img/twitter.png" alt="twitter"></a>
          <a class="socialLink" href="#"><img class="socialIcon" src="../../assets/img/g+.png" alt="GooglePlus"></a>
        </div>
        <div class="txtFooter">
          Copyright (c) 2017 Copyright Heticien All Rights Reserved.
        </div>
      </div>

    </div>

  </footer>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="src/scripts/script.js"></script>






















        
    <script type="text/javascript" src="../../assets/js/main.js"></script>
  </body>
</html>