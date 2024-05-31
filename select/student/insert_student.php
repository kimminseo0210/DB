<?php
// 데이터베이스 연결
$con = mysqli_connect("localhost", "minseoUser", "0210", "cse_comu");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// 지도교수 선택에 따라 연구실 목록을 동적으로 업데이트하기 위해 Ajax 요청을 처리합니다.
if (isset($_GET['ProfessorID'])) {
    $ProfessorID = $_GET['ProfessorID'];
    $sql_labs = "SELECT LabID, LabName FROM lab WHERE ProfessorID='$ProfessorID'";
    $result_labs = mysqli_query($con, $sql_labs);

    $labs_options = "<option value=''>연구실 없음</option>";
    while ($row_labs = mysqli_fetch_array($result_labs)) {
        $labID = $row_labs['LabID'];
        $labName = $row_labs['LabName'];
        $labs_options .= "<option value='$labID'>$labName</option>";
    }

    echo $labs_options;
    mysqli_close($con);
    exit();
}
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>학생 정보</title>
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
<h1>학생 정보 입력</h1>
<form method="post" action="insert_result_student.php">
    학과 :
    <select name="DepartmentID">
        <?php
        // 학과 목록 조회 쿼리
        $sql = "SELECT DepartmentID, College FROM department";
        $result = mysqli_query($con, $sql);
        while ($row_dept = mysqli_fetch_array($result)) {
            $dept = $row_dept['DepartmentID'];
            $dept_name = $row_dept['College'];
            echo "<option value='$dept'>$dept_name</option>";
        }
        ?>
    </select><br>
    이름 : <input type="text" name="StudentName"><br>
    생년월일 (예: 19900101) : <input type="text" name="DOB" pattern="\d{8}" title="YYYYMMDD 형식으로 입력해 주세요"><br>
    학년 : <input type="number" name="Grade" min="1" max="4" value="1"><br>
    지도교수 :
    <select name="ProfessorID" onchange="updateLabs(this.value)">
        <option value="">교수 선택</option>
        <?php
        // 지도교수 목록 조회 쿼리
        $sql_teacher = "SELECT ProfessorID, ProfessorName FROM professor";
        $result_teacher = mysqli_query($con, $sql_teacher);
        while ($row_teacher = mysqli_fetch_array($result_teacher)) {
            $ProfessorID = $row_teacher['ProfessorID'];
            $ProfessorName = $row_teacher['ProfessorName'];
            echo "<option value='$ProfessorID'>$ProfessorName</option>";
        }
        ?>
    </select><br>
    소속 연구실 :
    <select name="labs" id="labs">
        <option value="">연구실 없음</option>
    </select><br><br>
    <input type="submit" value="정보 추가">
    <input type="button" value="취소" onclick="window.location.href='student.php'">
</form>

<?php
// 데이터베이스 연결 해제
mysqli_close($con);
?>
</body>
</html>
