<?php
require_once 'config.php';
// Keep your two-step confirm-then-delete flow from Part 1.
// Step 1 | confirm screen
$stmt = $pdo->prepare('SELECT first_name, last_name FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) {
    echo '<p>Student not found.</p>';
    exit;
}
// Step 2 | deletion, only after the POST confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$d = $pdo->prepare('DELETE FROM students WHERE id = ?');
$d->execute([$id]);
if ($d->rowCount() > 0) {
    $pdo->commit();
    echo '<p>Student deleted successfully.</p>';
} else {
    echo '<p>Failed to delete student.</p>';
}
}
?>