<?php
// 종합 데이터베이스 진단 파일
echo "<h1>🔍 데이터베이스 종합 진단</h1>";

$host = "localhost";
$user = "digicope";
$pass = "pass01**";
$dbname = "digicope";

echo "<div style='background: #e8f4fd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h2>📋 연결 정보</h2>";
echo "<ul>";
echo "<li><strong>호스트:</strong> $host</li>";
echo "<li><strong>사용자:</strong> $user</li>";
echo "<li><strong>비밀번호:</strong> " . str_repeat('*', strlen($pass)) . "</li>";
echo "<li><strong>데이터베이스:</strong> $dbname</li>";
echo "</ul>";
echo "</div>";

// 1단계: 기본 연결 테스트
echo "<h2>1️⃣ 기본 연결 테스트</h2>";
try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    echo "<p style='color: green; font-weight: bold;'>✅ MySQL 서버 연결 성공!</p>";
    
    // 2단계: 데이터베이스 존재 확인
    echo "<h2>2️⃣ 데이터베이스 존재 확인</h2>";
    $result = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green; font-weight: bold;'>✅ 데이터베이스 '$dbname' 존재!</p>";
        
        // 3단계: 데이터베이스 선택
        echo "<h2>3️⃣ 데이터베이스 선택</h2>";
        $pdo->exec("USE `$dbname`");
        echo "<p style='color: green; font-weight: bold;'>✅ 데이터베이스 선택 성공!</p>";
        
        // 4단계: 테이블 확인
        echo "<h2>4️⃣ 테이블 확인</h2>";
        $tables = $pdo->query("SHOW TABLES")->fetchAll();
        if (count($tables) > 0) {
            echo "<p style='color: green; font-weight: bold;'>✅ 테이블 목록:</p>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>" . $table[0] . "</li>";
            }
            echo "</ul>";
            
            // users 테이블 확인
            if (in_array(['users'], $tables)) {
                echo "<p style='color: green; font-weight: bold;'>✅ users 테이블 존재!</p>";
                
                // 사용자 수 확인
                $countResult = $pdo->query("SELECT COUNT(*) as count FROM users");
                $count = $countResult->fetch()['count'];
                echo "<p>현재 사용자 수: <strong>{$count}명</strong></p>";
                
                // 테이블 구조 확인
                echo "<h3>📊 users 테이블 구조</h3>";
                $columns = $pdo->query("DESCRIBE users")->fetchAll();
                echo "<table style='border-collapse: collapse; width: 100%;'>";
                echo "<tr style='background: #34495e; color: white;'>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>필드</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>타입</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>Null</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>키</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>기본값</th>";
                echo "</tr>";
                foreach ($columns as $column) {
                    echo "<tr>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $column['Field'] . "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $column['Type'] . "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $column['Null'] . "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $column['Key'] . "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . $column['Default'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
            } else {
                echo "<p style='color: orange; font-weight: bold;'>⚠️ users 테이블이 없습니다.</p>";
                echo "<p><a href='create_table.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>테이블 생성하기</a></p>";
            }
            
        } else {
            echo "<p style='color: orange; font-weight: bold;'>⚠️ 테이블이 없습니다.</p>";
            echo "<p><a href='create_table.php' style='background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>테이블 생성하기</a></p>";
        }
        
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ 데이터베이스 '$dbname'가 존재하지 않습니다.</p>";
        echo "<p>dothome 관리자 페이지에서 데이터베이스를 생성하세요.</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ 연결 실패!</p>";
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    echo "</div>";
    
    // 일반적인 해결 방법
    echo "<h3>🔧 해결 방법</h3>";
    echo "<ol>";
    echo "<li>dothome 관리자 페이지에서 MySQL 서비스가 활성화되어 있는지 확인</li>";
    echo "<li>데이터베이스 'digicope'가 생성되어 있는지 확인</li>";
    echo "<li>사용자 'digicope'의 권한이 올바르게 설정되어 있는지 확인</li>";
    echo "<li>비밀번호 'pass01**'가 올바른지 확인</li>";
    echo "<li>호스팅 계정이 활성화되어 있는지 확인</li>";
    echo "</ol>";
}

echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h2>🎯 다음 단계</h2>";
echo "<ol>";
echo "<li>위의 진단 결과를 확인하세요</li>";
echo "<li>연결이 성공하면 <a href='create_table.php'>테이블 생성</a>을 진행하세요</li>";
echo "<li>테이블이 있으면 <a href='index.html'>웹앱 실행</a>을 진행하세요</li>";
echo "</ol>";
echo "</div>";
?>
