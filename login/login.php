<?php
session_start();

$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);

// Caps Lock 상태 확인
$capsLockAlert = isset($_POST['capsLock']) ? "<br><span style='color:red;'>Caps Lock이 켜져 있습니다.</span>" : "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // 사용자가 제출한 아이디와 비밀번호 가져오기
    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];

    // 관리자 로그인 시도
    $admin_sql = "SELECT * FROM user WHERE BINARY userID='$userID'";
    $admin_ret = mysqli_query($con, $admin_sql);

    // 학생 로그인 시도
    $student_sql = "SELECT * FROM student WHERE StudentID='$userID'";
    $student_ret = mysqli_query($con, $student_sql);

    // 교수 로그인 시도
    $prof_sql = "SELECT * FROM professor WHERE ProfessorID='$userID'";
    $prof_ret = mysqli_query($con, $prof_sql);

    // 일치하는 사용자가 있는지 확인
    if (mysqli_num_rows($admin_ret) == 1) {
        $admin_row = mysqli_fetch_assoc($admin_ret);
        $admin_PW = $admin_row['userPW'];

        // 비밀번호 해시 확인
        if (password_verify($userPW, $admin_PW)) {
            // 인증 성공: 로그인 세션 설정 및 main.php로 이동
            $_SESSION['loggedIn'] = true;
            $_SESSION['userID'] = $userID;
            $_SESSION['role'] = 'admin';
            echo "<script>alert('로그인 성공');</script>";
            header("Location: ../main.php");
            exit();
        }
    } elseif (mysqli_num_rows($student_ret) == 1) {
        $student_row = mysqli_fetch_assoc($student_ret);
        $student_PW = $student_row['studentPW'];

        // 비밀번호 해시 확인
        if (password_verify($userPW, $student_PW)) {
            // 인증 성공: 로그인 세션 설정 및 main.php로 이동
            $_SESSION['loggedIn'] = true;
            $_SESSION['userID'] = $userID;
            $_SESSION['role'] = 'student';
            echo "<script>alert('로그인 성공');</script>";
            header("Location: ../main.php");
            exit();
        }
    } elseif (mysqli_num_rows($prof_ret) == 1) {
        $prof_row = mysqli_fetch_assoc($prof_ret);
        $prof_PW = $prof_row['ProfessorPW'];

        // 비밀번호 해시 확인
        if (password_verify($userPW, $prof_PW)) {
            // 인증 성공: 로그인 세션 설정 및 main.php로 이동
            $_SESSION['loggedIn'] = true;
            $_SESSION['userID'] = $userID;
            $_SESSION['role'] = 'professor';
            echo "<script>alert('로그인 성공');</script>";
            header("Location: ../main.php");
            exit();
        }
    } else {
        // 일치하는 사용자가 없음 또는 비밀번호 불일치
        echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.$capsLockAlert'); window.location.href = 'login.php';</script>";
        exit();
    }
}

?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html"; charset="utf-8">
    <script type="text/javascript">
        // Caps Lock 상태 확인 함수
        function checkCapsLock(e) {
            var capsLockOn = (e.getModifierState && e.getModifierState('CapsLock'));

            if (capsLockOn) {
                document.getElementById("capsLockAlert").style.display = "block";
            } else {
                document.getElementById("capsLockAlert").style.display = "none";
            }
        }
    </script>
</head>
<body>
<h1>로그인</h1>
<form method="post" action="login.php">
    학번/교번 : <input type="text" name="userID" required><br>
    비밀번호 : <input type="password" name="userPW" required onkeydown="checkCapsLock(event)"><br>
    <div id="capsLockAlert" style="display: none; color: red;">Caps Lock이 켜져 있습니다.</div>
    <br><br>
    <input type="submit" name="login" value="로그인">
    <input type="button" value="취소" onclick="window.location.href='../main.php'">
</form>
</body>
</html>
