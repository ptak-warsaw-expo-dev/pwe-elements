<?php

function exhibitor_registering($entry_id){
    $sent = wp_mail(
        'marek.rumianek@warsawexpo.eu',
        'Wejściówka' ,
        $entry_id,
        array('Content-Type: text/html; charset=UTF-8', 'From ')
    );
}

add_action( 'pwelement_cron_hook', 'exhibitor_registering', 10, 3 );