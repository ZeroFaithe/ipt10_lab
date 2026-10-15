<?php
require_once 'db_connect.php';
$errors = [];
$first_name = $middle_name = $last_name = $email = $birthday = $sex = '';
$student_number = $program = $enrolment_date = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$sex = trim($_POST['sex'] ?? '');
$student_number = trim($_POST['student_number'] ?? '');
$program = trim($_POST['program'] ?? '');
$enrolment_date = trim($_POST['enrolment_date'] ?? '');

if ($first_name === '' || strlen($first_name) < 2 || strlen($first_name) > 100 || !preg_match('/^[A-Za-z\s]+$/', $first_name)) {
    $errors['first_name'] = 'First name must be 2-100 letters and spaces only.';
}
if ($last_name === '' || strlen($last_name) < 2 || strlen($last_name) > 100 || !preg_match('/^[A-Za-z\s]+$/', $last_name)) {
    $errors['last_name'] = 'Last name must be 2-100 letters and spaces only.';
}


if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email is required and must be valid.';
}
if ($birthday === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
    $errors['birthday'] = 'Birthday must use YYYY-MM-DD format.';
}
if (!in_array($sex, ['Male', 'Female'], true)) {
    $errors['sex'] = 'Select a valid sex.';
}
if ($student_number !== '' && (!ctype_alnum($student_number) || strlen($student_number) > 50)) {
    $errors['student_number'] = 'Student number must be alphanumeric and no more than 50 characters.';
}
if (!preg_match('/^.{0,200}$/us', $program)) {
    $errors['program'] = 'Program must be no more than 200 characters.';
}
if ($enrolment_date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $enrolment_date)) {
    $errors['enrolment_date'] = 'Enrolment date must use YYYY-MM-DD format.';
}
if (empty($errors)) {

    $stmt = $conn->prepare(
        'INSERT INTO students (id, first_name, middle_name, last_name, email, birthday, sex, student_number, program, enrolment_date)
         VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssssss', $first_name, $middle_name, $last_name, $email, $birthday, $sex, $student_number, $program, $enrolment_date);
$stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo '<p>Student created successfully!</p>';
    }
$first_name = $middle_name = $last_name = $email = $birthday = $sex = '';
$student_number = $program = $enrolment_date = '';
$stmt->close();
}
}
?>
<h2>Add New Student</h2>
<form method="POST">
<form method="POST">

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name"
           value="<?= htmlspecialchars($first_name) ?>" maxlength="100" required>
    <?= htmlspecialchars($errors['first_name'] ?? '') ?>
    <br>

    <label for="middle_name">Middle Name:</label>
    <input type="text" id="middle_name" name="middle_name"
           value="<?= htmlspecialchars($middle_name ?? '') ?>">
    <br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name"
           value="<?= htmlspecialchars($last_name) ?>" maxlength="100" required>
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
           value="<?= htmlspecialchars($student_number) ?>" maxlength="50">
    <?= htmlspecialchars($errors['student_number'] ?? '') ?>
    <br>

    <label for="program">Program:</label>
    <input type="text" id="program" name="program"
           value="<?= htmlspecialchars($program) ?>" maxlength="200">
    <?= htmlspecialchars($errors['program'] ?? '') ?>
    <br>

    <label for="enrolment_date">Enrolment Date:</label>
    <input type="date" id="enrolment_date" name="enrolment_date"
           value="<?= htmlspecialchars($enrolment_date ?? '') ?>" required>
    <?= htmlspecialchars($errors['enrolment_date'] ?? '') ?>
    <br>

    <button type="submit">Add Student</button>
</form>
<?php $conn->close(); ?>
