<?php
// 간단한 비밀번호 테스트 (웹브라우저에서 실행)
echo "<h1>🔐 간단한 비밀번호 테스트</h1>";

$host = "localhost";
$user = "digicope";
$dbname = "digicope";

// 몇 가지 주요 특수문자만 테스트
$test_passwords = [
    "pass01**",
    "pass01!!", 
    "pass01@@",
    "pass01##",
    "pass01$$"
];

echo "<p>테스트할 비밀번호: " . implode(", ", $test_passwords) . "</p>";

foreach ($test_passwords as $pass) {
    echo "<h3>테스트: " . htmlspecialchars($pass) . "</h3>";
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        
        echo "<p style='color: green; font-weight: bold;'>✅ 성공!</p>";
        echo "<p>이 비밀번호를 사용하세요: <strong>" . htmlspecialchars($pass) . "</strong></p>";
        
        // 성공한 비밀번호로 설정 파일 업데이트 안내
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>🎯 설정 방법</h4>";
        echo "<p>db_config.php 파일에서 다음 줄을 수정하세요:</p>";
        echo "<code>\$pass = \"" . htmlspecialchars($pass) . "\";</code>";
        echo "</div>";
        
        break; // 첫 번째 성공한 비밀번호에서 중단
        
    } catch(PDOException $e) {
        echo "<p style='color: red;'>❌ 실패 - " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

echo "<p><a href='quick_test.php'>연결 테스트</a> | <a href='create_table.php'>테이블 생성</a></p>";
?>
