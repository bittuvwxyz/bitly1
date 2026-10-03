<?php
declare(strict_types=1);
function config(): array { static $config; return $config ??= require __DIR__.'/../config/config.php'; }
function start_secure_session(): void { if (session_status() === PHP_SESSION_ACTIVE) return; $c=config(); session_name($c['session_name']); session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),'httponly'=>true,'samesite'=>'Lax']); session_start(); }
function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function base_url(): string { return config()['url']; }
function short_url(string $code): string { return base_url().'/'.rawurlencode($code); }
function flash(string $key, ?string $message=null): ?string { if ($message !== null) { $_SESSION['_flash'][$key]=$message; return null; } $v=$_SESSION['_flash'][$key] ?? null; unset($_SESSION['_flash'][$key]); return $v; }
function redirect(string $path): never { header('Location: '.base_url().$path, true, 303); exit; }
function request_is_post(): bool { return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'; }
function valid_url(string $url): ?string { $url=trim($url); if (!filter_var($url,FILTER_VALIDATE_URL)) return null; $p=parse_url($url); if (!isset($p['scheme'],$p['host']) || !in_array(strtolower($p['scheme']),['http','https'],true)) return null; return $url; }
function valid_alias(string $code): bool { return (bool)preg_match('/^[A-Za-z0-9_-]{3,64}$/', $code); }
function reserved_alias(string $code): bool { return in_array(strtolower($code), ['index','login','register','logout','dashboard','analytics','create-link','redirect','assets','api','favicon.ico'],true); }
function generate_code(int $length=7): string { $alphabet='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789'; $out=''; for($i=0;$i<$length;$i++) $out.=$alphabet[random_int(0,strlen($alphabet)-1)]; return $out; }
function security_headers(): void { header('X-Content-Type-Options: nosniff'); header('X-Frame-Options: DENY'); header('Referrer-Policy: strict-origin-when-cross-origin'); header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'"); }
function fail(int $status, string $title, string $message): never { http_response_code($status); require __DIR__.'/header.php'; echo '<main class="narrow page-error"><h1>'.e($title).'</h1><p>'.e($message).'</p><a class="button" href="'.e(base_url()).'">Return home</a></main>'; require __DIR__.'/footer.php'; exit; }
