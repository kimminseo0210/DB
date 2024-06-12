<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html"; charset="UTF-8">
    <title>DB finalTest</title>
</head>
<body>
<h1>순천향대학교 정보 검색</h1>
<?php
session_start();
// 로그인한 사용자 ID 출력
// 세션이 존재하고 로그인 상태인 경우
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 결과에서 사용자 아이디, 권한 추출
    $userID = htmlspecialchars($_SESSION['userID']);
    $userRole = htmlspecialchars($_SESSION['role']);
    // DB 연결
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
    if ($user_ret && mysqli_num_rows($user_ret) == 1) {
        $user_row = mysqli_fetch_assoc($user_ret);
        $userName = htmlspecialchars($user_row['userName']);
        // 인삿말 출력
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
    // 로그아웃 폼 표시
    echo '<form action="login/logout.php" method="post">';
    echo '<input type="submit" value="로그아웃">';
    echo '</form>';
} else {
    echo "<p>게스트로 사용 중</p>";
    // 세션이 존재하지 않거나 로그인 상태가 아닌 경우, 로그인 및 회원가입 폼 표시
    echo '<form action="login/login.php" method="post">';
    echo '<input type="submit" value="로그인">';
    echo '</form>';
}
?>
<br><br>
<h2>학교 정보 찾기</h2>
<input type="button" value="1) 학과 정보" onclick="window.location.href='select/sch/sch.php'">
<input type="button" value="2) 강의실 정보 검색" onclick="window.location.href='select/classroom/classroom.php'">
<input type="button" value="3) 연구실 정보 검색" onclick="window.location.href='select/labs/labs.php'">
<input type="button" value="4) 교수 정보 검색" onclick="window.location.href='select/teacher/teacher.php'">
<?php
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    if ($userRole == 'professor' || $userRole == 'admin') {
        echo "<br><br>";
        echo "<h3>학생 관리</h3>";
        echo "<input type='button' value='학생 페이지' onclick=\"window.location.href='select/student/student.php'\">";
        echo "<br><br>";
        echo "<h3>삭제 정보</h3>";
        echo "<input type='button' value='삭제 정보' onclick=\"window.location.href='outUser/outUser.php'\">";
    }
}
?>
</body>
</html>
