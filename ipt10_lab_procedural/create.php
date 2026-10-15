<?php
require_once 'db_connect.php';
$errors = [];
$first_name = $last_name = $email = $birthday = $sex = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$sex = trim($_POST['sex'] ?? '');

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
if (empty($errors)) {

    $stmt = $conn->prepare(
        'INSERT INTO students (id, first_name, last_name, email, birthday, sex)
         VALUES (UUID(), ?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $first_name, $last_name, $email, $birthday, $sex);
$stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo '<p>Student created successfully!</p>';
    }
$first_name = $last_name = $email = $birthday = $sex = '';
$stmt->close();
}
}
?>
<h2>Add New Student</h2>
<form method="POST">
<form method="POST">

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name"
           value="<?= htmlspecialchars($first_name) ?>" required>
    <?= htmlspecialchars($errors['first_name'] ?? '') ?>
    <br>

    <label for="middle_name">Middle Name:</label>
    <input type="text" id="middle_name" name="middle_name"
           value="<?= htmlspecialchars($middle_name ?? '') ?>">
    <br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name"
           value="<?= htmlspecialchars($last_name) ?>" required>
    <?= htmlspecialchars($errors['last_name'] ?? '') ?>
    <br>

    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday"
           value="<?= htmlspecialchars($birthday) ?>" required>
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
    <input type="email" id="email" name="email"
           value="<?= htmlspecialchars($email) ?>" required>
    <?= htmlspecialchars($errors['email'] ?? '') ?>
    <br>

    <label for="student_number">Student Number:</label>
    <input type="text" id="student_number" name="student_number"
           value="<?= htmlspecialchars($student_number ?? '') ?>" required>
    <br>

    <label for="program">Program:</label>
    <input type="text" id="program" name="program"
           value="<?= htmlspecialchars($program ?? '') ?>" required>
    <br>

    <label for="enrolment_date">Enrolment Date:</label>
    <input type="date" id="enrolment_date" name="enrolment_date"
           value="<?= htmlspecialchars($enrolment_date ?? '') ?>" required>
    <br>

    <button type="submit">Add Student</button>
</form>
<?php $conn->close(); ?>
