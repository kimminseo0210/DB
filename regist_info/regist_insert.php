<?php
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

$userID = $_POST['userID'];
$userName = $_POST['userName'];
$userPW = $_POST['userPW'];
$departmentID = $_POST['department'];
$role = $_POST['role'];

$hashedPW = password_hash($userPW, PASSWORD_DEFAULT);

$sql = "
INSERT INTO user (userID, userName, userPW, departmentID, authority) 
VALUES ('$userID', '$userName', '$hashedPW', '$departmentID', '$role')
";

$ret = mysqli_query($con, $sql);

echo "<h1>회원 가입 결과</h1>";
if ($ret) {
    echo "회원 가입 성공 !@.@!";
} else {
    echo "회원 가입 실패 !@.@!";
    echo "실패 원인" . mysqli_error($con);
}
mysqli_close($con);
?>
<html>
<head>
    <title>회원 가입</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='../../main.php'">
</body>
</html>
