<?php
// 결과 페이지로 따로 세션을 받지 않음
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// POST로 전달된 데이터 가져오기
$StudentID = $_POST['studentID'];
$studentName = $_POST['studentName'];
$grade = $_POST['grade'];
$department = $_POST['department'];
$labs = $_POST['labs'];
$AdvisorID = $_POST['teacherID'];

// labID가 빈 문자열인 경우 NULL로 설정
if ($labs === '') {
    $labs = 'NULL';
} else {
    $labs = "'$labs'";
}

// 데이터베이스 업데이트 쿼리 생성
$sql = "UPDATE student SET DepartmentID='$department', LabID=$labs, AdvisorID='$AdvisorID' WHERE StudentID='$StudentID'";

// 쿼리 실행
$ret = mysqli_query($con, $sql);

echo "<h1>학생 정보 수정 결과</h1>";
if ($ret) {
    if (mysqli_affected_rows($con) > 0) {
        echo "데이터가 성공적으로 수정됨 !@.@!";
    } else {
        echo "바뀐 정보가 없습니다 !@.@!";
    }
} else {
    echo "데이터 수정 실패 !@.@!";
    echo "실패 원인: ".mysqli_error($con);
}
mysqli_close($con);
?>
<html>
<head>
    <title>학생 정보 수정</title>
</head>
<body>
<br>
<input type="button" value="완료" onclick="window.location.href='student.php'">
</body>
</html>
