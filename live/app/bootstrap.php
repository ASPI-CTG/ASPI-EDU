<?php
declare(strict_types=1);
$configFile = __DIR__.'/config.local.php';
$config = require (is_file($configFile) ? $configFile : __DIR__.'/config.php');
if (session_status() !== PHP_SESSION_ACTIVE) { session_name('ASPI_NOTICE_ADMIN'); session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off','samesite'=>'Lax']); session_start(); }
try { $pdo = new PDO('mysql:host='.$config['db_host'].';dbname='.$config['db_name'].';charset=utf8mb4',$config['db_user'],$config['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]); } catch(Throwable $e) { http_response_code(500); if (basename($_SERVER['SCRIPT_NAME']??'')==='index.php') { throw $e; } exit('ডাটাবেজ সংযোগ হয়নি। app/config.local.php-এ ডাটাবেজের তথ্য যাচাই করুন।'); }
function e($v): string { return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function csrf_token(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_check(): void { if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')) { http_response_code(403); exit('নিরাপত্তা যাচাই ব্যর্থ হয়েছে। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।'); } }
function require_admin(): void { if(empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; } }
function date_bn($date): string { return date('d-m-Y',strtotime($date)); }
