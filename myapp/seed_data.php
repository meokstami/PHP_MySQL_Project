<?php
require_once 'db_config.php';

// 한국식 이름 생성 함수
function generateKoreanName() {
    $surnames = ['김', '이', '박', '최', '정', '강', '조', '윤', '장', '임', '한', '오', '서', '신', '권', '황', '안', '송', '전', '고'];
    $givenNames = [
        '민수', '지연', '현우', '서연', '준호', '미영', '성민', '예진', '동현', '수진',
        '태현', '지은', '민호', '서영', '준영', '혜진', '성호', '민영', '동욱', '지현',
        '현수', '서진', '민지', '지훈', '예나', '성욱', '서현', '민경', '지우', '현진'
    ];
    
    $surname = $surnames[array_rand($surnames)];
    $givenName = $givenNames[array_rand($givenNames)];
    
    return $surname . $givenName;
}

// 영어 이메일 생성 함수
function generateEmail($name) {
    $domains = ['example.com', 'test.com', 'sample.org', 'demo.net', 'mock.co.kr'];
    $emailPrefixes = [
        'minsu', 'jiyeon', 'hyunwoo', 'seoyeon', 'junho', 'miyoung', 'sungmin', 'yejin', 'donghyun', 'sujin',
        'taehyun', 'jieun', 'minho', 'seoyoung', 'junyoung', 'hyejin', 'sungho', 'minyoung', 'dongwook', 'jihyun',
        'hyunsu', 'seojin', 'minji', 'jihun', 'yena', 'sungwook', 'seohyun', 'mingyeong', 'jiwoo', 'hyunjin'
    ];
    
    $prefix = $emailPrefixes[array_rand($emailPrefixes)];
    $domain = $domains[array_rand($domains)];
    
    return $prefix . '@' . $domain;
}

// 나이 생성 함수 (20~50 사이)
function generateAge() {
    return rand(20, 50);
}

try {
    // 기존 데이터 삭제 (선택사항)
    $pdo->exec("DELETE FROM users");
    
    // 10명의 가상 사용자 데이터 생성
    $users = [];
    for ($i = 0; $i < 10; $i++) {
        $name = generateKoreanName();
        $email = generateEmail($name);
        $age = generateAge();
        
        $users[] = [
            'name' => $name,
            'email' => $email,
            'age' => $age
        ];
    }
    
    // 데이터베이스에 삽입
    $sql = "INSERT INTO users (name, email, age) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    $insertCount = 0;
    foreach ($users as $user) {
        try {
            $stmt->execute([$user['name'], $user['email'], $user['age']]);
            $insertCount++;
        } catch(PDOException $e) {
            // 중복 이메일 등의 오류는 무시하고 계속 진행
            continue;
        }
    }
    
    // 성공 메시지 출력
    echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 10px;'>";
    echo "<h3>✅ 샘플 데이터 삽입 완료</h3>";
    echo "<p><strong>{$insertCount}명의 사용자</strong>가 성공적으로 추가되었습니다.</p>";
    echo "<p><strong>생성된 사용자 목록:</strong></p>";
    echo "<table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>";
    echo "<tr style='background-color: #f8f9fa;'>";
    echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>이름</th>";
    echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>이메일</th>";
    echo "<th style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>나이</th>";
    echo "</tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['name']}</td>";
        echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['email']}</td>";
        echo "<td style='border: 1px solid #dee2e6; padding: 8px;'>{$user['age']}세</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
} catch(PDOException $e) {
    // 에러 메시지 출력
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px;'>";
    echo "<h3>❌ 데이터 삽입 실패</h3>";
    echo "<p><strong>오류 코드:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>오류 메시지:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>해결 방법:</strong></p>";
    echo "<ul>";
    echo "<li>users 테이블이 생성되었는지 확인하세요</li>";
    echo "<li>데이터베이스 연결을 확인하세요</li>";
    echo "<li>사용자 권한이 INSERT 권한을 가지고 있는지 확인하세요</li>";
    echo "</ul>";
    echo "</div>";
}
?>
