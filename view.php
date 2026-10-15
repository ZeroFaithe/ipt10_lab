<?php
require_once 'db_connect.php';
// The id comes from the URL (?id=...). It is a UUID string | trim it
// and reject empty input. Never cast it to an integer.
$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }
$stmt = $conn->prepare('SELECT id, first_name, last_name, birthday, sex, email, student_number, program, enrolment_date, created_at FROM students WHERE id = ?');
// A UUID is text, so the bind type must be a string, not an integer.
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
echo '<p>Student not found.</p>';
} else {
?>
<h2>Student Details</h2>
<p><strong>Student ID:</strong> <?= htmlspecialchars($row['id']) ?></p>
<p><strong>Full Name:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></p>
<p><strong>Birthday:</strong> <?= htmlspecialchars($row['birthday']) ?></p>
<p><strong>Sex:</strong> <?= htmlspecialchars($row['sex']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
<p><strong>Student Number:</strong> <?= htmlspecialchars($row['student_number']) ?></p>
<p><strong>Program:</strong> <?= htmlspecialchars($row['program']) ?></p>
<p><strong>Enrolment Date:</strong> <?= htmlspecialchars($row['enrolment_date']) ?></p>
<p><strong>Created At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
<?php
}
$stmt->close();
$conn->close();
?>