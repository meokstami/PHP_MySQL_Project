<?php
// 데이터베이스 연결 테스트 파일
require_once 'db_config.php';

echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px;'>";
echo "<h3>✅ 데이터베이스 연결 성공!</h3>";
echo "<p>데이터베이스 연결이 정상적으로 작동합니다.</p>";
echo "<p><strong>연결 정보:</strong></p>";
echo "<ul>";
echo "<li>호스트: localhost</li>";
echo "<li>사용자: digicope</li>";
echo "<li>데이터베이스: digicope</li>";
echo "</ul>";
echo "</div>";

// 테이블 존재 여부 확인
try {
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "<div style='background-color: #d1ecf1; color: #0c5460; padding: 15px; border: 1px solid #bee5eb; border-radius: 5px; margin: 10px;'>";
        echo "<h3>📋 테이블 상태</h3>";
        echo "<p>✅ users 테이블이 존재합니다.</p>";
        
        // 사용자 수 확인
        $countResult = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $countResult->fetch()['count'];
        echo "<p>현재 등록된 사용자 수: <strong>{$count}명</strong></p>";
        echo "</div>";
    } else {
        echo "<div style='background-color: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeaa7; border-radius: 5px; margin: 10px;'>";
        echo "<h3>⚠️ 테이블 상태</h3>";
        echo "<p>users 테이블이 존재하지 않습니다.</p>";
        echo "<p><a href='create_table.php' style='color: #856404; text-decoration: underline;'>테이블 생성하기</a></p>";
        echo "</div>";
    }
} catch(PDOException $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px;'>";
    echo "<h3>❌ 테이블 확인 오류</h3>";
    echo "<p>오류: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
