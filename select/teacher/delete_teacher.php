<?php
// 관리자로 로그인을 했을 경우에만 들어올 수 있으므로 따로 세션을 받지 않음
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
// Professor의 아이디를 받아옴
$ProfessorID = $_GET['ProfessorID'];
$sql = "SELECT * FROM professor WHERE ProfessorID='$ProfessorID'";
$ret = mysqli_query($con, $sql);
if ($ret) {
    $count = mysqli_num_rows($ret);
    if ($count == 0) {
        echo $ProfessorID." 해당 교수가 없음 !@.@!"."<br>";
        echo "<br><a href='teacher.php'><--교수 정보 페이지</a>";
        exit();
    } else {
        $row = mysqli_fetch_array($ret);
        $ProfessorName = $row['ProfessorName'];
    }
} else {
    echo "데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    echo "<br><a href='teacher.php'><--교수 정보 페이지</a>";
    exit();
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
</head>
<body>
<h1>학생 정보 삭제</h1>
<form method="post" action="delete_result_teacher.php">
    교수 ID : <?php echo $ProfessorID; ?><br>
    교수 이름 : <?php echo $ProfessorName; ?>
    <br><br>
    위 학생 정보를 삭제 하겠습니까 ?
    <input type="hidden" name="ProfessorID" value="<?php echo $ProfessorID; ?>">
    <input type="submit" value="삭제">
    <input type="button" value="취소" onclick="window.location.href='teacher.php'">
</form>
</body>
</html>
