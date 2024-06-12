<?php
// 관리자로 로그인을 한 경우에만 들어올수 있으므로 세션을 따로 받지 않음
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
// 학생ID를 받아옴
$studentID = $_GET['StudentID'];
$sql = "SELECT * FROM student WHERE StudentID='$studentID'";
$ret = mysqli_query($con, $sql);
if ($ret) {
    $count = mysqli_num_rows($ret);
    if ($count == 0) {
        echo $studentID . " 아이디의 학생이 없음 !@.@!" . "<br>";
        echo "<br><a href='student.php'><--학생 정보 페이지</a>";
        exit();
    } else {
        $row = mysqli_fetch_array($ret);
        $StudentName = $row['StudentName'];
    }
} else {
    echo "데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    echo "<br><a href='student.php'><--학생 정보 페이지</a>";
    exit();
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<h1>학생 정보 삭제</h1>
<form method="post" action="delete_result_student.php">
    학번 : <?php echo $studentID; ?><br>
    이름 : <?php echo $StudentName; ?><br><br>
    위 학생 정보를 삭제 하겠습니까 ?
    <input type="hidden" name="studentID" value="<?php echo $studentID; ?>">
    <input type="submit" value="삭제">
    <input type="button" value="취소" onclick="window.location.href='student.php'">
</form>
</body>
</html>
