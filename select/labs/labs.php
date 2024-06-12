<?php
// 세션 시작
session_start();
// labstbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
$sql = "
SELECT l.LabID, l.LabName, l.ProfessorID, l.Field, p.ProfessorName, 
(SELECT COUNT(*) FROM student s WHERE s.LabID = l.LabID) AS StudentCount
FROM lab l
LEFT JOIN Professor p ON l.ProfessorID = p.ProfessorID";                    // left join으로 교수ID에 해당하는 이름을 가져옴
                                                                            // StudentCount로 연구실에 속한 학생의 수를 받음
$ret = mysqli_query($con, $sql);
// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    echo "labstbl 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}
echo "<h1>연구실 정보 검색 결과</h1>";

// 세션이 존재하고 로그인 상태인 경우
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 결과에서 사용자ID, 권한 추출
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
} else {
    echo "<p>게스트로 로그인됨</p>";
}
echo "<table border='1'>";
echo "<tr>";
echo "<th>연구실 번호</th> <th>연구실 이름</th> <th>분야</th> <th>담당 교수</th> <th>인원</th>";
echo "</tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>".$row['LabID']."</td>";
    echo "<td>".$row['LabName']."</td>";
    echo "<td>".$row['Field']."</td>";
    echo "<td>".$row['ProfessorName']."</td>";
    echo "<td>".$row['StudentCount']."</td>";
    // 로그인이 되어있고 권한이 관리자이거나 교수인 경우 수정 표시
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        if ($userRole == 'admin' || $userRole == 'professor') {
            echo "<td>";
            echo "<a href='update_labs.php?LabID=".$row['LabID']."'>수정</a>";
            echo "</td>";
        }
    }
    echo "</tr>";
}
mysqli_close($con);
echo "</table>";
echo "<br> <a href='../../main.php'><-- 메인 화면</a>";
?>

<html>
<head>
    <title>CSE 연구실 정보</title>
</head>
</html>
