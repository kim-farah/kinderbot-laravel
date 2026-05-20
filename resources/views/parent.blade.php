<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Kinderbot</title>
    <style>
        :root {
            --dark-blue: #1E3A5F;
            --orange: #FF6B35;
            --white: #FFFFFF;
            --light-gray: #F8F9FA;
            --gray: #6C757D;
            --border-gray: #E9ECEF;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: var(--light-gray);
            height: 100vh;
            overflow: hidden;
            transition: background 0.3s ease;
        }
        .dashboard-container { display: flex; flex-direction: column; height: 100vh; }
        .top-bar {
            background: var(--dark-blue);
            color: var(--white);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
            flex-shrink: 0;
        }
        .logo { font-size: 20px; font-weight: 600; }
        .user-info { display: flex; align-items: center; gap: 16px; }
        .logout-btn {
            background: transparent;
            border: 1px solid var(--white);
            color: var(--white);
            padding: 6px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .logout-btn:hover { background: var(--orange); border-color: var(--orange); }
        .main-layout { display: flex; flex: 1; overflow: hidden; }
        .sidebar {
            width: 260px;
            background: var(--white);
            border-right: 1px solid var(--border-gray);
            padding: 24px 0;
            flex-shrink: 0;
            overflow-y: auto;
        }
        .sidebar nav ul { list-style: none; }
        .sidebar nav li {
            padding: 12px 24px;
            cursor: pointer;
            color: var(--gray);
            font-size: 15px;
            transition: all 0.2s;
        }
        .sidebar nav li:hover { background: var(--light-gray); color: var(--dark-blue); }
        .sidebar nav li.active { background: var(--orange); color: var(--white); }
        .content {
            flex: 1;
            padding: 24px 32px;
            overflow-y: auto;
            transition: background 0.3s ease;
        }
        .page-content { display: none; }
        .page-content.active { display: block; }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .section-header h2 { font-size: 24px; color: var(--dark-blue); margin: 0; }
        .btn-primary {
            background: var(--orange);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-small {
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 6px;
            border: 1px solid var(--border-gray);
            background: white;
            cursor: pointer;
        }
        .child-profile {
            background: linear-gradient(135deg, var(--dark-blue) 0%, #2c4a6e 100%);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 30px;
            color: white;
        }
        .child-profile h2 { font-size: 28px; margin-bottom: 8px; }
        .progress-section { margin-top: 16px; }
        .progress-label { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .progress-bar-bg { background: rgba(255,255,255,0.3); border-radius: 10px; height: 10px; overflow: hidden; }
        .progress-bar-fill { background: var(--orange); border-radius: 10px; height: 100%; transition: width 0.5s; }
        .stats-grid { display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; }
        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 20px;
            flex: 1;
            min-width: 150px;
            text-align: center;
            border: 1px solid var(--border-gray);
        }
        .stat-number { font-size: 28px; font-weight: bold; color: var(--dark-blue); }
        .stat-label { color: var(--gray); font-size: 14px; margin-top: 5px; }
        .data-table {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border-gray);
            overflow-x: auto;
            width: 100%;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-gray); }
        th { background: var(--light-gray); font-weight: 600; color: var(--dark-blue); }
        .status-completed { color: var(--success); font-weight: 500; }
        .status-progress { color: var(--warning); font-weight: 500; }
        .notes-list { display: flex; flex-direction: column; gap: 16px; }
        .note-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-gray);
            border-left: 4px solid var(--orange);
        }
        .note-header { display: flex; justify-content: space-between; margin-bottom: 12px; }
        .note-teacher { font-weight: 600; color: var(--dark-blue); }
        .note-date { color: var(--gray); font-size: 12px; }
        .settings-item {
            padding: 16px;
            border-bottom: 1px solid var(--border-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .settings-item:last-child { border-bottom: none; }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            width: 400px;
            max-width: 90%;
            padding: 24px;
        }
        .modal-content h3 { margin-bottom: 20px; color: var(--dark-blue); }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 500; }
        .form-input { width: 100%; padding: 10px; border: 1px solid var(--border-gray); border-radius: 8px; }
        .modal-buttons { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; }
        .readonly-badge {
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            color: #666;
            display: inline-block;
            margin-left: 10px;
        }
        body.dark-mode { background: #1a1a2e; }
        body.dark-mode .sidebar { background: #16213e; border-right-color: #2a2a4a; }
        body.dark-mode .sidebar nav li { color: #aaa; }
        body.dark-mode .sidebar nav li:hover { background: #1f2a4a; color: white; }
        body.dark-mode .content { background: #1a1a2e; }
        body.dark-mode .stat-card, body.dark-mode .data-table, body.dark-mode .note-card, body.dark-mode .modal-content { background: #16213e; border-color: #2a2a4a; color: #ddd; }
        body.dark-mode .stat-number { color: var(--orange); }
        body.dark-mode th { background: #0f0f2a; color: white; }
        body.dark-mode td { border-bottom-color: #2a2a4a; }
        body.dark-mode .note-message { color: #ccc; }
        /* Prevent browser password manager on message modal */
input, textarea, select {
    transition: background-color 5000s ease-in-out 0s;

}

/* Prevent browser password manager on all non-login forms */
form:not(.login-form) input,
form:not(.login-form) textarea,
form:not(.login-form) select {
    -webkit-text-security: none !important;
}
/* Conversation list hover effect */
#messagesContainer div[onclick]:hover {
    background: #f5f5f5 !important;
    /*transform: translateX(2px);*/
    transition: all 0.2s;
}

body.dark-mode #messagesContainer div[onclick]:hover {
    background: #2a2a4a !important;
}

.btn-cancel {
    background: #f0f0f0;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-cancel:hover {
    background: #e0e0e0;
}



#conversationMessages div[style*="text-align:right"] {
    margin-left: auto;
}

#conversationMessages div[style*="text-align:left"] {
    margin-right: auto;
}

/* Searchable recipient input */
.recipient-search-container {
    position: relative;
    width: 100%;
}

.recipient-search-input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border-gray);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.recipient-search-input:focus {
    border-color: var(--orange);
}

.recipient-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid var(--border-gray);
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.recipient-search-results.show {
    display: block;
}

.recipient-search-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 10px;
}

.recipient-search-item:hover {
    background: var(--light-gray);
}

.recipient-search-item .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--orange);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
}

.recipient-search-item .info {
    flex: 1;
}

.recipient-search-item .name {
    font-weight: 500;
    font-size: 14px;
}

.recipient-search-item .type {
    font-size: 11px;
    color: var(--gray);
}

body.dark-mode .recipient-search-results {
    background: #16213e;
    border-color: #2a2a4a;
}

body.dark-mode .recipient-search-item:hover {
    background: #1f2a4a;
}

/* Prevent browser password manager on new message modal */
#newMessageModal input,
#newMessageModal textarea {
    transition: background-color 5000s ease-in-out 0s;
}

/* Remove shift from New Chat modal search results */
#newMessageModal .recipient-search-item,
#newMessageModal .recipient-search-item:hover,
#newMessageModal div[onclick],
#newMessageModal div[onclick]:hover {
    transform: none !important;
    -webkit-transform: none !important;
    transition: none !important;
}
#conversationMessages button {
    transition: transform 0.2s;
}

#conversationMessages button:hover {
    transform: scale(1.1);
}

.parent-message {
    text-align: right;
    margin-bottom: 5px;
}
.parent-message .bubble {
    display: inline-block;
    max-width: 70%;
    background: #FF6B35;
    color: white;
    padding: 8px 12px;
    border-radius: 18px;
    text-align: left;
}
.parent-delete {
    text-align: right;
    margin-bottom: 15px;
}
.parent-delete button {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 11px;
    cursor: pointer;
}
.other-message {
    text-align: left;
    margin-bottom: 15px;
}
.other-message .bubble {
    display: inline-block;
    max-width: 70%;
    background: #f0f0f0;
    color: #333;
    padding: 8px 12px;
    border-radius: 18px;
    text-align: left;
}

/* Password Modal Styles - Teacher Style */
#passwordModal .modal-content {
    background: white;
    padding: 24px;
    border-radius: 16px;
    width: 500px;
    max-width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

#passwordModal .modal-content h3 {
    margin-bottom: 20px;
    color: var(--dark-blue);
    font-size: 20px;
    font-weight: 600;
}

#passwordModal .form-group {
    margin-bottom: 16px;
}

#passwordModal .form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
    font-size: 14px;
    color: #333;
}

#passwordModal .form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-gray);
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
    font-family: inherit;
}

#passwordModal .form-input:focus {
    outline: none;
    border-color: var(--orange);
}

#passwordModal .modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
}

#passwordModal .btn-cancel {
    background: #f0f0f0;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

#passwordModal .btn-cancel:hover {
    background: #e0e0e0;
}

#passwordModal .btn-save {
    background: var(--orange);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: opacity 0.2s;
}

#passwordModal .btn-save:hover {
    opacity: 0.9;
}

/* Dark mode support */
body.dark-mode #passwordModal .modal-content {
    background: #16213e;
}

body.dark-mode #passwordModal .modal-content h3 {
    color: var(--orange);
}

body.dark-mode #passwordModal .form-group label {
    color: #ddd;
}

body.dark-mode #passwordModal .form-input {
    background: #0f0f2a;
    border-color: #2a2a4a;
    color: white;
}
/* Activity Detail Card Styles */
.activity-detail-card {
    background: white;
    border-radius: 16px;
    margin-bottom: 24px;
    overflow: hidden;
    border: 1px solid var(--border-gray);
    transition: transform 0.2s, box-shadow 0.2s;
}
.activity-detail-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Competency Table Styles */
.competency-table {
    width: 100%;
    border-collapse: collapse;
}

.competency-table thead tr {
    background: var(--light-gray);
}

.competency-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: var(--dark-blue);
    font-size: 14px;
}

.competency-table td {
    padding: 12px;
    border-bottom: 1px solid var(--border-gray);
    vertical-align: middle;
}

.competency-table tr:last-child td {
    border-bottom: none;
}

/* Row hover effect */
.competency-table tbody tr {
    transition: background 0.2s;
}
.competency-table tbody tr:hover {
    background: #f5f5f5 !important;
}

/* Rated row */
.competency-row-rated {
    background: var(--white);
}

/* Pending row */
.competency-row-pending {
    background: #FFF8E7;
}

/* Competency name */
.competency-name {
    font-weight: 600;
    color: var(--dark-blue);
    font-size: 14px;
}

/* Rating stars */
.rating-stars {
    color: #FFB81C;
    font-size: 16px;
    letter-spacing: 2px;
    white-space: nowrap;
}

.rating-stars-empty {
    color: #ccc;
    font-size: 16px;
    letter-spacing: 2px;
    white-space: nowrap;
}

.rating-number {
    font-size: 13px;
    margin-left: 8px;
    color: var(--gray);
}

/* Teacher comment */
.teacher-comment {
    font-size: 13px;
    color: var(--gray);
    font-style: italic;
}

.comment-date {
    font-size: 11px;
    color: var(--gray);
    margin-top: 4px;
    display: block;
}

/* Status badges */
.status-rated {
    color: green;
    font-weight: 500;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-pending {
    color: var(--orange);
    font-weight: 500;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Messages */
.warning-message {
    margin-top: 15px;
    padding: 12px;
    background: #FFF8E7;
    border-radius: 8px;
    font-size: 13px;
    color: var(--orange);
    border-left: 3px solid var(--orange);
}

.success-message {
    margin-top: 15px;
    padding: 12px;
    background: #e8f5e9;
    border-radius: 8px;
    font-size: 13px;
    color: green;
    border-left: 3px solid green;
}

/* Activity header */
.activity-header {
    background: var(--dark-blue);
    color: white;
    padding: 16px 20px;
}

.activity-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.activity-meta {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 13px;
    opacity: 0.9;
}

/* Activity body */
.activity-body {
    padding: 20px;
}

.activity-objective {
    margin-bottom: 16px;
}

.activity-objective strong,
.activity-overview strong {
    color: var(--dark-blue);
}

/* Dark mode support */
body.dark-mode .activity-detail-card {
    background: #16213e !important;
    border-color: #2a2a4a;
}

body.dark-mode .competency-table thead tr {
    background: #0f0f2a;
}

body.dark-mode .competency-table th {
    color: var(--orange);
}

body.dark-mode .competency-row-rated {
    background: #16213e;
}

body.dark-mode .competency-row-pending {
    background: #2a2a3a;
}

body.dark-mode .competency-table tbody tr:hover {
    background: #1f2a4a !important;
}

body.dark-mode .competency-name {
    color: var(--orange);
}

body.dark-mode .teacher-comment {
    color: #aaa;
}

body.dark-mode .warning-message {
    background: #2a2a3a;
    border-left-color: var(--orange);
    color: #ffaa66;
}

body.dark-mode .success-message {
    background: #1a3a1a;
    border-left-color: green;
    color: #88ff88;
}

body.dark-mode .activity-objective strong,
body.dark-mode .activity-overview strong {
    color: var(--orange);
}
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="top-bar">
            <div class="logo">👨‍👩‍👧 Kinderbot Parent</div>
            <div class="user-info">
                <span>👤 Parent Name</span>
                <!--<button class="logout-btn" onclick="location.href='{{ route('login') }}'">Logout</button>-->
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
    @csrf
    <button type="submit" class="logout-btn">Logout</button>
</form>

            </div>
        </div>
        <div class="main-layout">
            <div class="sidebar">
                <nav>
                    <ul>
                        <li class="active" data-page="dashboard">📊 Dashboard</li>
                        <li data-page="mychild">👦 My Child</li>
                        <li data-page="activities">📝 Activities</li>
                        <li data-page="messages">💬 Messages
                            <span id="messagesUnreadBadge" style="background:#dc3545; color:white; border-radius:10px; padding:2px 6px; font-size:10px; margin-left:5px; display:none;">0</span>
                        </li>
                        <li data-page="settings">⚙️ Settings</li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <div id="dashboardPage" class="page-content active">
                    <div class="section-header">
                        <h2>Welcome back! <span class="readonly-badge">Read-only view</span></h2>
                    </div>
                    <div class="child-profile">
    <h2 id="childName">Loading...</h2>
    <div id="childClass">Loading...</div>
    <div class="progress-section">
        <div class="progress-label">
            <span>📊 Overall Progress</span>
            <span id="progressPercent">0%</span>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" id="progressFill" style="width:0%"></div>
        </div>
    </div>
</div>
                    <div class="stats-grid">
                      <div class="stat-card">
    <div class="stat-number" id="completedCount">0</div>
    <div class="stat-label">Activities Completed</div>
</div>
<div class="stat-card">
    <div class="stat-number" id="avgRating">0</div>
    <div class="stat-label">Average Rating</div>
</div>

</div>
</div>
                <div id="mychildPage" class="page-content">
                    <div class="section-header"><h2>My Child's Profile</h2></div>
                    <div id="childProfileFull" class="data-table"></div>
                </div>

                <div id="activitiesPage" class="page-content">
                    <div class="section-header"><h2>All Activities</h2></div>
                    <div id="allActivitiesTable" class="data-table"></div>
                </div>

                <div id="messagesPage" class="page-content">
                    <div class="section-header">
                        <h2>Messages</h2>
                        <button class="btn-primary" id="newMessageBtn">+ New Chat</button>
                    </div>
                    <div id="messagesContainer" class="data-table"></div>
                </div>

                <div id="settingsPage" class="page-content">
                    <div class="section-header"><h2>Settings</h2></div>
                    <div class="data-table">
                        <div class="settings-item">
                            <div><strong>🌙 Dark Mode</strong><div style="font-size:13px;color:gray;">Switch theme preference</div></div>
                            <div><button class="btn-small" id="darkModeBtn">Light Mode</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>🔑 Change Password</strong><div style="font-size:13px;color:gray;">Update your account password</div></div>
                            <div><button class="btn-small" id="changePasswordBtn">Change</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Message Modal (WhatsApp Style) -->
<div id="newMessageModal" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">New Chat</h3>
            <button class="modal-close" onclick="closeNewMessageModal()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div class="recipient-search-container">
            <input type="text" id="recipientSearch" class="recipient-search-input" placeholder="Search by name..." autocomplete="off">
            <div id="recipientSearchResults" class="recipient-search-results"></div>
        </div>
        <div id="messageInputSection" style="display:none; margin-top:20px;">
            <textarea id="newMessageText" rows="3" class="form-input" placeholder="Type your message here..."></textarea>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeNewMessageModal()">Cancel</button>
                <button class="btn-primary" onclick="sendNewMessage()">Send</button>
            </div>
        </div>
    </div>
</div>

<div id="passwordModal" class="modal">
    <div class="modal-content">
        <h3>🔑 Change Password</h3>
        <form id="passwordChangeForm" onsubmit="return false;">
            <div class="form-group">
                <label>Current Password:</label>
                <input type="password" id="currentPassword" class="form-input" placeholder="Enter current password" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" id="newPassword" class="form-input" placeholder="Enter new password (min 6 characters)" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label>Confirm New Password:</label>
                <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm new password" autocomplete="new-password">
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closePasswordModal()">Cancel</button>
                <button type="submit" class="btn-save" onclick="changePassword()">Update Password</button>
            </div>
        </form>
    </div>
</div>
<!-- Conversation Modal -->
<div id="conversationModal" class="modal">
    <div class="modal-content" style="max-width:600px; width:35%; max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 id="conversationTitle">Conversation</h3>
            <button class="modal-close" onclick="closeConversationModal()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div id="conversationMessages" style="max-height:400px; overflow-y:auto; margin-bottom:20px;">
            <div style="text-align:center; padding:20px;">Loading messages...</div>
        </div>
        <div id="replySection" style="border-top:1px solid #ddd; padding-top:15px;">
            <div class="form-group">
                <label>Reply:</label>
                <textarea id="replyMessage" rows="3" class="form-input" placeholder="Type your reply here..."></textarea>
            </div>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeConversationModal()">Cancel</button>
                <button class="btn-primary" onclick="sendReply()">Send Reply</button>
            </div>
        </div>
    </div>
</div>

    <script>
    // Get parent name from Laravel
    const parentName = @json($parentName);
    document.querySelector('.user-info span').innerHTML = `👤 ${parentName}`;

    let currentChildId = null;

    // ==================== LOAD REAL DATA FROM API ====================

    async function fetchAPI(url) {
        try {
            const response = await fetch(url, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            return [];
        }
    }


   async function loadChildData() {
    const children = await fetchAPI('/api/parent/children');

    if (children.length === 0) {
        document.getElementById('childName').innerHTML = 'No child linked';
        document.getElementById('childClass').innerHTML = 'Please contact school';
        return;
    }

    const child = children[0];
    currentChildId = child.id;

    // Update profile section
    document.getElementById('childName').innerHTML = child.full_name;
    document.getElementById('childClass').innerHTML = child.class || 'Not enrolled';

    // Load progress
    const progress = await fetchAPI(`/api/parent/child/${child.id}/progress`);
    document.getElementById('progressPercent').innerHTML = `${progress.progress || 0}%`;
    document.getElementById('progressFill').style.width = `${progress.progress || 0}%`;
    document.getElementById('completedCount').innerHTML = progress.completedCount || 0;
    document.getElementById('avgRating').innerHTML = progress.avgRating || 0;

    // Remove notesCount reference - no longer exists

    // Load activities
    await loadChildActivities(child.id);

    // Remove notes loading call
    // await loadChildNotes(child.id);  // DELETE THIS LINE
}

    async function loadChildActivities(childId) {
        const activities = await fetchAPI(`/api/parent/child/${childId}/activities`);
        const container = document.getElementById('activitiesTable');

        if (activities.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px;">No activities yet.</p>';
            return;
        }

        let html = '<table class="data-table"><thead><tr><th>Activity</th><th>Date</th><th>Status</th><th>Rating</th></tr></thead><tbody>';
        activities.forEach(a => {
            const statusClass = a.status === 'completed' ? 'status-completed' : 'status-progress';
            const statusText = a.status === 'completed' ? '✅ Completed' : '🔄 In Progress';
            const stars = '★'.repeat(a.rating) + '☆'.repeat(5 - a.rating);
            html += `<tr>
                <td><strong>${a.activity}</strong></td>
                <td>${a.date}</td>
                <td class="${statusClass}">${statusText}</td>
                <td>${stars}</td>
            </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // ==================== MY CHILD PAGE ====================


async function displayChildProfileFull() {
    const children = await fetchAPI('/api/parent/children');
    if (children.length === 0) {
        document.getElementById('childProfileFull').innerHTML = '<p style="text-align:center;padding:40px;">No child linked</p>';
        return;
    }

    const child = children[0];
    const progress = await fetchAPI(`/api/parent/child/${child.id}/progress`);

    // 4-star display for overall average
    const fullStars = Math.floor(progress.avgRating);
    const emptyStars = 4 - fullStars;
    const starDisplay = '★'.repeat(fullStars) + '☆'.repeat(emptyStars);

    let html = '<table class="data-table"><tbody>';
    html += `<tr><td style="width:150px"><strong>Name</strong></td><td>${escapeHtml(child.full_name)}</td></tr>`;
    html += `<tr><td><strong>Date of Birth</strong></td><td>${child.date_of_birth || '-'}</td></tr>`;
    html += `<tr><td><strong>Age</strong></td><td>${child.age || '-'} years</td></tr>`;
    html += `<tr><td><strong>Class</strong></td><td>${escapeHtml(child.class || 'Not enrolled')}</td></tr>`;
    html += `<tr><td><strong>Teacher</strong></td><td>${escapeHtml(child.teacher || 'Not assigned')}</td></tr>`;
    html += `<tr><td><strong>Overall Progress</strong></td><td>${progress.progress || 0}% <div style="background:#e0e0e0;border-radius:10px;height:6px;margin-top:5px;"><div style="background:var(--orange);width:${progress.progress || 0}%;height:6px;border-radius:10px;"></div></div></td></tr>`;
    html += `<tr><td><strong>Completed Activities</strong></td><td>${progress.completedCount || 0} / ${progress.totalActivities || 0}</td></tr>`;
    html += `<tr><td><strong>Overall Average Rating</strong></td><td>${progress.avgRating || 0} / 4 <span style="color:#FFB81C; margin-left:10px; font-size:16px;">${starDisplay}</span><br><small style="color:var(--gray);">Average of all competency ratings across all activities</small></td></tr>`;
    html += '</tbody></table>';
    document.getElementById('childProfileFull').innerHTML = html;
}

    // ==================== ALL ACTIVITIES PAGE ====================


async function displayAllActivities() {
    if (!currentChildId) {
        const children = await fetchAPI('/api/parent/children');
        if (children.length > 0) currentChildId = children[0].id;
    }

    if (!currentChildId) {
        document.getElementById('allActivitiesTable').innerHTML = '<p style="text-align:center;padding:40px;">No child linked</p>';
        return;
    }

    const activities = await fetchAPI(`/api/parent/child/${currentChildId}/activities-with-ratings`);
    const container = document.getElementById('allActivitiesTable');

    if (activities.length === 0) {
        container.innerHTML = '<p style="text-align:center;padding:40px;">No completed activities yet.</p>';
        return;
    }

    let html = '';
    for (const activity of activities) {
        const fullStars = Math.floor(activity.avg_rating);
        const emptyStars = 4 - fullStars;
        const starDisplay = '★'.repeat(fullStars) + '☆'.repeat(emptyStars);

        html += `
            <div class="activity-detail-card">
                <div class="activity-header">
                    <h3 class="activity-title">${escapeHtml(activity.title)}</h3>
                    <div class="activity-meta">
                        <span>📅 Completed: ${activity.completion_date}</span>
                        <span>⭐ Activity Average: ${activity.avg_rating} / 4 ${starDisplay}</span>
                        <span>📊 ${activity.rated_competencies}/${activity.total_competencies} competencies rated</span>
                    </div>
                </div>
                <div class="activity-body">
                    <div class="activity-objective">
                        <strong>📖 Objective:</strong> ${escapeHtml(activity.objective || 'Not specified')}
                    </div>
                    <div class="activity-overview">
                        <strong>📝 Overview:</strong> ${escapeHtml(activity.overview || 'Not specified')}
                    </div>

                    <h4 style="color:var(--dark-blue); margin:20px 0 15px 0;">🎯 Competencies & Ratings</h4>
                    <table class="competency-table">
                        <thead>
                            <tr>
                                <th style="width:30%">Competency</th>
                                <th style="width:25%; text-align:center;">Rating</th>
                                <th style="width:30%">Teacher's Comment</th>
                                <th style="width:15%; text-align:center;">Status</th>
                            </thead>
                            <tbody>
        `;

        for (const comp of activity.competencies) {
            const hasRating = comp.rating !== null;

            if (hasRating) {
                const fullStarsRating = Math.floor(comp.rating);
                const emptyStarsRating = 4 - fullStarsRating;
                const ratingStars = '★'.repeat(fullStarsRating) + '☆'.repeat(emptyStarsRating);
                const comment = comp.comment || 'No comment provided';
                const assessedDate = comp.assessed_at ? new Date(comp.assessed_at).toLocaleDateString() : '';

                html += `
                    <tr class="competency-row-rated">
                        <td class="competency-name">${escapeHtml(comp.competency_name || 'Not specified')}</td>
                        <td style="text-align:center;">
                            <span class="rating-stars">${ratingStars}</span>
                            <span class="rating-number">(${comp.rating}/4)</span>
                        </td>
                        <td>
                            <div class="teacher-comment">"${escapeHtml(comment)}"</div>
                            ${assessedDate ? `<div class="comment-date">📅 ${assessedDate}</div>` : ''}
                        </td>
                        <td style="text-align:center;">
                            <span class="status-rated">✅ Rated</span>
                        </td>
                    </tr>
                `;
            } else {
                html += `
                    <tr class="competency-row-pending">
                        <td class="competency-name">${escapeHtml(comp.competency_name || 'Not specified')}</td>
                        <td style="text-align:center;">
                            <span class="rating-stars-empty">☆☆☆☆</span>
                            <span class="rating-number">(Not rated)</span>
                        </td>
                        <td><span class="teacher-comment">—</span></td>
                        <td style="text-align:center;">
                            <span class="status-pending">⏳ Pending</span>
                        </td>
                    </tr>
                `;
            }
        }

        html += `
                            </tbody>
                        </table>
                        ${activity.rated_competencies < activity.total_competencies ?
                            '<div class="warning-message">⚠️ Some competencies have not been rated yet. The activity average only includes rated competencies.</div>' :
                            '<div class="success-message">✅ All competencies have been rated for this activity!</div>'}
                    </div>
                </div>
            </div>
        `;
    }

    container.innerHTML = html;
}

// Add escapeHtml function if not present
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

    // ==================== MESSAGES ====================
async function displayMessages() {
    const messages = await fetchAPI('/api/parent/messages');
    const container = document.getElementById('messagesContainer');

    if (messages.length === 0) {
        container.innerHTML = '<p style="text-align:center;padding:40px;">No messages yet.</p>';
        return;
    }

    // Group messages by conversation partner
    const conversations = {};
    messages.forEach(m => {
        const partnerId = m.direction === 'received' ? m.from_id : m.to_id;
        const partnerName = m.direction === 'received' ? m.from : m.to;
        const partnerType = m.direction === 'received' ? m.from_type : m.to_type;

        if (!conversations[partnerId]) {
            conversations[partnerId] = {
                id: partnerId,
                name: partnerName,
                type: partnerType,
                lastMessage: m.message,
                lastDate: m.date,
                lastTimestamp: new Date(m.date).getTime(),  // ← ADD for sorting
                unreadCount: (m.direction === 'received' && !m.is_read) ? 1 : 0
            };
        } else {
            if (m.direction === 'received' && !m.is_read) {
                conversations[partnerId].unreadCount++;
            }
            // Keep the most recent message
            const currentTimestamp = new Date(m.date).getTime();
            if (currentTimestamp > conversations[partnerId].lastTimestamp) {
                conversations[partnerId].lastMessage = m.message;
                conversations[partnerId].lastDate = m.date;
                conversations[partnerId].lastTimestamp = currentTimestamp;
            }
        }
    });

    // ← SORT BY LATEST MESSAGE (NEWEST FIRST)
    const sortedConversations = Object.values(conversations).sort((a, b) => {
        return b.lastTimestamp - a.lastTimestamp;
    });

    let html = '<div style="display:flex; flex-direction:column; gap:8px;">';

    sortedConversations.forEach(conv => {
        let icon = conv.type === 'teacher' ? '👩‍🏫' : '📋';
        let unreadBadge = conv.unreadCount > 0 ? `<span style="background:#FF6B35; color:white; border-radius:12px; padding:2px 8px; font-size:11px; margin-left:8px;">${conv.unreadCount}</span>` : '';

        const msgDate = new Date(conv.lastDate);
        const today = new Date();
        let dateDisplay = '';

        if (msgDate.toDateString() === today.toDateString()) {
            dateDisplay = msgDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else if (msgDate.toDateString() === new Date(today.setDate(today.getDate() - 1)).toDateString()) {
            dateDisplay = 'Yesterday';
        } else {
            dateDisplay = msgDate.toLocaleDateString([], { month: 'short', day: 'numeric' });
        }

        html += `
            <div onclick="openConversation('${conv.id}', '${conv.name}', '${conv.type}')" style="display:flex; align-items:center; gap:15px; background:white; border-radius:12px; padding:12px 16px; cursor:pointer; transition:background 0.2s; border:1px solid #e0e0e0;">
                <div style="width:50px; height:50px; background:${conv.type === 'teacher' ? '#FF6B35' : '#1E3A5F'}; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
                    ${icon}
                </div>
                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:baseline;">
                        <strong style="font-size:16px;">${conv.name}</strong>
                        <span style="font-size:11px; color:#888;">${dateDisplay}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:4px;">
                        <span style="font-size:13px; color:#666; ${conv.unreadCount > 0 ? 'font-weight:600; color:#333;' : ''}">${conv.lastMessage.substring(0, 35)}${conv.lastMessage.length > 35 ? '...' : ''}</span>
                        ${unreadBadge}
                    </div>
                </div>
                <div style="color:#ccc;">▶</div>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
    await updateTotalUnreadCount();
}

let currentConversationPartnerId = null;
let currentConversationPartnerName = null;
let currentConversationPartnerType = null;

async function openConversation(partnerId, partnerName, partnerType) {
    currentConversationPartnerId = partnerId;
    currentConversationPartnerName = partnerName;
    currentConversationPartnerType = partnerType;

    document.getElementById('conversationTitle').innerHTML = `💬 Conversation with ${partnerName}`;
    document.getElementById('conversationModal').style.display = 'flex';
    document.getElementById('replyMessage').value = '';

    // Mark messages as read
    await fetch(`/api/parent/mark-as-read/${partnerId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    await loadConversation(partnerId);
    displayMessages(); // This will refresh the sidebar and remove notification badge
    await updateTotalUnreadCount();
}

async function loadConversation(participantId) {
    const container = document.getElementById('conversationMessages');
    container.innerHTML = '<div style="text-align:center; padding:20px;">Loading messages...</div>';
    const messages = await fetchAPI(`/api/parent/conversation/${participantId}`);

    if (messages.length === 0) {
        container.innerHTML = '<div style="text-align:center; padding:20px;">No messages yet. Start the conversation!</div>';
        return;
    }

    let html = '';
    let lastDate = '';

    messages.forEach(msg => {
        const isParent = msg.sender_type === 'parent';

        const msgDate = new Date(msg.date);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        let dateSeparator = '';
        let dateDisplay = '';

        const currentDateKey = msgDate.toDateString();
        if (currentDateKey !== lastDate) {
            lastDate = currentDateKey;
            if (currentDateKey === today.toDateString()) {
                dateSeparator = 'Today';
            } else if (currentDateKey === yesterday.toDateString()) {
                dateSeparator = 'Yesterday';
            } else {
                dateSeparator = msgDate.toLocaleDateString([], { weekday: 'long', month: 'long', day: 'numeric' });
            }
            html += `<div style="text-align:center; margin:15px 0;"><span style="background:#e0e0e0; padding:4px 12px; border-radius:12px; font-size:12px; color:#666;">${dateSeparator}</span></div>`;
        }

        dateDisplay = msgDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        if (isParent) {
            html += `<div class="parent-message">
                        <div class="bubble">
                            <div style="font-size:12px; font-weight:500;">${msg.sender}</div>
                            <div style="font-size:14px;">${msg.message}</div>
                            <div style="font-size:10px; opacity:0.6; margin-top:4px;">${dateDisplay}</div>
                        </div>
                    </div>
                    <div class="parent-delete">
                        <button onclick="deleteParentMsg(${msg.id})">x</button>
                    </div>`;
        } else {
            html += `<div class="other-message">
                        <div class="bubble">
                            <div style="font-size:12px; font-weight:500;">${msg.sender}</div>
                            <div style="font-size:14px;">${msg.message}</div>
                            <div style="font-size:10px; opacity:0.6; margin-top:4px;">${dateDisplay}</div>
                        </div>
                    </div>`;
        }
    });

    container.innerHTML = html;
    container.scrollTop = container.scrollHeight;
}
async function deleteParentMsg(messageId) {
    if (!confirm('Delete this message?')) return;

    const response = await fetch(`/api/parent/messages/${messageId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    if (response.ok) {
        alert('✅ Message deleted');
        if (currentConversationPartnerId) {
            await loadConversation(currentConversationPartnerId);
        }
        displayMessages();
    } else {
        alert('❌ Error deleting message');
    }
}

function closeConversationModal() {
    document.getElementById('conversationModal').style.display = 'none';
    currentConversationPartnerId = null;
    let currentConversationPartnerName = null;
    let currentConversationPartnerType = null;
}

async function sendReply() {
    const replyText = document.getElementById('replyMessage').value;

    if (!replyText) {
        alert('Please enter a reply message');
        return;
    }

    // Determine the receiver type
    let receiverType = 'teacher';
    if (currentConversationPartnerType === 'coordinator') {
        receiverType = 'coordinator';
    }

    const response = await fetch('/api/parent/reply-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: currentConversationPartnerId,
            receiver_type: receiverType,
            subject: 'Re: Conversation',
            message: replyText
        })
    });

    const data = await response.json();

    if (data.success) {
        //alert('✅ Reply sent successfully!');
        document.getElementById('replyMessage').value = '';
        await loadConversation(currentConversationPartnerId);
        displayMessages();
    } else {
        alert('❌ Error: ' + data.message);
    }
}

function viewFullMessage(senderOrTo, subject, message, date, type, messageId) {
    // Decode the escaped characters
    const decode = (str) => {
        if (!str) return '';
        return str.replace(/\\'/g, "'")
                  .replace(/\\"/g, '"')
                  .replace(/\\\\/g, '\\')
                  .replace(/\\n/g, '\n')
                  .replace(/\\r/g, '');
    };

    const decodedSender = decode(senderOrTo);
    const decodedSubject = decode(subject);
    const decodedMessage = decode(message);
    const decodedDate = decode(date);

    const title = type === 'received' ? `📩 Message from ${decodedSender}` : `📤 Message to ${decodedSender}`;

    // Show the message
    alert(`${title}\n\nSubject: ${decodedSubject}\nDate: ${decodedDate}\n\nMessage:\n${decodedMessage}`);

    // If it's a received message, mark as read
    if (type === 'received' && messageId) {
        fetch(`/api/parent/mark-as-read/${messageId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            displayMessages();
        });
    }
}

function closeFullMessageModal() {
    const modal = document.getElementById('fullMessageModal');
    if (modal) modal.remove();
}


    async function sendParentMessage(event) {
    if (event) event.preventDefault();
    const select = document.getElementById('messageTo');
    const receiverId = select.value;
    const selectedOption = select.options[select.selectedIndex];
    const receiverType = selectedOption?.getAttribute('data-type');
    const subject = document.getElementById('messageSubject').value;
    const message = document.getElementById('messageBody').value;

    if (!receiverId) {
        alert('Please select a recipient');
        return;
    }
    if (!message) {
        alert('Please enter a message');
        return;
    }

    const response = await fetch('/api/parent/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: receiverId,
            receiver_type: receiverType,
            subject: subject,
            message: message
        })
    });

    const data = await response.json();
    if (data.success) {
        //alert('✅ Message sent successfully!');
        closeMessageModal();
        displayMessages();
    } else {
        alert('❌ Error: ' + data.message);
    }
}

// ==================== NEW MESSAGE (WHATSAPP STYLE) ====================

let selectedRecipient = null;
let allRecipients = [];

async function loadAllRecipients() {
    const recipients = await fetchAPI('/api/parent/recipients');
    allRecipients = recipients;
}

function openNewMessageModal() {
    selectedRecipient = null;
    document.getElementById('recipientSearch').value = '';
    document.getElementById('messageInputSection').style.display = 'none';
    document.getElementById('newMessageText').value = '';
    document.getElementById('recipientSearchResults').innerHTML = '';
    document.getElementById('recipientSearchResults').classList.remove('show');
    document.getElementById('newMessageModal').style.display = 'flex';
    document.getElementById('recipientSearch').focus();
    // Show X button
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'flex';
}

function closeNewMessageModal() {
    document.getElementById('newMessageModal').style.display = 'none';
    document.getElementById('messageInputSection').style.display = 'none';
    document.getElementById('recipientSearch').value = '';
    document.getElementById('recipientSearchResults').innerHTML = '';
    document.getElementById('recipientSearchResults').classList.remove('show');

    // Show X button for next time
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'flex';
}

function searchRecipients() {
    const searchTerm = document.getElementById('recipientSearch').value.toLowerCase().trim();
    const resultsDiv = document.getElementById('recipientSearchResults');

    if (searchTerm.length === 0) {
        resultsDiv.classList.remove('show');
        return;
    }

    // Make sure allRecipients is loaded
    if (!allRecipients || allRecipients.length === 0) {
        resultsDiv.innerHTML = '<div style="padding:10px 15px; color:gray;">Loading users...</div>';
        resultsDiv.classList.add('show');
        return;
    }

    const filtered = allRecipients.filter(r =>
        r.name.toLowerCase().includes(searchTerm)
    );

    if (filtered.length === 0) {
        resultsDiv.innerHTML = '<div style="padding:10px 15px; color:gray;">No users found</div>';
        resultsDiv.classList.add('show');
        return;
    }

    let html = '';
    filtered.forEach(r => {
        let icon = r.type === 'teacher' ? '👩‍🏫' : '📋';
        html += `
            <div class="recipient-search-item" onclick="selectRecipient('${r.id}', '${r.name}', '${r.type}')">
                <div class="avatar">${icon}</div>
                <div class="info">
                    <div class="name">${r.name}</div>
                    <div class="type">${r.type}</div>
                </div>
            </div>
        `;
    });

    resultsDiv.innerHTML = html;
    resultsDiv.classList.add('show');
}

function selectRecipient(id, name, type) {
    selectedRecipient = { id, name, type };
    document.getElementById('recipientSearch').value = name;
    document.getElementById('recipientSearchResults').classList.remove('show');
    document.getElementById('messageInputSection').style.display = 'block';
    document.getElementById('newMessageText').focus();
    // HIDE X button (Cancel button handles closing now)
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'none';
}

async function sendNewMessage() {
    const message = document.getElementById('newMessageText').value.trim();

    if (!selectedRecipient) {
        alert('Please select a recipient');
        return;
    }
    if (!message) {
        alert('Please enter a message');
        return;
    }

    const response = await fetch('/api/parent/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: selectedRecipient.id,
            receiver_type: selectedRecipient.type,
            message: message,
            subject: null
        })
    });

    const data = await response.json();
    if (data.success) {
        alert('✅ Message sent successfully!');
        closeNewMessageModal();
        displayMessages();
        // Open conversation with the recipient
        openConversation(selectedRecipient.id, selectedRecipient.name, selectedRecipient.type);
    } else {
        alert('❌ Error: ' + data.message);
    }
}

// Add event listener for search input
document.getElementById('recipientSearch')?.addEventListener('input', searchRecipients);
document.getElementById('newMessageBtn')?.addEventListener('click', openNewMessageModal);

async function deleteMessage(messageId) {
    if (!confirm('Delete this message? It will be hidden from your view.')) return;

    // Determine which API endpoint to use based on dashboard
    let url = '';
    if (window.location.pathname.includes('coordinator')) {
        url = `/api/messages/${messageId}`;
    } else if (window.location.pathname.includes('teacher')) {
        url = `/api/teacher/messages/${messageId}`;
    } else {
        url = `/api/parent/messages/${messageId}`;
    }

    const response = await fetch(url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    const data = await response.json();
    if (data.success) {
        alert('✅ Message deleted');
        // Refresh the current conversation
        if (currentConversationPartnerId) {
            await loadConversation(currentConversationPartnerId);
        }
    } else {
        alert('❌ Error: ' + (data.message || 'Could not delete message'));
    }
}

async function updateTotalUnreadCount() {
    try {
        const response = await fetch('/api/parent/unread-count', {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        const data = await response.json();
        const badge = document.getElementById('messagesUnreadBadge');
        if (data.count > 0) {
            badge.innerText = data.count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error('Error fetching unread count:', error);
    }
}
    // ==================== SIDEBAR NAVIGATION ====================

    const sidebarItems = document.querySelectorAll('.sidebar nav li');
    const pages = {
        dashboard: document.getElementById('dashboardPage'),
        mychild: document.getElementById('mychildPage'),
        activities: document.getElementById('activitiesPage'),
        messages: document.getElementById('messagesPage'),
        settings: document.getElementById('settingsPage')
    };

    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            const pageName = item.getAttribute('data-page');
            sidebarItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            Object.values(pages).forEach(p => p?.classList.remove('active'));
            if (pages[pageName]) pages[pageName].classList.add('active');
            if (pageName === 'mychild') displayChildProfileFull();
            if (pageName === 'activities') displayAllActivities();
            if (pageName === 'messages') displayMessages();
        });
    });

    // ==================== MODAL FUNCTIONS ====================

    let parentRecipientsList = [];

async function loadParentRecipients() {
    const recipients = await fetchAPI('/api/parent/recipients');
    parentRecipientsList = recipients;

    const select = document.getElementById('messageTo');
    select.innerHTML = '<option value="">-- Select Recipient --</option>';

    recipients.forEach(r => {
        let icon = r.type === 'teacher' ? '👩‍🏫' : (r.type === 'coordinator' ? '📋' : '👥');
        select.innerHTML += `<option value="${r.id}" data-type="${r.type}">${icon} ${r.name} (${r.type})</option>`;
    });
}

function openParentMessageModal() {
    loadParentRecipients();
    document.getElementById('messageSubject').value = '';
    document.getElementById('messageBody').value = '';
    document.getElementById('messageModal').style.display = 'flex';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}
    // ==================== SETTINGS BUTTONS ====================

    //document.getElementById('newMessageBtn')?.addEventListener('click', () => openParentMessageModal());
    document.getElementById('notificationsBtn')?.addEventListener('click', () => alert('🔔 Notification preferences saved!'));

    document.getElementById('darkModeBtn')?.addEventListener('click', () => {
        darkMode = !darkMode;
        localStorage.setItem('parent_theme', darkMode ? 'dark' : 'light');
        applyDarkMode();
    });

    document.getElementById('privacyBtn')?.addEventListener('click', () => document.getElementById('privacyModal').style.display = 'flex');
    document.getElementById('changePasswordBtn')?.addEventListener('click', () => document.getElementById('passwordModal').style.display = 'flex');

    // Dark mode
    let darkMode = localStorage.getItem('parent_theme') === 'dark';
    function applyDarkMode() {
        document.body.classList.toggle('dark-mode', darkMode);
        const btn = document.getElementById('darkModeBtn');
        if (btn) btn.innerHTML = darkMode ? 'Dark Mode 🌙' : 'Light Mode ☀️';
    }
    applyDarkMode();

    function openPasswordModal() {
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    document.getElementById('passwordModal').style.display = 'flex';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

async function changePassword() {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Validation
    if (!currentPassword) {
        alert('Please enter your current password');
        return;
    }
    if (!newPassword) {
        alert('Please enter a new password');
        return;
    }
    if (newPassword.length < 6) {
        alert('New password must be at least 6 characters');
        return;
    }
    if (newPassword !== confirmPassword) {
        alert('New passwords do not match');
        return;
    }

    try {
        const response = await fetch('/api/parent/change-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmPassword
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('✅ ' + data.message);
            closePasswordModal();
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        } else {
            alert('❌ ' + data.message);
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        }
    } catch (error) {
        alert('❌ Error changing password. Please try again.');
    }
}

// Event listener
document.getElementById('changePasswordBtn')?.addEventListener('click', () => {
    openPasswordModal();
});

    // Initial load
    loadChildData();
    displayMessages();
    loadAllRecipients();
    updateTotalUnreadCount();
</script>
</body>
</html>
