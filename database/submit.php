<?php
$host = 'localhost';
$db = 'volunteer';
$user = 'volunteer';
$pass = 'wh8tC6kXsMWxemTL';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $phone_number = $_POST['phone_number'];
    $activity_id = $_POST['activity'];

    $stmt = $pdo->prepare('SELECT volunteer_id FROM volunteers WHERE name = ? AND gender = ? AND birth_date = ? AND phone_number = ?');
    $stmt->execute([$name, $gender, $birth_date, $phone_number]);
    $volunteer = $stmt->fetch();

    if ($volunteer) {
        $volunteer_id = $volunteer['volunteer_id'];
    } else {
        $notes = '';
        $sql = "INSERT INTO volunteers (name, gender, birth_date, phone_number, notes) VALUES (:name, :gender, :birth_date, :phone_number, :notes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':gender' => $gender,
            ':birth_date' => $birth_date,
            ':phone_number' => $phone_number,
            ':notes' => $notes
        ]);

        $volunteer_id = $pdo->lastInsertId();
    }


    $stmt = $pdo->prepare('SELECT * FROM volunteer_activities WHERE volunteer_id = ? AND activity_id = ?');
    $stmt->execute([$volunteer_id, $activity_id]);
    $existing_relation = $stmt->fetch();

    if ($existing_relation) {
        echo "<script type='text/javascript'>
        alert('已报名过，请勿重复提交！');
        window.location.href = 'https://jzxys.com/volunteer.html';
        </script>";
    } else {
        $stmt = $pdo->prepare('INSERT INTO volunteer_activities (volunteer_id, activity_id) VALUES (?, ?)');
        $stmt->execute([$volunteer_id, $activity_id]);

        echo "<script type='text/javascript'>
        alert('报名成功！');
        window.location.href = 'https://jzxys.com/volunteer.html';
        </script>";
    }
}
