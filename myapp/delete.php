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
$id = trim($_POST['id'] ?? '');

// 입력값 검증
if (empty($id)) {
    echo json_encode(['status' => 'error', 'message' => '사용자 ID를 입력해주세요.']);
    exit;
}

// ID 검증 (숫자여야 함)
if (!is_numeric($id)) {
    echo json_encode(['status' => 'error', 'message' => '올바른 사용자 ID를 입력해주세요.']);
    exit;
}

$id = (int)$id;

try {
    // 먼저 해당 ID의 사용자가 존재하는지 확인
    $checkSql = "SELECT id, name FROM users WHERE id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$id]);
    $user = $checkStmt->fetch();
    
    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => '삭제할 사용자를 찾을 수 없습니다.']);
        exit;
    }
    
    // 데이터베이스에서 삭제
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    // 삭제된 행 수 확인
    if ($stmt->rowCount() > 0) {
        // 성공 응답
        echo json_encode([
            'status' => 'success',
            'message' => '삭제 완료',
            'data' => [
                'id' => $id,
                'name' => $user['name']
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status' => 'error', 'message' => '삭제에 실패했습니다.']);
    }
    
} catch(PDOException $e) {
    // 데이터베이스 오류 처리
    echo json_encode([
        'status' => 'error',
        'message' => '데이터베이스 오류가 발생했습니다.',
        'error_code' => $e->getCode(),
        'error_message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    
} catch(Exception $e) {
    // 일반적인 오류
    echo json_encode([
        'status' => 'error',
        'message' => '오류가 발생했습니다.',
        'error_message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
