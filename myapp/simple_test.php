<?php
// 간단한 데이터베이스 연결 테스트
echo "<h2>데이터베이스 연결 테스트</h2>";

// 설정값 확인
$host = "localhost";
$user = "digicope";
$pass = "pass01**";  // 실제 비밀번호로 변경하세요
$dbname = "digicope";

echo "<p><strong>연결 정보:</strong></p>";
echo "<ul>";
echo "<li>호스트: $host</li>";
echo "<li>사용자: $user</li>";
echo "<li>데이터베이스: $dbname</li>";
echo "</ul>";

try {
    // 기본 연결 테스트
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    echo "<p style='color: green;'>✅ MySQL 서버 연결 성공!</p>";
    
    // 데이터베이스 존재 확인
    $result = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green;'>✅ 데이터베이스 '$dbname' 존재함!</p>";
        
        // 데이터베이스 선택
        $pdo->exec("USE `$dbname`");
        echo "<p style='color: green;'>✅ 데이터베이스 선택 성공!</p>";
        
        // 테이블 확인
        $tables = $pdo->query("SHOW TABLES")->fetchAll();
        if (count($tables) > 0) {
            echo "<p style='color: green;'>✅ 테이블 목록:</p>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>" . $table[0] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: orange;'>⚠️ 테이블이 없습니다. <a href='create_table.php'>테이블 생성하기</a></p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ 데이터베이스 '$dbname'가 존재하지 않습니다.</p>";
        echo "<p>dothome 관리자 페이지에서 데이터베이스를 생성하세요.</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ 연결 실패!</p>";
    echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    
    // 일반적인 해결 방법 제시
    echo "<h3>해결 방법:</h3>";
    echo "<ul>";
    echo "<li>dothome 관리자 페이지에서 MySQL 서비스가 활성화되어 있는지 확인</li>";
    echo "<li>데이터베이스 비밀번호가 올바른지 확인</li>";
    echo "<li>데이터베이스가 생성되어 있는지 확인</li>";
    echo "</ul>";
}
?>
