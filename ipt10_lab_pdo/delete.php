<?php
require_once 'config.php';
$id = trim($_POST['id'] ?? $_GET['id'] ?? '');
if ($id === '') {
    die('Invalid student ID');
}
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
if (!$pdo->inTransaction()) {
    $pdo->beginTransaction();
}
$d->execute([$id]);
if ($d->rowCount() > 0) {
    $pdo->commit();
    echo '<p>Student deleted successfully.</p>';
} else {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo '<p>Failed to delete student.</p>';
}
} else {
    echo '<h2>Delete Student</h2>';
    echo '<p>Are you sure you want to delete ' .
        htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '?</p>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
    echo '<button type="submit">Yes</button> ';
    echo '<button type="button" onclick="window.location.href=\'index.php\'">Cancel</button>';
    echo '</form>';
}
?>