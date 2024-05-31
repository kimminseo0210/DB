<?php
// 세션 시작
session_start();
// labstbl 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse"
);
$sql = "SELECT * FROM labstbl";
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

// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 로그인한 사용자 ID 출력
    $userID = htmlspecialchars($_SESSION['userID']);
    $user_con = mysqli_connect("localhost", "minseoUser", "0210", "cse");
    $user_sql = "SELECT userName, authority FROM membertbl WHERE userID = '$userID'";
    $user_ret = mysqli_query($user_con, $user_sql);
    // 결과에서 사용자 이름 추출
    if ($user_ret && mysqli_num_rows($user_ret) == 1) {
        $user_row = mysqli_fetch_assoc($user_ret);
        $userName = htmlspecialchars($user_row['userName']);
        $userAuth = htmlspecialchars($user_row['authority']);
        echo "<p>" . $userName . "님으로 로그인됨</p>";
    } else {
        echo "<p>사용자 정보를 가져오는 데 실패했습니다.</p>";
    }
} else {
    echo "<p>게스트로 로그인됨</p>";
}
echo "<table border='1'>";
echo "<tr>";
echo "<th>연구실 번호</th> <th>연구실 이름</th> <th>학과</th> <th>분야</th> <th>담당 교수</th> <th>인원</th>";
echo "<tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>".$row['labsID']."</td>";
    echo "<td>".$row['labsName']."</td>";
    echo "<td>".$row['department']."</td>";
    echo "<td>".$row['field']."</td>";
    echo "<td>".$row['teacherID']."</td>";
    echo "<td>".$row['studentNum']."</td>";
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        if ($userAuth == 'true') {
            echo "<td>";
            echo "<a href='update_labs.php?labsID=".$row['labsID']."'>수정</a>";
            echo "</td>";
        }
    }
    echo "</tr>";
}
mysqli_close($con);
echo "</table>";
echo "<br> <a href='../select.php'><-- 정보 검색 화면</a>";
echo "<br> <a href='../../main.php'><-- 메인 화면</a>";
?>

<html>
<head>
    <title>CSE 연구실 정보</title>
</head>
</html>