<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - User Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .header h1 {
      font-weight: 700;
      margin: 0;
    }

    .table-container {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .table {
      margin-bottom: 0;
    }

    .table thead {
      background-color: #f8f9fa;
      border-bottom: 2px solid #dee2e6;
    }

    .table th {
      font-weight: 600;
      color: #495057;
      padding: 15px;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
    }

    .table td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:hover {
      background-color: #f8f9fa;
    }

    .badge-email {
      background-color: #e7f3ff;
      color: #0066cc;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
    }

    .badge-phone {
      background-color: #e7ffe7;
      color: #00cc00;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
    }

    .password-cell {
      font-family: 'Courier New', monospace;
      background-color: #f0f0f0;
      padding: 8px 12px;
      border-radius: 5px;
      font-size: 0.85rem;
      word-break: break-all;
    }

    .user-count {
      background: rgba(255, 255, 255, 0.2);
      padding: 10px 15px;
      border-radius: 5px;
      display: inline-block;
      font-size: 0.95rem;
    }

    .no-data {
      text-align: center;
      padding: 40px;
      color: #999;
    }

    .no-data i {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.5;
    }

    .action-btn {
      padding: 5px 10px;
      font-size: 0.85rem;
      margin: 0 2px;
    }

    @media (max-width: 768px) {
      .table {
        font-size: 0.9rem;
      }

      .table th, .table td {
        padding: 10px;
      }
    }
  </style>
</head>
<body>

  <div class="admin-container">
    <!-- Header -->
    <div class="header">
      <h1><i class="bi bi-shield-lock"></i> Admin Dashboard</h1>
      <p class="mt-2 mb-0">Manage and view all user accounts</p>
      <div class="user-count mt-3">
        <i class="bi bi-people"></i> Total Users: <strong>{{ count($users) }}</strong>
      </div>
    </div>

    <!-- Users Table -->
    <div class="table-container">
      @if(count($users) > 0)
        <table class="table">
          <thead>
            <tr>
              <th><i class="bi bi-hash"></i> ID</th>
              <th><i class="bi bi-person"></i> Name</th>
              <th><i class="bi bi-at"></i> Username</th>
              <th><i class="bi bi-envelope"></i> Email</th>
              <th><i class="bi bi-telephone"></i> Phone</th>
              <th><i class="bi bi-key"></i> Password</th>
              <th><i class="bi bi-calendar"></i> Created</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
              <tr>
                <td><strong>#{{ $user->id }}</strong></td>
                <td>{{ $user->name ?? 'N/A' }}</td>
                <td><strong>{{ $user->username ?? 'N/A' }}</strong></td>
                <td>
                  <span class="badge-email">{{ $user->email ?? 'N/A' }}</span>
                </td>
                <td>
                  <span class="badge-phone">{{ $user->phone ?? 'N/A' }}</span>
                </td>
                <td>
                  <div class="password-cell">
                    {{ $user->password ?? 'N/A' }}
                  </div>
                </td>
                <td><small>{{ $user->created_at->format('M d, Y') }}</small></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="no-data">
          <i class="bi bi-inbox"></i>
          <h5>No Users Found</h5>
          <p>There are currently no registered users in the system.</p>
        </div>
      @endif
    </div>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 30px; color: #999;">
      <p><small>&copy; 2026 Admin Panel. All rights reserved.</small></p>
      <a href="/" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Login</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
