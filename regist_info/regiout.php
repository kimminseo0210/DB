<?php
// 데이터베이스 연결
$con = mysqli_connect(
    "localhost",
    "minseoUser",
    "0210",
    "cse_comu");

// 삭제할 유저의 아이디 받기
$userID = $_GET['userID'];

// membertbl에서 유저 정보 삭제
$sql_delete_user = "DELETE FROM user WHERE userID='$userID'";
$ret_delete_user = mysqli_query($con, $sql_delete_user);

// 삭제된 유저 정보를 저장할 deleted_users 테이블 생성 및 데이터 삽입
if ($ret_delete_user) {
    // 현재 날짜 및 시간
    $deleted_at = date('Y-m-d H:i:s');

    // deleted_users 테이블에 삭제된 유저 정보 삽입
    $sql_insert_deleted_user = "INSERT INTO deleted_user (userID, deleted_at) VALUES ('$userID', '$deleted_at')";
    $ret_insert_deleted_user = mysqli_query($con, $sql_insert_deleted_user);

    if ($ret_insert_deleted_user) {
        echo "유저 정보가 성공적으로 삭제되고 deleted_users 테이블에 삽입되었습니다.";
    } else {
        echo "유저 정보가 삭제되었지만 deleted_users 테이블에 삽입하지 못했습니다: " . mysqli_error($con);
    }
} else {
    echo "유저 정보를 삭제하지 못했습니다: " . mysqli_error($con);
}

// 데이터베이스 연결 해제
mysqli_close($con);
echo "<br><a href='../control_user/control_user.php'><--수정화면</a>";
?>
