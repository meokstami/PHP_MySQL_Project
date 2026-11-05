<?php
// 매우 간단한 연결 테스트
echo "<h2>데이터베이스 연결 테스트</h2>";

$host = "localhost";
$user = "digicope";
$pass = "pass01**";
$dbname = "digicope";

echo "<p>연결 시도 중...</p>";

try {
    // 특수문자 비밀번호를 안전하게 처리
    $dsn = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<p style='color: green; font-weight: bold;'>✅ 연결 성공!</p>";
    
    // 간단한 쿼리 테스트
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✅ 쿼리 테스트 성공: " . $result['test'] . "</p>";
    
    // 테이블 확인
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green;'>✅ users 테이블 존재!</p>";
        
        // 사용자 수 확인
        $countResult = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $countResult->fetch()['count'];
        echo "<p>현재 등록된 사용자 수: <strong>{$count}명</strong></p>";
    } else {
        echo "<p style='color: orange;'>⚠️ users 테이블이 없습니다.</p>";
        echo "<p><a href='create_table.php'>테이블 생성하기</a></p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ 연결 실패!</p>";
    echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    
    // 구체적인 해결 방법
    echo "<h3>해결 방법:</h3>";
    echo "<ol>";
    echo "<li>dothome 관리자 페이지에서 MySQL 서비스 활성화 확인</li>";
    echo "<li>데이터베이스 'digicope' 생성 확인</li>";
    echo "<li>사용자 'digicope' 권한 확인</li>";
    echo "<li>비밀번호 'pass01**' 확인</li>";
    echo "</ol>";
}
?>
