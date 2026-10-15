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
$stmt = $pdo->prepare($sql);
$stmt->execute($data);
if ($stmt->rowCount() > 0) {
    $pdo->commit();
    echo '<p>Student created successfully!</p>';
    $first_name = $middle_name = $last_name = $birthday = $sex = '';
    $email = $student_number = $program = $enrolment_date = '';
}
