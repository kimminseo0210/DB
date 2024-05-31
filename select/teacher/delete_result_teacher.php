<?php
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// POST로 전달된 교수 ID 가져오기
$ProfessorID = $_POST['ProfessorID'];

// 교수가 참조되는 테이블 검사
$sql_check_classroom = "SELECT * FROM classroom WHERE ProfessorID='$ProfessorID'";
$result_check_classroom = mysqli_query($con, $sql_check_classroom);
// 연구실
$sql_check_labs = "SELECT * FROM lab WHERE ProfessorID='$ProfessorID'";
$result_check_labs = mysqli_query($con, $sql_check_labs);
// 학생
$sql_check_student = "SELECT * FROM student WHERE AdvisorID='$ProfessorID'";
$result_check_student = mysqli_query($con, $sql_check_student);
// 참조되는 테이블이 있는지 확인
if (mysqli_num_rows($result_check_classroom) > 0 || mysqli_num_rows($result_check_labs) > 0 || mysqli_num_rows($result_check_student) > 0) {
    echo "<h1>교수 정보를 삭제 할 수 없습니다.</h1>";
    echo "<h2>교수 정보 삭제 전 다른 테이블을 먼저 변경/삭제 해주세요</h2>";
    // 강의실 정보 출력
    if (mysqli_num_rows($result_check_classroom) > 0) {
        echo "<h3>강의실 정보</h3>";
        echo "<table border='1'>";
        echo "<tr><th>강의실 번호</th><th>강의실 이름</th></tr>";
        while ($row = mysqli_fetch_assoc($result_check_classroom)) {
            echo "<tr>";
            echo "<td>" . $row['ClassroomID'] . "</td>";
            echo "<td>" . $row['Purpose'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    // 연구실 정보 출력
    if (mysqli_num_rows($result_check_labs) > 0) {
        echo "<h3>연구실 정보</h3>";
        echo "<table border='1'>";
        echo "<tr><th>연구실 번호</th><th>연구실 이름</th></tr>";
        while ($row = mysqli_fetch_assoc($result_check_labs)) {
            echo "<tr>";
            echo "<td>" . $row['LabID'] . "</td>";
            echo "<td>" . $row['LabName'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    // 학생 정보 출력
    if (mysqli_num_rows($result_check_student) > 0) {
        echo "<h3>학생 정보</h3>";
        echo "<table border='1'>";
        echo "<tr><th>학번</th><th>이름</th></tr>";
        while ($row = mysqli_fetch_assoc($result_check_student)) {
            echo "<tr>";
            echo "<td>" . $row['StudentID'] . "</td>";
            echo "<td>" . $row['StudentName'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "<br><a href='teacher.php'><--수정화면</a>";
    exit();
}

// 참조되는 테이블이 없으면 삭제 실행
$sql_delete = "DELETE FROM Professor WHERE ProfessorID='$ProfessorID'";
$ret = mysqli_query($con, $sql_delete);

echo "<h1>교수 정보 삭제 결과</h1>";
if ($ret) {
    echo "데이터가 성공적으로 삭제됨 !@.@!";
} else {
    echo "데이터 삭제 실패 !@.@!";
    echo "실패 원인: " . mysqli_error($con);
}
mysqli_close($con);
echo "<br><a href='teacher.php'><--수정화면</a>";
?>