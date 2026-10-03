<?php
declare(strict_types=1);
function csrf_token(): string { return $_SESSION['_csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void { if (!request_is_post() || !hash_equals($_SESSION['_csrf'] ?? '', $_POST['csrf_token'] ?? '')) fail(419, 'Request expired', 'Please return to the form and try again.'); }
