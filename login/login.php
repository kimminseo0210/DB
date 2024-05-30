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

    // 대소문자 구별하여 로그인 시도
    $query = "SELECT * FROM user WHERE BINARY userID='$userID'";
    $result = mysqli_query($con, $query);

    // 일치하는 사용자가 있는지 확인
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $storedPW = $row['userPW'];

        // 비밀번호 해시 확인
        if (password_verify($userPW, $storedPW)) {
            // 인증 성공: 로그인 세션 설정 및 main.php로 이동
            $_SESSION['loggedIn'] = true;
            echo "<script>alert('로그인 성공');</script>";
            $_SESSION['userID'] = $userID;
            header("Location: ../main.php");
            exit();
        } else {
            // 인증 실패: 알림창 표시 후 로그인 페이지로 리디렉션
            echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.$capsLockAlert'); window.location.href = 'login.php';</script>";
            exit();
        }
    } else {
        // 일치하는 사용자가 없음
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
            var capsLockOn = (e.getModifierState && e.getModifierState('CapsLock')) ||
                (e.getModifierState && e.getModifierState('Capslock')) ||
                (e.getModifierState && e.getModifierState('CapsLK')) ||
                (e.getModifierState && e.getModifierState('Capital'));

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
    아이디 : <input type="text" name="userID" required><br>
    비밀번호 : <input type="password" name="userPW" required onkeydown="checkCapsLock(event)"><br>
    <div id="capsLockAlert" style="display: none; color: red;">Caps Lock이 켜져 있습니다.</div>
    <br><br>
    <input type="submit" name="login" value="로그인">
    <input type="button" value="취소" onclick="window.location.href='../main.php'">
</form>
</body>
</html>
