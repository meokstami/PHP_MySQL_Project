<?php
// dothome.co.kr 무료 호스팅 환경 데이터베이스 연결 설정
$host = "localhost";
$user = "digicope";  // 실제 dothome 아이디로 변경하세요
$pass = "pass01**";     // dothome에서 설정한 실제 비밀번호로 변경하세요
$dbname = "digicope"; // 실제 dothome 아이디로 변경하세요

// 에러 리포팅 설정
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // 특수문자 비밀번호를 안전하게 처리
    $dsn = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 연결 성공 메시지 (개발 시에만 사용)
    // echo "데이터베이스 연결 성공!<br>";
    
} catch(PDOException $e) {
    // 상세한 에러 메시지 출력
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px;'>";
    echo "<h3>데이터베이스 연결 오류</h3>";
    echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>연결 정보:</strong></p>";
    echo "<ul>";
    echo "<li>호스트: " . htmlspecialchars($host) . "</li>";
    echo "<li>사용자: " . htmlspecialchars($user) . "</li>";
    echo "<li>데이터베이스: " . htmlspecialchars($dbname) . "</li>";
    echo "</ul>";
    echo "<p><strong>해결 방법:</strong></p>";
    echo "<ul>";
    echo "<li>dothome 관리자 페이지에서 MySQL 데이터베이스가 생성되었는지 확인하세요</li>";
    echo "<li>데이터베이스 사용자 권한이 올바르게 설정되었는지 확인하세요</li>";
    echo "<li>호스팅 계정이 활성화되어 있는지 확인하세요</li>";
    echo "</ul>";
    echo "</div>";
    
    // 스크립트 실행 중단
    die();
}
?>
