<?php
session_start();

// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// AJAX 요청 처리
if (isset($_GET['ProfessorID'])) {
    $ProfessorID = $_GET['ProfessorID'];

    // 해당 교수의 연구실 목록 조회 쿼리
    $sql_labs = "SELECT LabID, LabName FROM lab WHERE ProfessorID='$ProfessorID'";
    $result_labs = mysqli_query($con, $sql_labs);

    // 연구실 목록을 옵션으로 반환
    if ($row_labs = mysqli_fetch_array($result_labs)) {
        $labID = $row_labs['LabID'];
        $labN = $row_labs['LabName'];
        echo "<option value='$labID'>$labN</option>";
    }
    echo "<option value=''>연구실 없음</option>";
    exit();
}

// GET으로 전달된 학생 ID를 가져옴
$StudentID = $_GET['StudentID'];

// 학생 정보 조회 쿼리
$sql = "SELECT * FROM student WHERE StudentID='$StudentID'";
$ret = mysqli_query($con, $sql);

// 데이터베이스 연결 체크
if (!$ret) {
    echo "데이터 검색 실패" . "<br>";
    echo "실패 원인 : " . mysqli_error($con);
    echo "<br><a href='student.php'><--학생 정보 페이지</a>";
    exit();
}

// 학생 정보를 변수에 할당
$row = mysqli_fetch_array($ret);
$studentID = $row['StudentID'];
$studentName = $row['StudentName'];
$grade = $row['Grade'];
$department = $row['DepartmentID'];
$labs = $row['labID'];
$currentTeacherID = $row['AdvisorID'];

// 학과 목록 조회 쿼리
$sql_department = "SELECT DepartmentID, College FROM department";
$result_department = mysqli_query($con, $sql_department);

// 지도교수 목록 조회 쿼리
$sql_teacher = "SELECT ProfessorID, ProfessorName FROM Professor";
$result_teacher = mysqli_query($con, $sql_teacher);

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
    <title>학생 정보 수정</title>
    <script>
        function updateLabs(professorID) {
            if (professorID == "") {
                document.getElementById("labs").innerHTML = "<option value=''>연구실 없음</option>";
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "?ProfessorID=" + professorID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("labs").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
<h1>학생 정보 수정</h1>
<form method="post" action="update_result_student.php">
    학번 : <input type="text" name="studentID" value="<?php echo $studentID ?>" readonly><br>
    학과 :
    <select name="department">
        <option value="학과 선택"></option>
        <?php
        // 학과 목록 반복
        while ($row_department = mysqli_fetch_array($result_department)) {
            $dept = $row_department['DepartmentID'];
            $deptName = $row_department['College'];
            $selected = ($dept == $department) ? "selected" : "";
            echo "<option value='$dept' $selected>$deptName</option>";
        }
        ?>
    </select><br>
    이름 : <input type="text" name="studentName" value="<?php echo $studentName ?>" readonly><br>
    학년 : <input type="text" name="grade" value="<?php echo $grade ?>" readonly><br>
    지도교수 :
    <select name="ProfessorID" onchange="updateLabs(this.value)">
        <option value="">교수 선택</option>
        <?php
        // 지도교수 목록 반복
        while ($row_teacher = mysqli_fetch_array($result_teacher)) {
            $ProfessorID = $row_teacher['ProfessorID'];
            $ProfessorName = $row_teacher['ProfessorName'];
            $selected = ($ProfessorID == $currentTeacherID) ? "selected" : "";
            echo "<option value='$ProfessorID' $selected>$ProfessorName</option>";
        }
        ?>
    </select><br>
    연구실 :
    <select name="labs" id="labs">
        <option value="">연구실 없음</option>
        <?php
        // 해당 지도교수의 연구실 목록 반복
        if ($currentTeacherID != "") {
            $sql_labs = "SELECT LabID, LabName FROM lab WHERE ProfessorID='$currentTeacherID'";
            $result_labs = mysqli_query($con, $sql_labs);
            while ($row_labs = mysqli_fetch_array($result_labs)) {
                $labID = $row_labs['LabID'];
                $labN = $row_labs['LabName'];
                $selected = ($labID == $labs) ? "selected" : "";
                echo "<option value='$labID' $selected>$labN</option>";
            }
        }
        ?>
    </select><br>
    <br>
    <input type="submit" value="정보수정">
    <input type="button" value="취소" onclick="window.location.href='student.php'">
</form>
</body>
</html>
