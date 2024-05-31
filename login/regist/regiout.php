<?php
// 데이터베이스 연결
$con = mysqli_connect("localhost", "minseoUser", "0210", "cse");

// 삭제할 유저의 아이디 받기
$userID = $_GET['userID'];

// membertbl에서 유저 정보 삭제
$sql_delete_user = "DELETE FROM membertbl WHERE userID='$userID'";
$ret_delete_user = mysqli_query($con, $sql_delete_user);

// 삭제된 유저 정보를 저장할 deleted_users 테이블 생성 및 데이터 삽입
if ($ret_delete_user) {
    // deleted_users 테이블 생성
    $sql_create_deleted_users = "CREATE TABLE IF NOT EXISTS deleted_users (userID CHAR(50) PRIMARY KEY)";
    $ret_create_deleted_users = mysqli_query($con, $sql_create_deleted_users);

    if ($ret_create_deleted_users) {
        // membertbl에서 삭제된 유저 정보 가져오기
        $sql_get_deleted_user_info = "SELECT * FROM membertbl WHERE userID='$userID'";
        $result_get_deleted_user_info = mysqli_query($con, $sql_get_deleted_user_info);

        if ($result_get_deleted_user_info) {
            // 가져온 정보를 deleted_users 테이블에 삽입
            $row_deleted_user_info = mysqli_fetch_assoc($result_get_deleted_user_info);

            $sql_insert_deleted_user = "INSERT INTO deleted_users (userID) 
                                        VALUES ('$userID')";
            $ret_insert_deleted_user = mysqli_query($con, $sql_insert_deleted_user);

            if ($ret_insert_deleted_user) {
                echo "<h1>유저 정보 삭제 및 저장 결과</h1>";
                echo "데이터가 성공적으로 삭제되었고, 삭제된 유저 정보가 저장되었습니다.";
            } else {
                echo "<h1>유저 정보 삭제 및 저장 실패</h1>";
                echo "삭제된 유저 정보를 저장하는 데 실패했습니다.";
                echo "실패 원인: " . mysqli_error($con);
            }
        } else {
            echo "<h1>유저 정보 삭제 및 저장 실패</h1>";
            echo "삭제된 유저 정보를 가져오는 데 실패했습니다.";
            echo "실패 원인: " . mysqli_error($con);
        }
    } else {
        echo "<h1>유저 정보 삭제 및 저장 실패</h1>";
        echo "deleted_users 테이블을 생성하는 데 실패했습니다.";
        echo "실패 원인: " . mysqli_error($con);
    }
} else {
    echo "<h1>유저 정보 삭제 및 저장 실패</h1>";
    echo "데이터가 삭제되지 않았습니다.";
    echo "실패 원인: " . mysqli_error($con);
}

// 데이터베이스 연결 해제
mysqli_close($con);
echo "<br><a href='../../control_user/control_user.php'><--수정화면</a>";
?>
