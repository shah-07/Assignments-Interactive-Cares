<?php

declare(strict_types=1);

spl_autoload_register(function ($className) {
  $baseDir = "classes/";
  require_once "{$baseDir}{$className}.php";
});