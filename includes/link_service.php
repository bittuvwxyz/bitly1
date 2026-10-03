<?php
declare(strict_types=1);
function enforce_link_creation_limit(): void {
    $now = time();
    $recent = array_filter($_SESSION['_link_creations'] ?? [], static fn ($time) => $time > $now - 3600);
    if (count($recent) >= 20) throw new RuntimeException('Too many links created. Please try again in an hour.');
    $recent[] = $now; $_SESSION['_link_creations'] = $recent;
}
function create_short_link(?int $userId, string $destination, string $alias, ?string $expiry): array {
    enforce_link_creation_limit();
    $url=valid_url($destination); if (!$url) throw new InvalidArgumentException('Enter a complete http:// or https:// URL.');
    $expiresAt=null; if ($expiry !== '') { $date=DateTimeImmutable::createFromFormat('Y-m-d\\TH:i', $expiry); if (!$date || $date <= new DateTimeImmutable()) throw new InvalidArgumentException('Expiration must be a future date and time.'); $expiresAt=$date->format('Y-m-d H:i:s'); }
    if ($alias !== '' && (!valid_alias($alias) || reserved_alias($alias))) throw new InvalidArgumentException('Custom alias must be 3–64 letters, numbers, hyphens, or underscores and not reserved.');
    $pdo=db(); for($attempt=0;$attempt<12;$attempt++) { $code=$alias !== '' ? $alias : generate_code(); try { $s=$pdo->prepare('INSERT INTO short_links(user_id,code,destination_url,expires_at) VALUES(?,?,?,?)'); $s->execute([$userId,$code,$url,$expiresAt]); return ['id'=>(int)$pdo->lastInsertId(),'code'=>$code]; } catch(PDOException $e) { if (($e->errorInfo[1] ?? 0) === 1062) { if($alias !== '') throw new InvalidArgumentException('That custom alias is already in use.'); continue; } throw $e; } } throw new RuntimeException('Could not create a unique short code. Please try again.');
}
function owns_link(int $linkId, int $userId): ?array { $s=db()->prepare('SELECT * FROM short_links WHERE id=? AND user_id=?'); $s->execute([$linkId,$userId]); return $s->fetch() ?: null; }
