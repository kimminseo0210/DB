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

if (mysqli_connect_errno()) {
    echo "MySQL 연결 실패: " . mysqli_connect_error();
    exit();
}

// GET 파라미터로 전달된 ClassroomID와 timeSlot 값을 받음
if (isset($_GET['ClassroomID']) && isset($_GET['timeSlot'])) {
    $ClassroomID = $_GET['ClassroomID'];
    $timeSlot = $_GET['timeSlot'];
} else {
    echo "필수 파라미터가 누락되었습니다.";
    exit();
}

// 세션에서 사용자 정보를 가져옴
if (isset($_SESSION['userID']) && isset($_SESSION['role'])) {
    $UserID = $_SESSION['userID'];
    $UserRole = $_SESSION['role'];
} else {
    echo "세션에 사용자 정보가 설정되지 않았습니다.";
    exit();
}

// 현재 날짜와 결합하여 DATETIME 값 생성
$currentDate = date('Y-m-d');
$datetimeValue = $currentDate . ' ' . $timeSlot;

// 예약 취소 쿼리
$cancelSql = "
DELETE FROM classroomreservation
WHERE ClassroomID = '$ClassroomID' AND ReservationTime = '$datetimeValue' AND (
    (StudentID = '$UserID' AND '$UserRole' = 'student') OR 
    (ProfessorID = '$UserID' AND '$UserRole' = 'professor')
)
";

// 쿼리 실행 및 결과 확인
if (mysqli_query($con, $cancelSql)) {
    echo "<h1>예약 취소 완료</h1>";
    echo "<p>강의실 ID: $ClassroomID</p>";
    echo "<p>취소된 예약 시간: $datetimeValue</p>";
    echo "<p>예약이 성공적으로 취소되었습니다.</p>";
} else {
    echo "<h1>예약 취소 실패</h1>";
    echo "<p>강의실 ID: $ClassroomID</p>";
    echo "<p>취소하려는 예약 시간: $datetimeValue</p>";
    echo "<p>예약 취소에 실패하였습니다: " . mysqli_error($con) . "</p>";
}

// 데이터베이스 연결 종료
mysqli_close($con);
?>

<br> <a href='classroom_reservation.php?ClassroomID=<?php echo $ClassroomID; ?>'><-- 강의실 화면</a>
<br> <a href='../../../main.php'><-- 메인 화면</a>