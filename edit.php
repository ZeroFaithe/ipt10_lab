<?php
require_once 'db_connect.php';
$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$errors = [];
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$sex = trim($_POST['sex'] ?? '');
$student_number = trim($_POST['student_number'] ?? '');
$program = trim($_POST['program'] ?? '');
$enrolment_date = trim($_POST['enrolment_date'] ?? '');

if (empty($first_name) || strlen($first_name) < 2 || strlen($first_name) > 100 || !preg_match('/^[a-zA-Z ]+$/', $first_name)) {
    $errors['first_name'] = 'First name must be 2-100 letters and spaces only.';
}
if (empty($last_name) || strlen($last_name) < 2 || strlen($last_name) > 100 || !preg_match('/^[a-zA-Z ]+$/', $last_name)) {
    $errors['last_name'] = 'Last name must be 2-100 letters and spaces only.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address.';
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
    $errors['birthday'] = 'Birthday must use YYYY-MM-DD format.';
}
if (!in_array($sex, ['Male', 'Female'], true)) {
    $errors['sex'] = 'Select a valid sex.';
}
if (!empty($errors)) {
?>
<h2>Edit Student</h2>
<form method="POST">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
    <?= htmlspecialchars($errors['first_name'] ?? '') ?>
    <br>
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
    <?= htmlspecialchars($errors['last_name'] ?? '') ?>
    <br>
    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($birthday) ?>" required>
    <?= htmlspecialchars($errors['birthday'] ?? '') ?>
    <br>
    <label for="sex">Sex:</label>
    <select id="sex" name="sex" required>
        <option value="">Select sex</option>
        <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
    </select>
    <?= htmlspecialchars($errors['sex'] ?? '') ?>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    <?= htmlspecialchars($errors['email'] ?? '') ?>
    <br>
    <label for="student_number">Student Number:</label>
    <input type="text" id="student_number" name="student_number" value="<?= htmlspecialchars($student_number) ?>" required>
    <?= htmlspecialchars($errors['student_number'] ?? '') ?>
    <br>
    <label for="program">Program:</label>
    <input type="text" id="program" name="program" value="<?= htmlspecialchars($program) ?>" required>
    <?= htmlspecialchars($errors['program'] ?? '') ?>
    <br>
    <label for="enrolment_date">Enrolment Date:</label>
    <input type="date" id="enrolment_date" name="enrolment_date" value="<?= htmlspecialchars($enrolment_date) ?>" required>
    <?= htmlspecialchars($errors['enrolment_date'] ?? '') ?>
    <br>
    <button type="submit">Update Student</button>
</form>
<?php
$conn->close();
exit;
}
$stmt = $conn->prepare('UPDATE students SET first_name = ?, last_name = ?, email = ?, birthday = ?, sex = ?, student_number = ?, program = ?, enrolment_date = ? WHERE id = ?');
$stmt->bind_param('sssssssss', $first_name, $last_name, $email, $birthday, $sex, $student_number, $program, $enrolment_date, $id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo '<p>Student updated successfully.</p>';
} else {
    echo '<p>No rows were changed; the submitted values may be unchanged or the student may no longer exist.</p>';
}
$stmt->close();
} else {
$stmt = $conn->prepare('SELECT first_name, last_name, email, birthday, sex, student_number, program, enrolment_date FROM students WHERE id = ?');
$stmt->bind_param('s', $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$student) {
    echo '<p>Student not found.</p>';
    $conn->close();
    exit;
}
$first_name = $student['first_name'];
$last_name = $student['last_name'];
$email = $student['email'];
$birthday = $student['birthday'];
$sex = $student['sex'];
$student_number = $student['student_number'];
$program = $student['program'];
$enrolment_date = $student['enrolment_date'];
?>
<h2>Edit Student</h2>
<form method="POST">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
    <br>
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
    <br>
    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($birthday) ?>" required>
    <br>
    <label for="sex">Sex:</label>
    <select id="sex" name="sex" required>
        <option value="">Select sex</option>
        <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
    </select>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    <br>
    <label for="student_number">Student Number:</label>
    <input type="text" id="student_number" name="student_number" value="<?= htmlspecialchars($student_number) ?>" required>
    <br>
    <label for="program">Program:</label>
    <input type="text" id="program" name="program" value="<?= htmlspecialchars($program) ?>" required>
    <br>
    <label for="enrolment_date">Enrolment Date:</label>
    <input type="date" id="enrolment_date" name="enrolment_date" value="<?= htmlspecialchars($enrolment_date) ?>" required>
    <br>
    <button type="submit">Update Student</button>
</form>
<?php
}
$conn->close();
?>
