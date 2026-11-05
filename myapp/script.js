// 전역 변수
let editingUserId = null;

// DOM 요소들
const userForm = document.getElementById('userForm');
const userTableBody = document.getElementById('userTableBody');
const messageDiv = document.getElementById('message');
const addBtn = document.getElementById('addBtn');
const updateBtn = document.getElementById('updateBtn');
const cancelBtn = document.getElementById('cancelBtn');

// 페이지 로드 시 사용자 목록 불러오기
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
});

// 폼 제출 이벤트
userForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (editingUserId) {
        updateUser();
    } else {
        addUser();
    }
});

// 취소 버튼 이벤트
cancelBtn.addEventListener('click', function() {
    resetForm();
});

// 사용자 추가 함수
async function addUser() {
    const formData = new FormData(userForm);
    
    try {
        const response = await fetch('insert.php', {
            method: 'POST',
            body: formData
        });
        
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('JSON 파싱 오류:', text);
            showMessage('서버 응답 오류가 발생했습니다.', 'error');
            return;
        }
        
        if (result.status === 'success') {
            showMessage(result.message, 'success');
            userForm.reset();
            loadUsers(); // 목록 새로고침
        } else {
            showMessage(result.message, 'error');
        }
    } catch (error) {
        showMessage('오류가 발생했습니다: ' + error.message, 'error');
    }
}

// 사용자 목록 불러오기 함수
async function loadUsers() {
    try {
        userTableBody.innerHTML = '<tr><td colspan="6" class="loading">로딩 중...</td></tr>';
        
        const response = await fetch('fetch.php');
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('JSON 파싱 오류:', text);
            showMessage('서버 응답 오류가 발생했습니다.', 'error');
            userTableBody.innerHTML = '<tr><td colspan="6" class="loading">사용자 목록을 불러올 수 없습니다.</td></tr>';
            return;
        }
        
        if (result.status === 'success') {
            displayUsers(result.data);
        } else {
            showMessage(result.message, 'error');
            userTableBody.innerHTML = '<tr><td colspan="6" class="loading">사용자 목록을 불러올 수 없습니다.</td></tr>';
        }
    } catch (error) {
        showMessage('오류가 발생했습니다: ' + error.message, 'error');
        userTableBody.innerHTML = '<tr><td colspan="6" class="loading">사용자 목록을 불러올 수 없습니다.</td></tr>';
    }
}

// 사용자 목록 표시 함수
function displayUsers(users) {
    if (users.length === 0) {
        userTableBody.innerHTML = '<tr><td colspan="6" class="loading">등록된 사용자가 없습니다.</td></tr>';
        return;
    }
    
    userTableBody.innerHTML = users.map((user, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${escapeHtml(user.name)}</td>
            <td>${escapeHtml(user.email)}</td>
            <td>${user.age}세</td>
            <td>${user.reg_date}</td>
            <td class="actions">
                <button class="btn btn-info" onclick="editUser(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', ${user.age})">
                    수정
                </button>
                <button class="btn btn-danger" onclick="deleteUser(${user.id})">
                    삭제
                </button>
            </td>
        </tr>
    `).join('');
}

// 사용자 수정 함수
async function updateUser() {
    const formData = new FormData(userForm);
    formData.append('id', editingUserId);
    
    try {
        const response = await fetch('update.php', {
            method: 'POST',
            body: formData
        });
        
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('JSON 파싱 오류:', text);
            showMessage('서버 응답 오류가 발생했습니다.', 'error');
            return;
        }
        
        if (result.status === 'success') {
            showMessage(result.message, 'success');
            resetForm();
            loadUsers(); // 목록 새로고침
        } else {
            showMessage(result.message, 'error');
        }
    } catch (error) {
        showMessage('오류가 발생했습니다: ' + error.message, 'error');
    }
}

// 사용자 삭제 함수
async function deleteUser(id) {
    if (!confirm('정말로 이 사용자를 삭제하시겠습니까?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('id', id);
    
    try {
        const response = await fetch('delete.php', {
            method: 'POST',
            body: formData
        });
        
        const text = await response.text();
        let result;
        
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('JSON 파싱 오류:', text);
            showMessage('서버 응답 오류가 발생했습니다.', 'error');
            return;
        }
        
        if (result.status === 'success') {
            showMessage(result.message, 'success');
            loadUsers(); // 목록 새로고침
        } else {
            showMessage(result.message, 'error');
        }
    } catch (error) {
        showMessage('오류가 발생했습니다: ' + error.message, 'error');
    }
}

// 사용자 편집 모드로 전환
function editUser(id, name, email, age) {
    editingUserId = id;
    
    document.getElementById('name').value = name;
    document.getElementById('email').value = email;
    document.getElementById('age').value = age;
    
    addBtn.style.display = 'none';
    updateBtn.style.display = 'inline-block';
    cancelBtn.style.display = 'inline-block';
    
    // 폼으로 스크롤
    userForm.scrollIntoView({ behavior: 'smooth' });
}

// 폼 초기화
function resetForm() {
    editingUserId = null;
    userForm.reset();
    
    addBtn.style.display = 'inline-block';
    updateBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
}

// 메시지 표시 함수
function showMessage(message, type) {
    messageDiv.textContent = message;
    messageDiv.className = `message ${type} show`;
    
    setTimeout(() => {
        messageDiv.classList.remove('show');
    }, 3000);
}

// HTML 이스케이프 함수
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// API 호출 공통 함수 (선택사항)
async function apiCall(url, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        };
        
        if (data && method === 'POST') {
            const formData = new FormData();
            for (const key in data) {
                formData.append(key, data[key]);
            }
            options.body = formData;
        }
        
        const response = await fetch(url, options);
        const text = await response.text();
        
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('JSON 파싱 오류:', text);
            throw new Error('서버 응답을 파싱할 수 없습니다.');
        }
    } catch (error) {
        console.error('API 호출 오류:', error);
        throw error;
    }
}
