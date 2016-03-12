<?php

$query = 'http://109.93.172.151:8080/pictures/kurc.jpg';
$url = 'http://images.google.com/searchbyimage?image_url=' . $query;

$body = file_get_contents($url);
var_dump($body);