<?php
require_once 'config.php';
// Keep the identical $errors[] validation from Part 1. Only the INSERT changes.
$sql = 'INSERT INTO students
(id, first_name, middle_name, last_name, birthday, sex, email,
student_number, program, enrolment_date)
VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)';
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');
$sex = trim($_POST['sex'] ?? '');
$email = trim($_POST['email'] ?? '');
$student_number = trim($_POST['student_number'] ?? '');
$program = trim($_POST['program'] ?? '');
$enrolment_date = trim($_POST['enrolment_date'] ?? '');
$errors = [];

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

$data = [
    $first_name,
    $middle_name,
    $last_name,
    $birthday,
    $sex,
    $email,
    $student_number,
    $program,
    $enrolment_date
];
if (empty($errors)) {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $pdo->commit();
        if ($stmt->rowCount() > 0) {
            echo '<p>Student created successfully!</p>';
            $first_name = $middle_name = $last_name = $birthday = $sex = '';
            $email = $student_number = $program = $enrolment_date = '';
        }
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
} else {
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
}
