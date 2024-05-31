<?php
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse"
);
$sql = "SELECT * FROM schtbl WHERE department='".$_GET['department']."'";
$ret = mysqli_query($con, $sql);
// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
    if ($count == 0) {
        echo $_GET['department']."아이디의 회원이 없음 !@.@!"."<br>";
        echo "<br><a href='sch.php'><--학생 정보 페이지</a>";
        exit();
    }
} else {
    echo "데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    echo "<br><a href='sch.php'><--학생 정보 페이지</a>";
    exit();
}
$row = mysqli_fetch_array($ret);
$department = $row['department'];
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
</head>
<body>
<h1>학생 정보 삭제</h1>
<form method="post" action="delete_result.php">
    학과 :
    <br><br>
    위 학생 정보를 삭제 하겠습니까 ?
    <input type="submit" value="삭제">
</form>
</body>
</html>