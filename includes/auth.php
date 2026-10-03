<?php
declare(strict_types=1);
function current_user(): ?array { static $user=false; if ($user !== false) return $user; $id=(int)($_SESSION['user_id'] ?? 0); if (!$id) return $user=null; $s=db()->prepare('SELECT id,email,created_at FROM users WHERE id=?'); $s->execute([$id]); return $user=$s->fetch() ?: null; }
function require_auth(): array { $user=current_user(); if (!$user) { flash('error','Please sign in to continue.'); redirect('/login.php'); } return $user; }
function login_user(array $user): void { session_regenerate_id(true); $_SESSION['user_id']=(int)$user['id']; }
function logout_user(): void { $_SESSION=[]; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); }
function login_limited(string $email): bool { $s=db()->prepare('SELECT COUNT(*) FROM login_attempts WHERE email=? AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)'); $s->execute([$email]); return (int)$s->fetchColumn() >= 8; }
function record_login_attempt(string $email): void { $s=db()->prepare('INSERT INTO login_attempts(email) VALUES(?)'); $s->execute([$email]); }
