<?php
session_start();
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// GET으로 전달된 연구실 ID를 가져옴
$LabID = $_GET['LabID'];

// 연구실 정보 조회 쿼리
$sql = "SELECT * FROM lab WHERE LabID='$LabID'";
$ret = mysqli_query($con, $sql);

// 데이터베이스 연결 체크
if (!$ret) {
    echo "데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    echo "<br><a href='labs.php'><--연구실 정보 페이지</a>";
    exit();
}

// 연구실 정보를 변수에 할당
$row = mysqli_fetch_array($ret);
$LabID = $row['LabID'];
$LabName = $row['LabName'];
$ProfessorID = $row['ProfessorID'];
$Field = $row['Field'];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
    <title>연구실 정보 수정</title>
</head>
<body>
<h1>연구실 정보 수정</h1>
<form method="post" action="update_result_labs.php">
    연구실 번호 : <input type="text" name="labsID" value="<?php echo $LabID ?>" readonly><br>
    연구실 이름 : <input type="text" name="labsName" value="<?php echo $LabName ?>" readonly><br>
    <input type="submit" value="정보 수정">
    <input type="button" value="취소" onclick="window.location.href='labs.php'">
</form>
</body>
</html>
