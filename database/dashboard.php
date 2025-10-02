<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$host = 'localhost';
$db = 'volunteer';
$user = 'volunteer';
$pass = 'wh8tC6kXsMWxemTL';
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

$volunteer_activity_view = $pdo->query('SELECT * FROM volunteer_activity_view')->fetchAll();
$volunteers = $pdo->query('SELECT * FROM volunteers')->fetchAll();
$activities = $pdo->query('SELECT * FROM activities')->fetchAll();
$volunteer_activities = $pdo->query('SELECT * FROM volunteer_activities')->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>志愿者信息管理-仪表盘</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../img/icons/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../img/icons/favicon-32x32.png" />
    <link rel="shortcut icon" type="image/x-icon" href="../img/icons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../img/icons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="../img/icons/android-chrome-192x192.png" />
    <link rel="icon" type="image/png" sizes="512x512" href="../img/icons/android-chrome-512x512.png" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }

        h1,
        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #5d51c1;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px 0;
            background-color: #5d51c1;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #4b3fa1;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
        }

        input[type="text"] {
            padding: 5px;
            margin-right: 10px;
        }

        select {
            padding: 8px;
            margin: 5px 0;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
            color: #333;
            width: 200px;
            box-sizing: border-box;
        }

        select:focus {
            border-color: #5d51c1;
            outline: none;
        }

        option {
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>九州星愿社 志愿者数据管理系统</h1>
    <br><br>
    <h2>志愿者活动信息速览</h2>
    <table>
        <tr>
            <th>姓名</th>
            <th>性别</th>
            <th>年龄</th>
            <th>联系方式</th>
            <th>活动名称</th>
            <th>活动日期</th>
            <th>地点</th>
        </tr>
        <?php foreach ($volunteer_activity_view as $va) : ?>
            <tr>
                <td><?php echo htmlspecialchars($va['volunteer_name']); ?></td>
                <td><?php echo htmlspecialchars($va['gender']); ?></td>
                <td>
                    <?php
                    $birthDate = new DateTime($va['birth_date']);
                    $currentDate = new DateTime();
                    $age = $currentDate->diff($birthDate)->y;
                    echo htmlspecialchars($age);
                    ?>
                </td>
                <td><?php echo htmlspecialchars($va['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($va['activity_name']); ?></td>
                <td><?php echo htmlspecialchars($va['activity_date']); ?></td>
                <td><?php echo htmlspecialchars($va['location']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><br>
    <h2>志愿者信息</h2>
    <table>
        <tr>
            <th>志愿者ID</th>
            <th>姓名</th>
            <th>性别</th>
            <th>出生日期</th>
            <th>手机号</th>
            <th>备注</th>
            <th>操作</th>
        </tr>
        <?php foreach ($volunteers as $volunteer) : ?>
            <tr>
                <td><?php echo htmlspecialchars($volunteer['volunteer_id']); ?></td>
                <td><?php echo htmlspecialchars($volunteer['name']); ?></td>
                <td><?php echo htmlspecialchars($volunteer['gender']); ?></td>
                <td><?php echo htmlspecialchars($volunteer['birth_date']); ?></td>
                <td><?php echo htmlspecialchars($volunteer['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($volunteer['notes']); ?></td>
                <td>
                    <a href="edit_volunteer.php?id=<?php echo $volunteer['volunteer_id']; ?>">编辑</a>
                    <a href="delete_volunteer.php?id=<?php echo $volunteer['volunteer_id']; ?>" onclick="return confirm('删除志愿者信息后，相关的活动参与记录也将一并删除。\n确定要删除吗？')">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="add_volunteer.php" class="btn">新增志愿者</a>
    <br><br><br>
    <h2>活动信息</h2>
    <table>
        <tr>
            <th>活动ID</th>
            <th>活动日期</th>
            <th>地点</th>
            <th>操作</th>
        </tr>
        <?php foreach ($activities as $activity) : ?>
            <tr>
                <td><?php echo htmlspecialchars($activity['activity_id']); ?></td>
                <td><?php echo htmlspecialchars($activity['activity_date']); ?></td>
                <td><?php echo htmlspecialchars($activity['location']); ?></td>
                <td>
                    <a href="edit_activity.php?id=<?php echo $activity['activity_id']; ?>">编辑</a>
                    <a href="delete_activity.php?id=<?php echo $activity['activity_id']; ?>" onclick="return confirm('删除活动信息后，相关活动的参与记录也将一并删除。\n确定要删除吗？')">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="add_activity.php" class="btn">新增活动</a>
    <br><br><br>
    <h2>志愿者活动关联信息</h2>
    <table>
        <tr>
            <th>志愿者ID</th>
            <th>活动ID</th>
            <th>操作</th>
        </tr>
        <?php foreach ($volunteer_activities as $va) : ?>
            <tr>
                <td><?php echo htmlspecialchars($va['volunteer_id']); ?></td>
                <td><?php echo htmlspecialchars($va['activity_id']); ?></td>
                <td>
                    <a href="delete_volunteer_activity.php?volunteer_id=<?php echo $va['volunteer_id']; ?>&activity_id=<?php echo $va['activity_id']; ?>" onclick="return confirm('确定要删除吗？')">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="add_volunteer_activity.php" class="btn">新增志愿者活动关联</a>
    <br><br><br>
    <h2>快速查询</h2>
    <form method="get" action="dashboard.php">
        <label for="query_name">按姓名查询参与次数:</label>
        <input type="text" id="query_name" name="query_name">
        <input type="submit" value="查询" class="btn">
    </form>

    <form method="get" action="dashboard.php">
        <label for="query_activity_name">按活动查询参与人数:</label>
        <input type="text" id="query_activity_name" name="query_activity_name">
        <input type="submit" value="查询" class="btn">
    </form>
    <br><br><br>
    <h2>查询结果</h2>
    <?php
    if (isset($_GET['query_name']) && $_GET['query_name'] !== '') {
        $query_name = $_GET['query_name'];
        $stmt = $pdo->prepare('SELECT COUNT(*) AS activity_count FROM volunteer_activity_view WHERE volunteer_name = ?');
        $stmt->execute([$query_name]);
        $result = $stmt->fetch();
        if ($result) {
            echo "<h3>志愿者 " . htmlspecialchars($query_name) . " 参与了 " . htmlspecialchars($result['activity_count']) . " 次活动</h3>";
        } else {
            echo "<h3>未找到志愿者 " . htmlspecialchars($query_name) . " 的参与记录</h3>";
        }
    }

    if (isset($_GET['query_activity_name']) && $_GET['query_activity_name'] !== '') {
        $query_activity_name = $_GET['query_activity_name'];
        $stmt = $pdo->prepare('SELECT COUNT(DISTINCT volunteer_id) AS participant_count FROM volunteer_activity_view WHERE activity_name = ?');
        $stmt->execute([$query_activity_name]);
        $result = $stmt->fetch();
        if ($result) {
            echo "<h3>活动 " . htmlspecialchars($query_activity_name) . " 有 " . htmlspecialchars($result['participant_count']) . " 人参与</h3>";
        } else {
            echo "<h3>未找到活动 " . htmlspecialchars($query_activity_name) . " 的参与记录</h3>";
        }
    }
    ?>
</body>

</html>