<?php

require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

$timezone = 'America/Lima';

date_default_timezone_set($timezone);

dd(Carbon::now());
