<?php
// 데이터베이스 연결
$con = mysqli_connect("localhost", "minseoUser", "0210", "cse_comu");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// POST 데이터 가져오기
$StudentName = $_POST['StudentName'];
$DOB = $_POST['DOB'];  // 생년월일 입력 받음
$Grade = $_POST['Grade'];
$DepartmentID = $_POST['DepartmentID'];
$labs = $_POST['labs'];
$ProfessorID = $_POST['ProfessorID'];

// labs가 빈 문자열이면 NULL로 처리
if ($labs === '') {
    $labs = 'NULL';
} else {
    $labs = "'$labs'";
}

// 생년월일을 'YYYY-MM-DD' 형식으로 변환
$DOB_formatted = substr($DOB, 0, 4) . '-' . substr($DOB, 4, 2) . '-' . substr($DOB, 6, 2);

// 데이터 삽입 쿼리 준비
$sql = "
INSERT INTO student (StudentName, Birthdate, Grade, DepartmentID, labID, AdvisorID, StudentPW) 
VALUES ('$StudentName', '$DOB_formatted', $Grade, '$DepartmentID', $labs, $ProfessorID, '')
";
$ret = mysqli_query($con, $sql);

echo "<h1>학생 정보 추가 결과</h1>";
if ($ret) {
    // 삽입된 학생의 StudentID 가져오기
    $StudentID = mysqli_insert_id($con);

    // StudentID를 해시화하여 StudentPW 생성
    $StudentPW = hash('sha256', $StudentID);

    // StudentPW 업데이트 쿼리 준비
    $sql_update = "UPDATE student SET StudentPW='$StudentPW' WHERE StudentID='$StudentID'";
    $ret_update = mysqli_query($con, $sql_update);

    if ($ret_update) {
        echo "학생 정보가 성공적으로 추가되고 비밀번호가 설정됨 !@.@!";
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
    <title>학생 정보 추가</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='student.php'">
</body>
</html>
