<?php

// autoload.php
spl_autoload_register(function ($class) {
  $namespace = 'App\\';
  $class = ltrim($class, $namespace);
  $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
  if (file_exists($file)) {
    require $file;
  }
});