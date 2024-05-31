<?php
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// POST 데이터 가져오기
$departmentID = $_POST['department'];
$ProfessorName = $_POST['ProfessorName'];
$Field  = $_POST['Field'];
$Office  = $_POST['Office'];
$Lab = $_POST['Lab'];

// labID가 빈 문자열인 경우 NULL로 설정
if ($Lab === '') {
    $Lab = 'NULL';
} else {
    $Lab = "'$Lab'";
}

// 데이터 삽입 쿼리 준비
$sql = "
INSERT INTO Professor (ProfessorName, departmentID, Field, Office, Lab, ProfessorPW) 
VALUES ('$ProfessorName', '$departmentID', '$Field', '$Office', $Lab, '')
";
$ret = mysqli_query($con, $sql);

echo "<h1>교수 정보 추가 결과</h1>";
if ($ret) {
    // 삽입된 교수의 ProfessorID 가져오기
    $ProfessorID = mysqli_insert_id($con);

    // ProfessorID를 해시화하여 ProfessorPW 생성
    $ProfessorPW = hash('sha256', $ProfessorID);

    // ProfessorPW 업데이트 쿼리 준비
    $sql_update = "UPDATE Professor SET ProfessorPW='$ProfessorPW' WHERE ProfessorID='$ProfessorID'";
    $ret_update = mysqli_query($con, $sql_update);

    if ($ret_update) {
        echo "교수 정보가 성공적으로 추가되고 비밀번호가 설정됨 !@.@!";
    } else {
        echo "비밀번호 설정 실패 !@.@!";
        echo "실패 원인: " . mysqli_error($con);
    }
} else {
    echo "데이터 추가 실패 !@.@!";
    echo "실패 원인: " . mysqli_error($con);
}
mysqli_close($con);
?>
<html>
<head>
    <title>교수 정보 추가</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='teacher.php'">
</body>
</html>
