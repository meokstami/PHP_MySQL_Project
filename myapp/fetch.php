<?php
require_once 'db_config.php';

// JSON 응답을 위한 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 디버깅을 위한 오류 출력 비활성화 (JSON 응답을 위해)
ini_set('display_errors', 0);

try {
    // users 테이블의 전체 데이터 조회
    $sql = "SELECT id, name, email, age, reg_date FROM users ORDER BY reg_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    // 등록일을 YYYY-MM-DD 형식으로 포맷
    foreach ($users as &$user) {
        if ($user['reg_date']) {
            $user['reg_date'] = date('Y-m-d', strtotime($user['reg_date']));
        }
    }
    
    // 성공 응답
    echo json_encode([
        'status' => 'success',
        'message' => '데이터를 성공적으로 조회했습니다.',
        'count' => count($users),
        'data' => $users
    ], JSON_UNESCAPED_UNICODE);
    
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
