<?php
// 세션 시작
session_start();
// classroom 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
$sql = "
SELECT c.ClassroomID, c.Purpose, c.ProfessorID, p.ProfessorName
FROM classroom c
LEFT JOIN professor p ON c.ProfessorID = p.ProfessorID
";
$ret = mysqli_query($con, $sql);
// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "classroom 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}
echo "<h1>강의실 정보 검색 결과</h1>";

// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 결과에서 사용자 이름 추출
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
echo "<h3>강의실 예약은 로그인 이후 사용가능합니다</h3>";
echo "<table border='1'>";
echo "<tr>";
echo "<th>강의실 번호</th> <th>강의실 종류</th> <th>담당 교수</th>";
if (isset($userRole) && $userRole !== 'admin') {
    echo "<th>강의실 예약</th>";
}
echo "</tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>".$row['ClassroomID']."</td>";
    echo "<td>".$row['Purpose']."</td>";
    echo "<td>".$row['ProfessorName']."</td>";
    if (!in_array($row['Purpose'], ['실습실', '연구실', '교수사무실', '학과사무실'])) {
        if (isset($userRole) && $userRole !== 'admin') {
            echo "<td>";
            if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
                echo "<a href='reservation/classroom_reservation.php?ClassroomID=".$row['ClassroomID']."'>예약</a>";
            } else {
                echo "<a href='../../login/login.php' onclick='alert(\"로그인 이후 이용해 주세요\");'>예약</a>";
            }
            echo "</td>";
        }
    }
    // 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
    if (isset($userRole) && $userRole === 'admin') {
        echo "<td>";
        echo "<a href='update_classroom.php?ClassroomID=".$row['ClassroomID']."'>수정</a>";
        echo "</td>";
    }
    echo "</tr>";
}
mysqli_close($con);
echo "</table>";
echo "<br> <a href='../../main.php'><-- 메인 화면</a>";
?>
<html>
<head>
    <title>CSE 강의실 정보</title>
</head>
</html>
