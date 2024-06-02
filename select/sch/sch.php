<?php
// 세션 시작
session_start();
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
if (!$con) {
    die("연결 실패: " . mysqli_connect_error());
}

// department 테이블 조회
$sql = "SELECT * FROM department";
$ret = mysqli_query($con, $sql);
if ($ret === false) {
    echo "department 데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    exit();
}

// 각 학과별 교수 수 조회
$professor_count_sql = "SELECT departmentID, COUNT(*) as professor_count FROM professor GROUP BY departmentID";
$professor_count_ret = mysqli_query($con, $professor_count_sql);
if ($professor_count_ret === false) {
    echo "professor 데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    exit();
}

// 교수 수를 저장할 배열
$professor_counts = [];
while ($row = mysqli_fetch_assoc($professor_count_ret)) {
    $professor_counts[$row['departmentID']] = $row['professor_count'];
}

// 각 학과별 학생 수 조회
$student_count_sql = "SELECT departmentID, COUNT(*) as student_count FROM student GROUP BY departmentID";
$student_count_ret = mysqli_query($con, $student_count_sql);
if ($student_count_ret === false) {
    echo "student 데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    exit();
}

// 학생 수를 저장할 배열
$student_counts = [];
while ($row = mysqli_fetch_assoc($student_count_ret)) {
    $student_counts[$row['departmentID']] = $row['student_count'];
}

echo "<h1>학과 정보 검색 결과</h1>";

// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // 로그인한 사용자 ID 출력
    $userID = htmlspecialchars($_SESSION['userID']);
    $userRole = htmlspecialchars($_SESSION['role']);

    if ($userRole === 'admin') {
        $user_sql = "SELECT userName FROM user WHERE userID = '$userID'";
    } elseif ($userRole === 'student') {
        $user_sql = "SELECT studentName AS userName FROM student WHERE StudentID = '$userID'";
    } elseif ($userRole === 'professor') {
        $user_sql = "SELECT professorName AS userName FROM professor WHERE ProfessorID = '$userID'";
    }
    $user_ret = mysqli_query($con, $user_sql);
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
echo "<th>학과 번호</th> <th>학과</th> <th>학생 수</th> <th>교수 수</th>";
if (isset($userRole) && $userRole === 'admin') {
    echo " <th>수정/삭제</th>";
}
echo "</tr>";
while ($row = mysqli_fetch_array($ret)) {
    echo "<tr>";
    echo "<td>" . $row['DepartmentID'] . "</td>";
    echo "<td>" . $row['College'] . "</td>";
    $departmentID = $row['DepartmentID'];
    $student_count = isset($student_counts[$departmentID]) ? $student_counts[$departmentID] : 0;
    echo "<td>" . $student_count . "</td>";
    // 해당 학과의 교수 수 출력
    $professor_count = isset($professor_counts[$departmentID]) ? $professor_counts[$departmentID] : 0;
    echo "<td>" . $professor_count . "</td>";
    // 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 수정과 삭제 링크 표시
    if (isset($userRole) && $userRole === 'admin') {
        echo "<td>";
        echo "<a href='update_sch.php?DepartmentID=" . $row['DepartmentID'] . "'>수정</a> ";
        echo "<a href='delete_sch.php?DepartmentID=" . $row['DepartmentID'] . "'>삭제</a>";
        echo "</td>";
    }
    echo "</tr>";
}
mysqli_close($con);
echo "</table>";

// 세션에 loggedIn이 true로 설정되어 있는지 확인하여 관리자로 로그인한 경우에만 학과 추가 링크 표시
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    if ($userRole === 'admin') {
        echo "<br><a href='insert_sch.php'>학과 추가</a>";
    }
}
echo "<br><a href='../../main.php'>메인 페이지</a>";
?>

<html>
<head>
    <title>순천향대학교 학과 정보</title>
</head>
</html>
