<?php
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu"
);
// 학과 목록 조회 쿼리
$sql_department = "SELECT College FROM department";
$result_department = mysqli_query($con, $sql_department);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원 가입</title>
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

        // 중복 아이디 체크 함수
        function checkDuplicateID() {
            var userID = document.getElementsByName("userID")[0].value;
            if (userID === "") {
                alert("아이디를 입력하세요.");
                return;
            }
            // AJAX를 사용하여 중복 아이디 확인
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        if (xhr.responseText.trim() === "duplicate") { // 응답값 양 끝 공백 제거
                            alert("중복된 아이디 또는 탈퇴한 아이디입니다.");
                        } else {
                            alert("사용 가능한 아이디입니다.");
                            // 중복 체크 통과 시 회원가입 버튼 활성화
                            document.getElementById("signupButton").disabled = false;
                        }
                    } else {
                        alert("오류가 발생했습니다. 다시 시도해주세요.");
                    }
                }
            };
            xhr.open("POST", "ID_check.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("userID=" + encodeURIComponent(userID)); // encodeURIComponent()를 사용하여 아이디 인코딩
        }

    </script>
</head>
<body>
<h1>회원 가입</h1>
<form method="post" action="regist_insert.php">

    <!-- 역할 선택 라디오 버튼 추가 -->
    <label for="role">역할:</label>
    <input type="radio" id="student" name="role" value="student" required>
    <label for="student">학생</label>
    <input type="radio" id="professor" name="role" value="professor" required>
    <label for="professor">교수</label><br>

    <label for="userName">이름:</label>
    <input type="text" id="userName" name="userName" required><br>

    <label for="userID">아이디:</label>
    <input type="text" id="userID" name="userID" required>
    <!-- 중복 체크 버튼 -->
    <input type="button" value="중복 체크" onclick="checkDuplicateID()"><br>

    <label for="userPW">비밀번호:</label>
    <input type="password" id="userPW" name="userPW" required onkeydown="checkCapsLock(event)"><br>
    <div id="capsLockAlert" style="display: none; color: red;">Caps Lock이 켜져 있습니다.</div>

    <label for="department">학과:</label>
    <select id="department" name="department" required>
        <option value="">학과 선택</option>
        <?php
        // 학과 목록 반복
        while ($row_department = mysqli_fetch_array($result_department)) {
            $deptID = $row_department['DepartmentID'];
            $dept = $row_department['College'];
            echo "<option value='$deptID'>$dept</option>";
        }
        ?>
    </select><br>

    <br>
    <!-- 회원가입 버튼 -->
    <input type="submit" id="signupButton" name="login" value="회원가입" disabled>
    <input type="button" value="취소" onclick="window.location.href='..main.php'">
</form>
</body>
</html>
