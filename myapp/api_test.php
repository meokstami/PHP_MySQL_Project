<?php
// API 테스트 파일
echo "<h2>API 응답 테스트</h2>";

echo "<h3>1. fetch.php 테스트</h3>";
echo "<p>응답 내용:</p>";
echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";

// fetch.php 직접 실행
ob_start();
include 'fetch.php';
$output = ob_get_clean();

echo htmlspecialchars($output);
echo "</pre>";

echo "<h3>2. JSON 파싱 테스트</h3>";
try {
    $data = json_decode($output, true);
    if ($data) {
        echo "<p style='color: green;'>✅ JSON 파싱 성공!</p>";
        echo "<p><strong>Status:</strong> " . $data['status'] . "</p>";
        if (isset($data['message'])) {
            echo "<p><strong>Message:</strong> " . $data['message'] . "</p>";
        }
        if (isset($data['count'])) {
            echo "<p><strong>Count:</strong> " . $data['count'] . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ JSON 파싱 실패!</p>";
        echo "<p>오류: " . json_last_error_msg() . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ JSON 파싱 오류!</p>";
    echo "<p>오류: " . $e->getMessage() . "</p>";
}

echo "<h3>3. 데이터베이스 연결 테스트</h3>";
try {
    require_once 'db_config.php';
    echo "<p style='color: green;'>✅ 데이터베이스 연결 성공!</p>";
    
    // 테이블 존재 확인
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green;'>✅ users 테이블 존재!</p>";
        
        // 사용자 수 확인
        $countResult = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $countResult->fetch()['count'];
        echo "<p>현재 사용자 수: <strong>{$count}명</strong></p>";
    } else {
        echo "<p style='color: orange;'>⚠️ users 테이블이 없습니다.</p>";
        echo "<p><a href='create_table.php'>테이블 생성하기</a></p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ 데이터베이스 연결 실패!</p>";
    echo "<p>오류: " . $e->getMessage() . "</p>";
}
?>
