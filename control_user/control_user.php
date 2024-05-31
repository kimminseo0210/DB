<?php
session_start();
// classroom 테이블 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
$sql = "
    SELECT u.userID, u.userName, u.userPW, u.departmentID, u.authority, d.College
    FROM user u
    LEFT JOIN department d on u.departmentID = d.departmentID
";
$ret = mysqli_query($con, $sql);
// 연결 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
} else {
    //echo "classroom 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}
echo "<h1>권한 설정</h1>";
// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 로그인한 사용자 ID 출력
    $userID = htmlspecialchars($_SESSION['userID']);
    $user_con = mysqli_connect(
        "localhost",
        "minseoUser",
        "0210",
        "cse_comu"
    );
    $user_sql = "SELECT userName, authority FROM user WHERE userID = '$userID'";
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
}
echo "<table border='1'>";
echo "<tr>";
echo "<th>유저 이름</th> <th>유저 아이디</th> <th>유저 비번</th> <th>학과</th> <th>권한</th>";
echo "<tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>".$row['userName']."</td>";
    if ($userID == 'admin') {
        echo "<td>****</td>";
    } else {
        echo "<td>".$row['userID']."</td>";
    }
    echo "<td>****</td>";
    echo "<td>".$row['departmentID']." - ".$row['College']."</td>";
    echo "<td>".$row['authority']."</td>";
    // 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
        if ($row['userID'] != 'admin') {
            echo "<td>";
            echo "<a href='update_authority.php?userID=".$row['userID']."'>권한수정</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='../regist_info/regiout.php?userID=".$row['userID']."'>유저삭제</a>";
            echo "</td>";
        }
    }
    echo "</tr>";
}

mysqli_close($con);
echo "</table>";
echo "<br> <a href='../main.php'><-- 메인 화면</a>";