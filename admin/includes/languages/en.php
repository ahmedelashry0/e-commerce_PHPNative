<?php

function lang($phrase)
{
    static $lang = array(
        //Dashboard Phrases
        'HOME_ADMIN'    => 'Home',
        'CATEGORIES'    => 'Categories',
        'ITEMS'         => 'Items',
        'MEMBERS'       => 'Members',
        'COMMENTS'      => 'Statistics',
        'LOGS'          => 'Logs',
    );
    return $lang[$phrase];
}
