<?php
// 특수문자 비밀번호 테스트 파일
echo "<h1>🔐 특수문자 비밀번호 테스트</h1>";

$host = "localhost";
$user = "digicope";
$dbname = "digicope";

// 다양한 특수문자 비밀번호 테스트
$passwords = [
    "pass01**",
    "pass01!!",
    "pass01@@",
    "pass01##",
    "pass01$$",
    "pass01%%",
    "pass01^^",
    "pass01&&",
    "pass01++",
    "pass01==",
    "pass01--",
    "pass01__",
    "pass01..",
    "pass01,,",
    "pass01;;",
    "pass01::",
    "pass01''",
    'pass01""',
    "pass01()",
    "pass01[]",
    "pass01{}",
    "pass01<>",
    "pass01??",
    "pass01//",
    "pass01\\\\",
    "pass01||",
    "pass01~~",
    "pass01``",
    "pass01!!",
    "pass01@@"
];

echo "<div style='background: #e8f4fd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h2>📋 테스트할 비밀번호들</h2>";
echo "<p>총 " . count($passwords) . "개의 특수문자 조합을 테스트합니다.</p>";
echo "</div>";

$successful_passwords = [];

foreach ($passwords as $index => $pass) {
    echo "<h3>테스트 " . ($index + 1) . ": " . htmlspecialchars($pass) . "</h3>";
    
    try {
        $dsn = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        // 간단한 쿼리 테스트
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        
        echo "<p style='color: green; font-weight: bold;'>✅ 연결 성공!</p>";
        echo "<p>쿼리 테스트 결과: " . $result['test'] . "</p>";
        
        $successful_passwords[] = $pass;
        
        // 첫 번째 성공한 비밀번호로 설정 파일 업데이트
        if (count($successful_passwords) == 1) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h4>🎯 권장 설정</h4>";
            echo "<p>성공한 비밀번호: <strong>" . htmlspecialchars($pass) . "</strong></p>";
            echo "<p>이 비밀번호를 db_config.php에 설정하세요:</p>";
            echo "<code>\$pass = \"" . htmlspecialchars($pass) . "\";</code>";
            echo "</div>";
        }
        
    } catch(PDOException $e) {
        echo "<p style='color: red;'>❌ 연결 실패</p>";
        echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
        echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h2>📊 테스트 결과 요약</h2>";
echo "<p><strong>총 테스트:</strong> " . count($passwords) . "개</p>";
echo "<p><strong>성공:</strong> " . count($successful_passwords) . "개</p>";
echo "<p><strong>실패:</strong> " . (count($passwords) - count($successful_passwords)) . "개</p>";

if (count($successful_passwords) > 0) {
    echo "<h3>✅ 성공한 비밀번호들:</h3>";
    echo "<ul>";
    foreach ($successful_passwords as $pass) {
        echo "<li><code>" . htmlspecialchars($pass) . "</code></li>";
    }
    echo "</ul>";
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h4>🎯 다음 단계</h4>";
    echo "<p>성공한 비밀번호 중 하나를 선택하여 db_config.php에 설정하세요.</p>";
    echo "<p>그 후 <a href='create_table.php'>테이블 생성</a>을 진행하세요.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h4>❌ 모든 테스트 실패</h4>";
    echo "<p>모든 특수문자 조합이 실패했습니다. 다음을 확인하세요:</p>";
    echo "<ul>";
    echo "<li>dothome 관리자 페이지에서 MySQL 서비스 활성화 확인</li>";
    echo "<li>데이터베이스 'digicope' 생성 확인</li>";
    echo "<li>사용자 'digicope' 권한 확인</li>";
    echo "<li>호스팅 계정 활성화 확인</li>";
    echo "</ul>";
    echo "</div>";
}
echo "</div>";
?>
