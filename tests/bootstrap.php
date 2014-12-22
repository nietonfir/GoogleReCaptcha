<?php

if (!@include_once(__DIR__.'/../vendor/autoload.php'))
    throw new \RuntimeException("Composer's autoload.php was not in /vendor. Run 'composer --install --dev'");
