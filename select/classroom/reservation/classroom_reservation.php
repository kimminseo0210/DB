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

if (mysqli_connect_errno()) {
    echo "MySQL 연결 실패: " . mysqli_connect_error();
    exit();
}

$ClassroomID = $_GET['ClassroomID'];

$sql = "
SELECT c.ClassroomID, c.Purpose
FROM classroom c
WHERE c.ClassroomID = '$ClassroomID'
";
$ret = mysqli_query($con, $sql);

// 쿼리 실행 체크
if ($ret) {
    $count = mysqli_num_rows($ret);
    if ($count == 0) {
        echo "해당 강의실이 없습니다.";
        exit();
    }
} else {
    echo "classroom 데이터 검색 실패"."<br>";
    echo "실패 원인 : ".mysqli_error($con);
    exit();
}

echo "<h1>강의실 예약</h1>";

// 세션에 loggedIn이 true로 설정되어 있는지 확인
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

    // 사용자 ID로 이미 예약된 총 시간을 계산
    $totalReservationTimeSql = "
    SELECT SUM(TIMESTAMPDIFF(MINUTE, ReservationTime, ADDTIME(ReservationTime, '1:00:00'))) AS totalMinutes
    FROM classroomreservation
    WHERE (StudentID = '$userID' OR ProfessorID = '$userID')
    ";
    $totalReservationTimeRet = mysqli_query($con, $totalReservationTimeSql);
    $totalReservedMinutes = 0;
    if ($totalReservationTimeRet) {
        $totalRow = mysqli_fetch_assoc($totalReservationTimeRet);
        $totalReservedMinutes = $totalRow['totalMinutes'] ? $totalRow['totalMinutes'] : 0;
    }
    $maxReservationMinutes = 180; // 3시간 = 180분
}
echo "<h2>$ClassroomID 예약</h2><br>";

// 최대 예약 시간을 넘었는지 여부에 따라 메시지 표시
if ($totalReservedMinutes >= $maxReservationMinutes) {
    echo "<p>한 사람당 최대 3시간 이용이 가능합니다</p>";
}

// 시간 테이블 생성
echo "<table border='1'>";
echo "<tr><th>시간</th><th>상태</th><th>예약 상황</th><th>취소</th></tr>";

// 9시부터 18시까지 1시간 단위로 시간 나누기
for ($hour = 9; $hour < 18; $hour++) {
    $startTime = sprintf("%02d:00:00", $hour);
    $endHour = $hour + 1;
    $endTime = sprintf("%02d:00:00", $endHour);
    $timeSlot = sprintf("%02d:00 - %02d:00", $hour, $endHour);

    // classroomreservation 테이블에서 해당 시간 슬롯에 대한 예약 상태 확인
    $reservationSql = "
    SELECT StudentID, ProfessorID 
    FROM classroomreservation 
    WHERE ClassroomID = '$ClassroomID' 
    AND TIME(reservationTime) = '$startTime'
    ";
    $reservationRet = mysqli_query($con, $reservationSql);
    $reservationStatus = "예약 가능";
    $reservationDetail = "";
    $reservationLink = "<a href='classroom_reservation_result.php?ClassroomID=$ClassroomID&timeSlot=$startTime'>예약 가능</a>";
    $cancelLink = "";
    $reservationRow = null; // 변수 초기화
    if ($reservationRet) {
        if (mysqli_num_rows($reservationRet) > 0) {
            $reservationRow = mysqli_fetch_assoc($reservationRet);
            if ($reservationRow['StudentID'] || $reservationRow['ProfessorID']) {
                $reservationStatus = "예약됨";
                if ($reservationRow['StudentID']) {
                    // 학생의 이름 가져오기
                    $studentID = $reservationRow['StudentID'];
                    $studentSql = "SELECT studentName FROM student WHERE StudentID = '$studentID'";
                    $studentRet = mysqli_query($con, $studentSql);
                    if ($studentRet && mysqli_num_rows($studentRet) == 1) {
                        $studentRow = mysqli_fetch_assoc($studentRet);
                        $reservationDetail = $reservationRow['StudentID'] . " - " . $studentRow['studentName'];
                    }
                } elseif ($reservationRow['ProfessorID']) {
                    // 교수의 이름 가져오기
                    $professorID = $reservationRow['ProfessorID'];
                    $professorSql = "SELECT professorName FROM professor WHERE ProfessorID = '$professorID'";
                    $professorRet = mysqli_query($con, $professorSql);
                    if ($professorRet && mysqli_num_rows($professorRet) == 1) {
                        $professorRow = mysqli_fetch_assoc($professorRet);
                        $reservationDetail = $reservationRow['ProfessorID'] . " - " . $professorRow['professorName'];
                    }
                }
                if ($reservationRow['StudentID'] == $userID || $reservationRow['ProfessorID'] == $userID) {
                    $reservationLink = "<a href='cancel_reservation.php?ClassroomID=$ClassroomID&timeSlot=$startTime'>취소</a>";
                } else {
                    $reservationLink = $reservationStatus; // 다른 사용자의 예약은 링크 비활성화
                }
            }
        }
    } else {
        echo "예약 데이터 검색 실패"."<br>";
        echo "실패 원인 : ".mysqli_error($con);
        exit();
    }

    echo "<tr>";
    if ($totalReservedMinutes >= $maxReservationMinutes && !($reservationRow && ($reservationRow['StudentID'] == $userID || $reservationRow['ProfessorID'] == $userID))) {
        echo "<td>$timeSlot</td><td>예약 불가</td>";
    } else {
        echo "<td>$timeSlot</td><td>$reservationLink</td>";
    }
    echo "<td>$reservationDetail</td>";
    echo "<td>$cancelLink</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($con);
echo "<br> <a href='../classroom.php'><-- 강의실 화면</a>";
?>
