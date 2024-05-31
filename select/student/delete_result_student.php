<?php
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
$studentID = $_POST['studentID'];

$sql = "DELETE FROM student WHERE StudentID='$studentID'";
$ret = mysqli_query($con, $sql);

echo "<h1>학생 정보 삭제 결과</h1>";
if ($ret) {
    echo "데이터가 성공적으로 삭제됨 !@.@!";
} else {
    echo "데이터 삭제 실패 !@.@!";
    echo "실패 원인: " . mysqli_error($con);
}
mysqli_close($con);
?>
<html>
<head>
    <title>학생 정보 삭제</title>
</head>
<body>
<br>
<a href='student.php'><--수정화면</a>
</body>
</html>
