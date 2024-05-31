<?php
session_start();

// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// GET으로 전달된 강의실 ID를 가져옴
$ClassroomID = $_GET['ClassroomID'];
// 강의실 정보 조회 쿼리
$sql = "SELECT * FROM classroom WHERE ClassroomID='$ClassroomID'";
$ret = mysqli_query($con, $sql);

// 데이터베이스 연결 체크
if (!$ret) {
    echo "데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    echo "<br><a href='classroom.php'><--강의실 정보 페이지</a>";
    exit();
}

// 강의실 정보를 변수에 할당
$row = mysqli_fetch_array($ret);
$ClassroomID = $row['ClassroomID'];
$Purpose = $row['Purpose'];
$ProfessorID = $row['ProfessorID'];

// 지도교수 목록 조회 쿼리
$sql_teacher = "SELECT ProfessorID, ProfessorName FROM professor";
$result_teacher = mysqli_query($con, $sql_teacher);
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>강의실 정보 수정</title>
</head>
<body>
<h1>강의실 정보 수정</h1><br>
<form method="post" action="update_result_classroom.php">
    강의실 번호 : <input type="text" name="ClassroomID" value="<?php echo $ClassroomID ?>" readonly><br>
    강의실 종류 :
    <select name="Purpose">
        <option value="이론" <?php if ($Purpose == '이론') echo 'selected'; ?>>이론</option>
        <option value="실습" <?php if ($Purpose == '실습') echo 'selected'; ?>>실습</option>
        <option value="연구실" <?php if ($Purpose == '연구실') echo 'selected'; ?>>연구실</option>
        <option value="교수사무실" <?php if ($Purpose == '교수사무실') echo 'selected'; ?>>교수사무실</option>
    </select><br>
    담당 교수 :
    <select name="ProfessorID">
        <?php
        // 지도교수 목록 반복
        while ($row_teacher = mysqli_fetch_array($result_teacher)) {
            $teacherID = $row_teacher['ProfessorID'];
            $teachName = $row_teacher['ProfessorName'];
            $selected = ($teacherID == $ProfessorID) ? "selected" : "";
            echo "<option value='$teacherID' $selected> $teachName</option>";
        }
        ?>
    </select>
    <br><br><br><br>
    <input type="submit" value="정보 수정">
    <input type="button" value="취소" onclick="window.location.href='classroom.php'">
</form>
</body>
</html>
