<?php

$start_date = do_shortcode('[trade_fair_datetotimer]');

$output .= '
  <style>

    #countdownCustom {
        max-width: 600px;
        overflow: hidden;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 30px;
        padding: 5px 15px;
        margin: 0 auto;
    }

      #countdownCustom .countdown-text {
        text-align: center;
        font-size: 20px;
        text-transform: uppercase;
        font-weight: 700;
        margin: 7px 0 5px;
      }

      .countdown-container {
        display: flex;
        justify-content: center;
        margin-top: 0px;
      }

      .countdown-container div {
        text-align: center;
        width: 22%;
        margin: 5px;
        border-radius: 5px;
        min-width: 40px;
      }

      .time {
        font-size: 2em;
        font-weight: bold;
      }

      @media(max-width:1200px) and (min-width:960px) {
        .pwelement .video-overlay {
          background: linear-gradient(to bottom, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0) 45%), linear-gradient(to right, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0) 45%) !important;
        }
      }

      @media(max-width:960px) {
        #countdownCustom {
          margin: 36px auto;
          padding: 15px;
        }
      }
        #trade-fair-date-custom {
        visibility:hidden;
        }
  </style>
  <p id="trade-fair-date-custom" data-trade-fair="[trade_fair_datetotimer]">[trade_fair_datetotimer]</p>
  <div id="countdownCustom"></div>

  <script>
    const tradeFairElement = document.querySelector("[data-trade-fair]");

    const tradeFairDateText = tradeFairElement.textContent.trim(); // Pobranie tekstu
    const tradeFairDate = new Date(tradeFairDateText); // Parsowanie daty

    function updateCountdown() {
      const now = new Date(); // Aktualny czas
      const timeDifference = tradeFairDate - now; // Różnica czasu w ms

      if (timeDifference <= 0) {
        document.getElementById("countdownCustom").innerHTML = "Wydarzenie już trwa lub minęło!";
        return;
      }

      const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
      const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

      document.getElementById("countdownCustom").innerHTML = `
          <div class="countdown-text">'. self::languageChecker('Do targów pozostało', 'The fair is still to come') .'</div>
          <div class="countdown-container">
              <div class="countdown-days">
                  <span class="time">${days}</span><br/>'. self::languageChecker('dni', 'days') .'
              </div>
              <div class="countdown-hours">
                  <span class="time">${hours}</span><br/>'. self::languageChecker('godzin', 'hours') .'
              </div>
              <div class="countdown-minutes">
                  <span class="time">${minutes}</span><br/>'. self::languageChecker('minut', 'minutes') .'
              </div>
              <div class="countdown-seconds">
                  <span class="time">${seconds}</span><br/>'. self::languageChecker('sekund', 'seconds') .'
              </div>
          </div>
      `;
    }
    setInterval(updateCountdown, 1000);

    document.addEventListener("DOMContentLoaded", function () {
        updateCountdown();
    });
  </script>';

  return $output;
?>