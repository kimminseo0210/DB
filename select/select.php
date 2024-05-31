<?php
echo "<h1>CSE 학과 정보 검색</h1>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSE 학과 정보 검색</title>
</head>
<body>
<!-- 로그인 관리 -->
<?php
session_start();

// 데이터베이스 연결
$user_con = mysqli_connect("localhost", "minseoUser", "0210", "cse");

// 로그인한 경우
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 사용자 ID 가져오기
    $userID = mysqli_real_escape_string($user_con, $_SESSION['userID']);
    // 사용자 이름 조회 쿼리 실행
    $user_sql = "SELECT userName FROM membertbl WHERE userID = '$userID'";
    $user_result = mysqli_query($user_con, $user_sql);
    // 사용자 이름 출력
    if ($user_result && mysqli_num_rows($user_result) == 1) {
        $user_row = mysqli_fetch_assoc($user_result);
        $userName = htmlspecialchars($user_row['userName']);
        echo "<p>" . $userName . "님으로 로그인됨</p>";
    } else {
        echo "<p>사용자 정보를 가져오는 데 실패했습니다.</p>";
    }
} else {
    // 로그인하지 않은 경우
    echo "<p>게스트로 로그인됨</p>";
}

// 데이터베이스 연결 해제
mysqli_close($user_con);
?>
<form method="post">
    <input type="button" value="강의실 정보 검색" onclick="window.location.href='classroom/classroom.php'"><br><br>
    <input type="button" value="연구실 정보 검색" onclick="window.location.href='labs/labs.php'"><br><br>
    <input type="button" value="교수 정보 검색" onclick="window.location.href='teacher/teacher.php'"><br><br>
</form>
<a href='../main.php'>메인 페이지</a>
</body>
</html>
