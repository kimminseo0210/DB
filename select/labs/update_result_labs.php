<?php
// 결과 페이지로 세션을 따로 추가하지 않음
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
// 연구실ID와 학생
$labsID = $_POST["labsID"];
$studentNum = $_POST['studentNum'];

$sql = "UPDATE labstbl SET studentNum='$studentNum' WHERE labsID='$labsID'";

$ret = mysqli_query($con, $sql);

echo "<h1>연구실 정보 수정 결과</h1>";
if ($ret) {
    if (mysqli_affected_rows($con) > 0) {
        echo "데이터가 성공적으로 수정됨 !@.@!";
    } else {
        echo "바뀐 정보가 없습니다 !@.@!";
    }
} else {
    echo "데이터 수정 실패 !@.@!";
    echo "실패 원인".mysqli_error($con);
}
mysqli_close($con);
?>
<html>
<head>
    <title>연구실 정보 수정</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='labs.php'">
</body>
</html>