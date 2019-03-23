<?php
/**
 * Configuration for database connection
 *
 */
$host       = "localhost";
$username   = "svbrasiladmin";
$password   = "svbrasil1234";
$dbname     = "treinamentosvbrasil";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );