<?php
// 세션 시작
session_start();
// teacher 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
$sql = "
SELECT p.ProfessorID, p.ProfessorName, p.departmentID, p.Field, p.Office, l.LabID, l.LabName , d.College
FROM professor p 
LEFT JOIN lab l ON p.ProfessorID = l.ProfessorID
LEFT JOIN department d on p.departmentID = d.DepartmentID
";
$ret = mysqli_query($con, $sql);
// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "teachertbl 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}
echo "<h1>교수 정보 검색 결과</h1>";

// 세션에 loggedIn이 true로 설정되어 있는지 확인
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 로그인한 사용자 ID, 권한 추출
    $userID = htmlspecialchars($_SESSION['userID']);
    $userRole = htmlspecialchars($_SESSION['role']);
    $user_con = mysqli_connect(
            "localhost",
            "minseoUser",
            "0210",
            "cse_comu"
    );
    if ($userRole === 'admin') {
        $user_sql = "SELECT userName FROM user WHERE userID = '$userID'";                               // 권한이 'admin'일 경우
    } elseif ($userRole === 'student') {
        $user_sql = "SELECT studentName AS userName FROM student WHERE StudentID = '$userID'";          // 권한이 'student'일 경우
    } elseif ($userRole === 'professor') {
        $user_sql = "SELECT professorName AS userName FROM professor WHERE ProfessorID = '$userID'";    // 권한이 'professor'일 경우
    }
    $user_ret = mysqli_query($user_con, $user_sql);
    // 결과에서 이름과 권한 추출
    if ($user_ret && mysqli_num_rows($user_ret) == 1) {
        $user_row = mysqli_fetch_assoc($user_ret);
        $userName = htmlspecialchars($user_row['userName']);
        // 인삿말
        if ($userRole === 'professor') {
            echo "<p>환영합니다, ".$userName." 교수님!</p>";
        } else if ($userRole === 'student') {
            echo "<p>환영합니다, ".$userName."님!</p>";
        } else {
            echo "<p>환영합니다, ".$userName."님!</p>";
        }
    } else {
        echo "<p>사용자 정보를 가져오는 데 실패했습니다.</p>";
    }
} else {    // 세션이 존재하지 않거나, 로그인이 아닌경우 출력
    echo "<p>게스트로 로그인됨</p>";
}
echo "<table border='1'>";
echo "<tr>";
echo "<th>학과</th> <th>교수실</th> <th>교번</th> <th>교수 이름</th> <th>연구실</th> <th>연구 분야</th>";
echo "<tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>" . $row['College']."</td>";
    echo "<td>" . $row['Office']."</td>";
    echo "<td>" . $row['ProfessorID']."</td>";
    echo "<td>" . $row['ProfessorName']."</td>";
    echo "<td>" . $row['LabName'] . "</td>";
    echo "<td>" . $row['Field']."</td>";
    // 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 삭제 링크 표시
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        if ($userRole == 'admin') {
            echo "<td>";
            echo "<a href='delete_teacher.php?ProfessorID=".$row['ProfessorID']."'>삭제</a>";
            echo "</td>";
        }
    }
}
mysqli_close($con);
echo "</table>";
// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 추가 링크 표시
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    if ($userRole == 'admin') {
        echo "<br> <a href='insert_teacher.php'><-- 교수 정보 추가</a><br>";
    }
}
echo "<br> <a href='../../main.php'><-- 메인 화면</a>";
?>

<html>
<head>
    <title>CSE 교수 정보</title>
</head>
</html>
