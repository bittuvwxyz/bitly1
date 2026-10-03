<?php
declare(strict_types=1);
require_once __DIR__.'/functions.php';
ini_set('display_errors', config()['environment'] === 'development' ? '1' : '0');
error_reporting(E_ALL);
require_once __DIR__.'/../config/database.php';
start_secure_session(); security_headers();
require_once __DIR__.'/csrf.php'; require_once __DIR__.'/auth.php';
