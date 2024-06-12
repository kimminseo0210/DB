<?php
// 관리자로만 들어올수 있으므로 따로 세션을 추가 하지 않음
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
// 학과ID를 받아옴
$DepartmentID = $_GET['DepartmentID'];
$sql = "SELECT DepartmentID, College FROM department WHERE DepartmentID='$DepartmentID'";
$ret = mysqli_query($con, $sql);

if ($ret) {
    $count = mysqli_num_rows($ret);
    if ($count == 0) {
        echo $DepartmentID." 아이디의 학과가 없음 !@.@!"."<br>";
        echo "<br><a href='sch.php'><--학과 정보 페이지</a>";
        exit();
    } else {
        $row = mysqli_fetch_assoc($ret);
        $CollegeName = htmlspecialchars($row['College']); // 학과 이름을 가져옴
    }
} else {
    echo "데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    echo "<br><a href='sch.php'><--학과 정보 페이지</a>";
    exit();
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>학과 정보 삭제</title>
</head>
<body>
<h1>학과 정보 삭제</h1>
<form method="post" action="delete_result_sch.php">
    학과 번호 : <?php echo htmlspecialchars($DepartmentID); ?><br>
    학과 이름 : <?php echo $CollegeName; ?><br><br>
    위 학과를 삭제 하겠습니까?
    <input type="hidden" name="DepartmentID" value="<?php echo htmlspecialchars($DepartmentID); ?>">
    <input type="submit" value="삭제">
    <input type="button" value="취소" onclick="window.location.href='sch.php'">
</form>
</body>
</html>
