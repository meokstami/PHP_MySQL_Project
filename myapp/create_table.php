<?php
require_once 'db_config.php';

try {
    // users 테이블 생성 SQL
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        reg_date DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    // 테이블 생성 실행
    $pdo->exec($sql);
    
    // 성공 메시지 출력
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px;'>";
    echo "<h3>✅ 테이블 생성 완료</h3>";
    echo "<p>users 테이블이 성공적으로 생성되었습니다.</p>";
    echo "<p><strong>테이블 구조:</strong></p>";
    echo "<ul>";
    echo "<li>id - INT (AUTO_INCREMENT, PRIMARY KEY)</li>";
    echo "<li>name - VARCHAR(50)</li>";
    echo "<li>email - VARCHAR(100)</li>";
    echo "<li>age - INT</li>";
    echo "<li>reg_date - DATETIME (기본값: CURRENT_TIMESTAMP)</li>";
    echo "</ul>";
    echo "</div>";
    
} catch(PDOException $e) {
    // 에러 메시지 출력
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px;'>";
    echo "<h3>❌ 테이블 생성 실패</h3>";
    echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>해결 방법:</strong></p>";
    echo "<ul>";
    echo "<li>데이터베이스 연결을 확인하세요</li>";
    echo "<li>사용자 권한이 테이블 생성 권한을 가지고 있는지 확인하세요</li>";
    echo "<li>테이블이 이미 존재하는지 확인하세요</li>";
    echo "</ul>";
    echo "</div>";
}
?>
