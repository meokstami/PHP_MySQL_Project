<?php
require_once 'db_config.php';

// JSON 응답을 위한 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// POST 요청만 허용
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'POST 요청만 허용됩니다.']);
    exit;
}

// POST 데이터 받기
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$age = trim($_POST['age'] ?? '');

// 입력값 검증
if (empty($name)) {
    echo json_encode(['status' => 'error', 'message' => '이름을 입력해주세요.']);
    exit;
}

if (empty($email)) {
    echo json_encode(['status' => 'error', 'message' => '이메일을 입력해주세요.']);
    exit;
}

if (empty($age)) {
    echo json_encode(['status' => 'error', 'message' => '나이를 입력해주세요.']);
    exit;
}

// 이름 길이 검증 (VARCHAR(50))
if (mb_strlen($name) > 50) {
    echo json_encode(['status' => 'error', 'message' => '이름은 50자 이하로 입력해주세요.']);
    exit;
}

// 이메일 형식 검증
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => '올바른 이메일 형식을 입력해주세요.']);
    exit;
}

// 이메일 길이 검증 (VARCHAR(100))
if (strlen($email) > 100) {
    echo json_encode(['status' => 'error', 'message' => '이메일은 100자 이하로 입력해주세요.']);
    exit;
}

// 나이 검증
if (!is_numeric($age)) {
    echo json_encode(['status' => 'error', 'message' => '나이는 숫자로 입력해주세요.']);
    exit;
}

$age = (int)$age;
if ($age < 1 || $age > 150) {
    echo json_encode(['status' => 'error', 'message' => '나이는 1~150 사이의 값으로 입력해주세요.']);
    exit;
}

try {
    // 데이터베이스에 삽입
    $sql = "INSERT INTO users (name, email, age) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $age]);
    
    // 성공 응답
    echo json_encode([
        'status' => 'success',
        'message' => '사용자가 성공적으로 추가되었습니다.',
        'data' => [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'email' => $email,
            'age' => $age
        ]
    ]);
    
} catch(PDOException $e) {
    // 데이터베이스 오류 처리
    if ($e->getCode() == 23000) {
        // 중복 키 오류 (이메일 중복)
        echo json_encode(['status' => 'error', 'message' => '이미 존재하는 이메일입니다.']);
    } else {
        // 기타 데이터베이스 오류
        echo json_encode(['status' => 'error', 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
} catch(Exception $e) {
    // 일반적인 오류
    echo json_encode(['status' => 'error', 'message' => '오류가 발생했습니다.']);
}
?>
