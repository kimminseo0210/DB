<?php
// 세션 시작
session_start();
// studenttbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
$sql = "
    SELECT s.StudentID, s.DepartmentID, s.StudentName, s.Grade, s.AdvisorID, s.labID, p.ProfessorName, l.LabName
    FROM student s
    LEFT JOIN Professor p ON s.AdvisorID = p.ProfessorID
    LEFT JOIN Lab l ON s.LabID = l.LabID
";
$ret = mysqli_query($con, $sql);
$user_sql = "SELECT userName FROM user";
$user_ret = mysqli_query($con, $user_sql);
$user_row = mysqli_fetch_array($user_ret);

// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "studenttbl 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}

echo "<h1>학생 정보 검색 결과</h1>";
echo "<table border='1'>";
echo "<tr>";
echo "<th>학생 번호</th> <th>학생 이름</th> <th>학과</th> <th>학년</th> <th>지도 교수</th> <th>소속 연구실</th>";
echo "<tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>" . $row['StudentID'] . "</td>";
    echo "<td>" . $row['StudentName'] . "</td>";
    echo "<td>" . $row['DepartmentID'] . "</td>";
    echo "<td>" . $row['Grade'] . "</td>";
    echo "<td>" . $row['AdvisorID'] . " - " . $row['ProfessorName'] . "</td>";
    echo "<td>" . $row['labID'] . " - ".$row['LabName']."</td>"; // 연구실 정보 표시
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        if ($userAuth == 'true') {
            echo "<td>";
            echo "<a href='update_student.php?studentID=".$row['studentID']."'>수정</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='delete_student.php?studentID=".$row['studentID']."'>삭제</a>";
            echo "</td>";
        }
    }
}
echo "</tr>";
mysqli_close($con);
echo "</table>";
// 관리자로 로그인한 경우 "학생 정보 추가" 버튼 표시
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    if ($userID == 'admin') {
        echo "<br><a href='insert_student.php'>학생 정보 추가</a>";
    }
}
echo "<br> <a href='../main.php'>메인 페이지</a>";
?>

<html>
<head>
    <title>학생 정보</title>
</head>
</html>