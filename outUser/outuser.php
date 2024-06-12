<?php
// DB 연결
$user_con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// 삭제된 학과
$sql_check_department = "SELECT * FROM deleted_department";
$result_check_department = mysqli_query($user_con, $sql_check_department);

// 삭제된 교수
$sql_check_professor = "SELECT * FROM deleted_professor";
$result_check_professor = mysqli_query($user_con, $sql_check_professor);

// 삭제된 학생
$sql_check_student = "SELECT * FROM deleted_student";
$result_check_student = mysqli_query($user_con, $sql_check_student);

// 삭제된 학과 출력
if (mysqli_num_rows($result_check_department) > 0) {
    echo "<h3>학과 정보</h3>";
    echo "<table border='1'>";
    echo "<tr><th>학과 ID</th><th>학과</th><th>삭제 일자</th></tr>";
    while ($row = mysqli_fetch_array($result_check_department)) {
        echo "<tr>";
        echo "<td>" . $row['departmentID'] . "</td>";
        echo "<td>" . $row['College'] . "</td>";
        echo "<td>" . $row['DeletedAt'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 삭제된 교수 출력
if (mysqli_num_rows($result_check_professor) > 0) {
    echo "<h3>교수 정보</h3>";
    echo "<table border='1'>";
    echo "<tr><th>교수 ID</th><th>교수 이름</th><th>삭제 일자</th></tr>";
    while ($row = mysqli_fetch_assoc($result_check_professor)) {
        echo "<tr>";
        echo "<td>" . $row['ProfessorID'] . "</td>";
        echo "<td>" . $row['ProfessorName'] . "</td>";
        echo "<td>" . $row['DeletedAt'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 삭제된 학생 정보 출력
if (mysqli_num_rows($result_check_student) > 0) {
    echo "<h3>학생 정보</h3>";
    echo "<table border='1'>";
    echo "<tr><th>학 번</th><th>학생 이름</th><th>삭제 일자</th></tr>";
    while ($row = mysqli_fetch_assoc($result_check_student)) {
        echo "<tr>";
        echo "<td>" . $row['studentID'] . "</td>";
        echo "<td>" . $row['studentName'] . "</td>";
        echo "<td>" . $row['DeletedAt'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
echo "<br><a href='../main.php'>메인 페이지</a>";
// 연결 종료
mysqli_close($user_con);
?>
