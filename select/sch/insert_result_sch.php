<?php
// 관리자만 들어올수 있으므로 따로 세션을 추가 하지 않음
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// POST 요청으로 받아온 학과 이름 값
$College = $_POST['College'];

// schtbl 테이블에 department 값 추가
// 학과 아이디는 AUTO_INCREMENT로 받아옴
$sql = "INSERT INTO department (College) VALUES ('$College')";

$ret = mysqli_query($con, $sql);

echo "<h1>학과 정보 추가 결과</h1>";
if ($ret) {
    echo "학과 정보가 성공적으로 추가됨 !@.@!<br>";
} else {
    echo "데이터 추가 실패 !@.@!";
    echo "실패 원인: " . mysqli_error($con);
}

mysqli_close($con);
?>
<html>
<head>
    <title>학생 정보 수정</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='sch.php'">
</body>
</html>
