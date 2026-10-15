<?php
require_once 'config.php';

$id = trim($_GET['id'] ?? $_POST['id'] ?? '');

if ($id === '') {
    die('Invalid student ID');
}

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die('Student not found.');
}

$errors = [];

$first_name = trim($_POST['first_name'] ?? $student['first_name']);
$middle_name = trim($_POST['middle_name'] ?? $student['middle_name']);
$last_name = trim($_POST['last_name'] ?? $student['last_name']);
$birthday = trim($_POST['birthday'] ?? $student['birthday']);
$sex = trim($_POST['sex'] ?? $student['sex']);
$email = trim($_POST['email'] ?? $student['email']);
$student_number = trim($_POST['student_number'] ?? $student['student_number']);
$program = trim($_POST['program'] ?? $student['program']);
$enrolment_date = trim($_POST['enrolment_date'] ?? $student['enrolment_date']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $sql = 'UPDATE students
                SET first_name = ?, middle_name = ?, last_name = ?,
                    birthday = ?, sex = ?, email = ?, student_number = ?,
                    program = ?, enrolment_date = ?
                WHERE id = ?';

        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
        }

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $first_name,
                $middle_name,
                $last_name,
                $birthday,
                $sex,
                $email,
                $student_number,
                $program,
                $enrolment_date,
                $id
            ]);

            $pdo->commit();
            echo '<p>Student updated successfully.</p>';
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
?>

<h2>Edit Student</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

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
           value="<?= htmlspecialchars($enrolment_date) ?>" required>
    <?= htmlspecialchars($errors['enrolment_date'] ?? '') ?>
    <br>

    <button type="submit">Update Student</button>
</form>