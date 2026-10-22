<?php
function writeAuditLog(PDO $pdo, int $userId, string $action, string $details = ''): void {
    $stmt = $pdo->prepare(
        "INSERT INTO audit_logs (user_id, action, details, ip_address, created_at)
         VALUES (:user_id, :action, :details, :ip_address, NOW())"
    );
    $stmt->execute([
        ':user_id' => $userId,
        ':action' => $action,
        ':details' => $details,
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);
}
?>
