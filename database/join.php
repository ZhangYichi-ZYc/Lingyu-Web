<?php
$host = 'localhost';
$db = 'company_db';
$user = 'company_db';
$pass = '43HiLjdz4J2aGGni';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file);

    $sql = "INSERT INTO job_applications (name, gender, birthdate, email, phone, education_level, school, major, graduation_year, current_position, company_name, work_duration, responsibilities, skills, resume_path) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $_POST['name'],
        $_POST['gender'],
        $_POST['birthdate'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['education'],
        $_POST['school'],
        $_POST['major'],
        $_POST['graduation_year'],
        $_POST['current_position'],
        $_POST['company_name'],
        $_POST['work_duration'],
        $_POST['responsibilities'],
        $_POST['skills'],
        $target_file,
    ]);

    echo "<script type='text/javascript'>
    alert('申请已提交！');
    window.location.href = 'https://jzxys.com/join.html';
    </script>";
}
?>