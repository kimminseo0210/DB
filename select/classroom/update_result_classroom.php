<?php
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// POST 데이터 가져오기
$ClassroomID = $_POST['ClassroomID'];
$Purpose = $_POST['Purpose'];
$ProfessorID = $_POST['ProfessorID'];

// 데이터 수정 쿼리 준비
$sql = "UPDATE classroom SET Purpose='$Purpose', ProfessorID='$ProfessorID' WHERE ClassroomID='$ClassroomID'";

$ret = mysqli_query($con, $sql);

echo "<h1>강의실 정보 수정 결과</h1>";
if ($ret) {
    if (mysqli_affected_rows($con) > 0) {
        echo "데이터가 성공적으로 수정됨 !@.@!";
    } else {
        echo "바뀐 정보가 없습니다 !@.@!";
    }
} else {
    echo "데이터 수정 실패 !@.@!";
    echo "실패 원인: " . mysqli_error($con);
}
mysqli_close($con);
?>
<html>
<head>
    <title>강의실 정보 수정</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='classroom.php'">
</body>
</html>
