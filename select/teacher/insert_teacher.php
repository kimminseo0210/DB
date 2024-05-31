<html>
<head>
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
    <title>교수 정보</title>
</head>
<body>
<h1>교수 정보 입력</h1>
<form method="post" action="insert_result_teacher.php">
    학과 :
    <?php
    // 데이터베이스 연결
    $con = mysqli_connect(
        "localhost",
        "minseoUser",
        "0210",
        "cse_comu"
    );
    // 학과 목록 조회 쿼리
    $sql = "SELECT DepartmentID, College FROM department";
    $result = mysqli_query($con, $sql);
    // 드롭다운 목록 시작
    echo "<select name='department'>";
    echo "<option value=''>학과 선택</option>"; // 기본 선택 항목 추가
    // 학과 목록 반복
    while ($row_dept = mysqli_fetch_array($result)) {
        $dept = $row_dept['DepartmentID'];
        $dept_name = $row_dept['College'];
        // 드롭다운 목록 옵션 추가
        echo "<option value='$dept'>$dept_name</option>";
    }
    // 드롭다운 목록 종료
    echo "</select>";
    // 데이터베이스 연결 해제
    mysqli_close($con);
    ?><br>
    이름 : <input type="text" name="ProfessorName"><br>
    연구 분야 : <input type="text" name="Field"><br>
    교수실 :
    <?php
    // 데이터베이스 연결
    $con = mysqli_connect(
        "localhost",
        "minseoUser",
        "0210",
        "cse_comu"
    );
    // 교수실 목록 조회 쿼리
    $sql = "SELECT ClassroomID, Purpose FROM classroom";
    $result = mysqli_query($con, $sql);
    // 드롭다운 목록 시작
    echo "<select name='Office'>";
    echo "<option value=''>교수 사무실 선택</option>"; // 기본 선택 항목 추가
    // 교수실 목록 반복
    while ($row_office = mysqli_fetch_array($result)) {
        $office_id = $row_office['ClassroomID'];
        $office_name = $row_office['Purpose'];
        // 드롭다운 목록 옵션 추가
        echo "<option value='$office_id'>$office_id - $office_name</option>";
    }
    // 드롭다운 목록 종료
    echo "</select>";
    // 데이터베이스 연결 해제
    mysqli_close($con);
    ?><br>
    연구실 :
    <?php
    // 데이터베이스 연결
    $con = mysqli_connect(
        "localhost",
        "minseoUser",
        "0210",
        "cse_comu"
    );
    // 연구실 목록 조회 쿼리
    $sql = "SELECT ClassroomID, Purpose FROM classroom";
    $result = mysqli_query($con, $sql);
    // 드롭다운 목록 시작
    echo "<select name='Lab'>";
    echo "<option value=''>연구실 없음</option>"; // 기본 선택 항목 추가
    // 연구실 목록 반복
    while ($row_lab = mysqli_fetch_array($result)) {
        $lab_id = $row_lab['ClassroomID'];
        $lab_name = $row_lab['Purpose'];
        // 드롭다운 목록 옵션 추가
        echo "<option value='$lab_id'>$lab_id - $lab_name</option>";
    }
    // 드롭다운 목록 종료
    echo "</select>";
    // 데이터베이스 연결 해제
    mysqli_close($con);
    ?><br>
    <br><br>
    <input type="submit" value="정보 추가">
    <input type="button" value="취소" onclick="window.location.href='teacher.php'">
</form>
</body>
</html>
