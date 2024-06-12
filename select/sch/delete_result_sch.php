<?php
// 결과 페이지로 따로 세션을 받지 않음
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
// 학과ID를 받아옴
$DepartmentID = $_POST['DepartmentID'];

// 교수 정보 확인
$sql_check_professor = "SELECT * FROM professor WHERE departmentID='$DepartmentID'";
$result_check_professor = mysqli_query($con, $sql_check_professor);

// 학생 정보 확인
$sql_check_student = "SELECT * FROM student WHERE DepartmentID='$DepartmentID'";
$result_check_student = mysqli_query($con, $sql_check_student);

// 학생, 교수테이블에서 참조되는 정보가 있는지 확인 후 있다면 테이블로 출력
if (mysqli_num_rows($result_check_professor) > 0 || mysqli_num_rows($result_check_student) > 0) {
    echo "<h1>학과 정보를 삭제할 수 없습니다.</h1>";
    echo "<h2>학과 정보 삭제 전 다른 테이블을 먼저 변경/삭제 해주세요</h2>";

    // 교수 정보 출력
    if (mysqli_num_rows($result_check_professor) > 0) {
        echo "<h3>교수 정보</h3>";
        echo "<table border='1'>";
        echo "<tr><th>교 번</th><th>교수 이름</th></tr>";
        while ($row = mysqli_fetch_assoc($result_check_professor)) {
            echo "<tr>";
            echo "<td>" . $row['ProfessorID'] . "</td>";
            echo "<td>" . $row['ProfessorName'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // 학생 정보 출력
    if (mysqli_num_rows($result_check_student) > 0) {
        echo "<h3>학생 정보</h3>";
        echo "<table border='1'>";
        echo "<tr><th>학 번</th><th>학생 이름</th></tr>";
        while ($row = mysqli_fetch_assoc($result_check_student)) {
            echo "<tr>";
            echo "<td>" . $row['StudentID'] . "</td>";
            echo "<td>" . $row['StudentName'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // 수정화면 링크 추가
    echo "<br><a href='sch.php'><--수정화면</a>";
    exit();
}

// department 테이블에서 학과 정보 삭제
$sql = "DELETE FROM department WHERE DepartmentID='$DepartmentID'";
$ret = mysqli_query($con, $sql);

echo "<h1>학과 정보 삭제 결과</h1>";
if ($ret) {
    echo "데이터가 성공적으로 삭제됨 !@.@!";
} else {
    echo "데이터 삭제 실패 !@.@!";
    echo "실패 원인: " . mysqli_error($con);
}

// 연결 종료
mysqli_close($con);

// 수정화면 링크 출력
echo "<br><a href='sch.php'><--수정화면</a>";
?>
