<?php
// 세션 시작
session_start();
// 모든 세션 변수 삭제
session_unset();
// 세션 삭제
session_destroy();
// 로그아웃 메시지
echo "<script>alert('로그아웃 되었습니다.'); window.location.href = '../main.php';</script>";
?>
