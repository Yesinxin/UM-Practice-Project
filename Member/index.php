<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .action-icon {
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-users me-2"></i>會員系統</a>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>新增會員</h5>
                    </div>
                    <div class="card-body">
                        <form id="createForm">
                            <div class="mb-3">
                                <label for="createName" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="createName" required>
                            </div>
                            <div class="mb-3">
                                <label for="createEmail" class="form-label">電子郵件</label>
                                <input type="email" class="form-control" id="createEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="createPassword" class="form-label">密碼</label>
                                <input type="password" class="form-control" id="createPassword" required>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus me-2"></i>新增</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>會員列表</h5>
                    </div>
                    <div class="card-body">
                        <div id="userList" class="list-group"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 修改會員的 Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">修改會員</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm">
                        <input type="hidden" id="updateId">
                        <div class="mb-3">
                            <label for="updateName" class="form-label">姓名</label>
                            <input type="text" class="form-control" id="updateName" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateEmail" class="form-label">電子郵件</label>
                            <input type="email" class="form-control" id="updateEmail" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" id="updateSubmit">修改</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://localhost/UM%20Practice%20Project/Member/api.php';

        function showAlert(title, text, icon) {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: '確定'
            });
        }

        async function getAllUsers() {
            try {
                const response = await axios.get(`${API_URL}?action=getAll`);
                const userList = document.getElementById('userList');
                userList.innerHTML = '';
                response.data.forEach(user => {
                    userList.innerHTML += `
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">${user.name}</h5>
                                <p class="mb-1">${user.email}</p>
                                <small>ID: ${user.id}</small>
                            </div>
                            <div>
                                <i class="fas fa-edit text-warning action-icon" onclick="openUpdateModal(${user.id}, '${user.name}', '${user.email}')"></i>
                                <i class="fas fa-trash-alt text-danger action-icon" onclick="deleteUser(${user.id})"></i>
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                showAlert('錯誤', '獲取會員資料失敗', 'error');
            }
        }

        document.getElementById('createForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('createName').value;
            const email = document.getElementById('createEmail').value;
            const password = document.getElementById('createPassword').value;
            
            try {
                const response = await axios.post(`${API_URL}?action=create`, { name, email, password });
                if (response.data.success) {
                    await showAlert('成功', '新增會員成功', 'success');
                    getAllUsers();
                    e.target.reset();
                } else {
                    showAlert('失敗', '新增會員失敗', 'error');
                }
            } catch (error) {
                showAlert('錯誤', '發生錯誤', 'error');
            }
        });

        function openUpdateModal(id, name, email) {
            document.getElementById('updateId').value = id;
            document.getElementById('updateName').value = name;
            document.getElementById('updateEmail').value = email;
            new bootstrap.Modal(document.getElementById('updateModal')).show();
        }

        document.getElementById('updateSubmit').addEventListener('click', async () => {
            const id = document.getElementById('updateId').value;
            const name = document.getElementById('updateName').value;
            const email = document.getElementById('updateEmail').value;
            
            try {
                const response = await axios.post(`${API_URL}?action=update`, { id, name, email });
                if (response.data.success) {
                    await showAlert('成功', '修改會員成功', 'success');
                    getAllUsers();
                    bootstrap.Modal.getInstance(document.getElementById('updateModal')).hide();
                } else {
                    showAlert('失敗', '修改會員失敗', 'error');
                }
            } catch (error) {
                showAlert('錯誤', '發生錯誤', 'error');
            }
        });

        async function deleteUser(id) {
            try {
                const result = await Swal.fire({
                    title: '確定要刪除嗎？',
                    text: "此操作無法撤銷！",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '是的，刪除！',
                    cancelButtonText: '取消'
                });

                if (result.isConfirmed) {
                    const response = await axios.post(`${API_URL}?action=delete`, { id });
                    if (response.data.success) {
                        await showAlert('成功', '刪除會員成功', 'success');
                        getAllUsers();
                    } else {
                        showAlert('失敗', '刪除會員失敗', 'error');
                    }
                }
            } catch (error) {
                showAlert('錯誤', '發生錯誤', 'error');
            }
        }

        // 頁面加載時自動獲取會員列表
        document.addEventListener('DOMContentLoaded', getAllUsers);
    </script>
</body>
</html>