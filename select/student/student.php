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
    SELECT s.StudentID, s.DepartmentID, s.StudentName, s.Grade, s.AdvisorID, s.labID, t.ProfessorName , l.LabName, d.College
    FROM student s
    LEFT JOIN professor t ON s.AdvisorID = t.ProfessorID
    LEFT JOIN lab l ON s.labID = l.LabID
    LEFT JOIN department d on s.DepartmentID = d.DepartmentID
";
$ret = mysqli_query($con, $sql);

// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "studenttbl 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}

echo "<h1>학생 정보 검색 결과</h1>";

// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 로그인한 사용자 ID 출력
    $userID = htmlspecialchars($_SESSION['userID']);
    $userRole = htmlspecialchars($_SESSION['role']);
    $user_con = mysqli_connect(
            "localhost",
            "minseoUser",
            "0210",
            "cse_comu"
    );
    if ($userRole === 'admin') {
        $user_sql = "SELECT userName FROM user WHERE userID = '$userID'";
    } elseif ($userRole === 'student') {
        $user_sql = "SELECT studentName AS userName FROM student WHERE StudentID = '$userID'";
    } elseif ($userRole === 'professor') {
        $user_sql = "SELECT professorName AS userName FROM professor WHERE ProfessorID = '$userID'";
    }
    $user_ret = mysqli_query($user_con, $user_sql);
    // 결과에서 사용자 이름 추출
    if ($user_ret && mysqli_num_rows($user_ret) == 1) {
        $user_row = mysqli_fetch_assoc($user_ret);
        $userName = htmlspecialchars($user_row['userName']);
        echo "<p>" . $userName . "님으로 로그인됨</p>";
    } else {
        echo "<p>사용자 정보를 가져오는 데 실패했습니다.</p>";
    }
} else {
    echo "<p>게스트로 로그인됨</p>";
}
echo "<table border='1'>";
echo "<tr>";
echo "<th>학과</th> <th>학번</th> <th>학생 이름</th> <th>학년</th> <th>지도 교수</th> <th>소속 연구실</th>";
echo "<tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>" . $row['College'] . "</td>";
    echo "<td>" . $row['StudentID'] . "</td>";
    echo "<td>" . $row['StudentName'] . "</td>";
    echo "<td>" . $row['Grade'] . "</td>";
    echo "<td>" . $row['ProfessorName'] . "</td>";
    echo "<td>" . $row['LabName']."</td>"; // 연구실 정보 표시
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        if ($userRole == 'admin'|| $userRole == 'professor') {
            echo "<td>";
            echo "<a href='update_student.php?StudentID=".$row['StudentID']."'>수정</a>";
            echo "</td>";
            if ($userRole == 'admin') {
                echo "<td>";
                echo "<a href='delete_student.php?StudentID=".$row['StudentID']."'>삭제</a>";
                echo "</td>";
            }
        }
    }
}
echo "</tr>";
mysqli_close($con);
echo "</table>";
// 관리자로 로그인한 경우 "학생 정보 추가" 버튼 표시
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    if ($userRole == 'admin') {
        echo "<br><br><input type='button' value='학생 정보 추가' onclick=\"window.location.href='insert_student.php'\">";
    }
}
echo "<input type='button' value='메인 페이지' onclick=\"window.location.href='../../main.php'\">";

?>

<html>
<head>
    <title>학생 정보</title>
</head>
</html>