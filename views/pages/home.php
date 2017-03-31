<?php

  include 'views/includes/config.php';
  include 'views/includes/handle_form.php';
  include 'views/partials/header.php';

  $query = $pdo->query('SELECT * FROM subscribers');
  $todolist= $query->fetchAll();

  // Get content
  $url  = 'https://data.nasa.gov/resource/y77d-th95.json';
  $path   = './cache/'.md5('nasa');

  // From cache
  if(file_exists($path))
  {
    $forecast = file_get_contents($path);
    echo '<p class="probleme-none"><p>';
  }
  // From API
  else
  {
    echo '<p class="probleme-none"><p>';
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

    <script type="text/javascript">
      var asteroids = <?= json_encode($asteroids)  ?>;

      // get API nasa data
      var astrs = <?= json_encode($asteroids) ?>;
    </script>

    <header>
      <div class="headerPage">
        <a href="/"><img class="logo" src="assets/img/logo.png"alt="#"></a>
      </div>
    </header>


    <div class="blocMessages">
      <!-- ERROR/SUCCESS MESSAGES -->
      <div class="messages success">
        <?php foreach($success_messages as $_message): ?>
          <p><?= $_message ?></p>
        <?php endforeach ?>
      </div>

      <div class="messages errors">
        <?php foreach($error_messages as $_key => $_message): ?>
          <p><?= "$_key : $_message" ?></p>
        <?php endforeach ?>
      </div>
    </div>
    
    <!-- CONTAINER TIMELINE-->
    <div class="containerLine">
      <section class="time-line">
        <div class="line-data">
          <div class="data-year">
            <p>To</p>
            <p class="data-year-item"></p>
          </div>
          <div class="data-number">
            <p class="data-number-item"></p>
            <p class="delete_space">asteroids have fallen.</p>
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

    <div class="containerMap">
      <section class="full-map">
        <div id="mapWE"></div>
        <div id="coords"></div>
      </section>
    </div>

    <!--Line and text about space-->
    <div class="containerTxtInfo">
      <div class="infoLine"></div>
      <div class="txtInfo">
       About 100 tons of extraterrestrial material strikes the Earth every day at the top of the atmosphere. Most are sprayed between 100 and 20 km altitude. A few tons still reach the ground. The two floors plunge into the oceans, the rest is mostly lost. The entry into the atmosphere of a kind of life causes a considerable heating which vaporizes its external part and slows down considerably. The iron present in the composition of the meteorite is consumed at several thousand degrees Celsius and burns its surface. In the end, it remains only about 1/100 to 1/1000 of its initial mass. Meteorites of a size greater than ten meters are not meadows and arrivals on the ground with a speed of several tens of kilometers per second. But why these tips of planets living peacefully between Mars and Jupiter decide to come to see the side of us risking passing to do a lot of damage?
      </div>
    </div>

    <!--Three block of description -->
    <div class="containerInfo">
      <div id="blocCanvas1" class="asteBloc blocInfo">
        <h2 class="titleBlocInfo">Astéroïdes</h2>
        <p>Asteroids are patatoid-shaped bodies gravitating mainly in the main asteroid belt between Mars and Jupiter and are composed mostly of carbon, silicone and metal. More than one million of them are more than one kilometer long and are already identified and monitored. They are considered "potentially dangerous" when they are over 130 meters.</p>
      </div>
      <div class="blocCanvas2 meteBloc blocInfo">
        <h2 class="titleBlocInfo orangeTitleMete">Météorites</h2>
        <p>Meteors are debris drifting into space that may come from comets, asteroids or fragmented planets. The debris becomes a meteor when it enters the earth's atmosphere: they are also called shooting stars because of the flash of lights they cause. If part of the meteor survives upon its arrival in the atmosphere and lands on Earth, this part is called meteorite and can weigh from a few grams to nearly 100 kilos.
        </p>
      </div>
      <div class="blocCanvas3 comBloc blocInfo">
        <h2 class="titleBlocInfo">Comètes</h2>
        <p>Comets are bodies composed mostly of ice and are easily recognizable by the "hair of ice" that they release behind them as they approach the Sun. Those that return to the solar system every less than 200 years are called periodic. They travel the solar system periodically and are somewhat smaller than the asteroids.</p>
      </div>
    </div>

    <!--Footer-->

    <footer>
      <div class="footer">
        <!--Form newsletter block-->
        <div class="newsletterBloc">
          <form method="post" lass="newsletter" >
            <div class="txtNews">
              Subscribe to our newsletter !
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
              <input class="validForm" type="submit" name="submit_add" value="Submit">
            </form>
          </div>
        </div>

        <!--SocialNetwork bloc-->
        <div class="socialBloc">
          <div class="social">
            <a class="socialLink" href="https://www.facebook.com/sharer/sharer.php?u=http://www.kaanroussel.com/home&amp;src=sdkpreparse"><img class="socialIcon" src="assets/img/fb.png" alt="facebook"></a>
            <a class="socialLink" href="https://twitter.com/intent/tweet?text=New web site about asteroids ! It's amazing !"><img class="socialIcon" src="assets/img/twitter.png" alt="twitter"></a>
            <a class="socialLink" href="https://plus.google.com/share?url=http://www.kaanroussel.com/home"><img class="socialIcon" src="assets/img/g+.png" alt="GooglePlus"></a>
          </div>
          <div class="txtFooter">
            Copyright (c) 2017 Copyright Heticien All Rights Reserved.
          </div>
        </div>
      </div>

     </footer>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/main.js"></script>

<?php
include 'views/partials/footer.php';