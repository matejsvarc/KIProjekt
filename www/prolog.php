<?php
// každá webová stránka začíná prologem

// --- adresáře ---
define('INC', __DIR__ . '/include');        // include files
define('XML', __DIR__ . '/xml');            // XML files

// --- konfigurace stránek ---
define('TITLE', 'Mixolog');

// kde transformovat XML
define('TRANSFORM_SERVER_SIDE', true);

// --- session ---
session_start();  // ze všeho nejdříve začít seanci, pak používat $_SESSION
