<?php
$con = mysqli_connect("localhost", "minseoUser", "0210", "cse");

// 전송된 아이디 값 받기
$userID = $_POST['userID'];

// membertbl에서 중복된 아이디가 있는지 확인하는 쿼리
$sql_member = "SELECT * FROM user WHERE userID = '$userID'";
$result_member = mysqli_query($con, $sql_member);

// deleted_users에서 중복된 아이디가 있는지 확인하는 쿼리
$sql_deleted = "SELECT * FROM deleted_user WHERE userID = '$userID'";
$result_deleted = mysqli_query($con, $sql_deleted);

if (!$result_member || !$result_deleted) {
    // 쿼리 실행 오류 발생 시 오류 메시지 출력
    echo "query_error";
} else {
    if (mysqli_num_rows($result_member) > 0 || mysqli_num_rows($result_deleted) > 0) {
        // 중복된 아이디가 있으면 "duplicate" 반환
        echo "duplicate";
    } else {
        // 중복된 아이디가 없으면 아무것도 반환하지 않음
    }
}
?>
