<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Dashboard - Kinderbot</title>
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
            font-size: 14px;
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
            transition: background 0.3s ease;
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
            margin: 30px 0 20px 0;
        }
        .section-header h2 { font-size: 22px; color: var(--dark-blue); margin: 0; }
        .btn-primary {
            background: var(--orange);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        .btn-primary:hover { opacity: 0.9; }
        .btn-small {
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 6px;
            border: 1px solid var(--border-gray);
            background: white;
            cursor: pointer;
        }
        .btn-small:hover { background: var(--light-gray); }
        .teacher-greeting { margin-bottom: 30px; }
        .teacher-greeting h1 { font-size: 28px; color: var(--dark-blue); margin-bottom: 8px; }
        .teacher-greeting .date { color: var(--gray); font-size: 14px; }
        .classes-grid { display: flex; gap: 24px; flex-wrap: wrap; }
        .class-card {
            background: var(--white);
            border: 1px solid var(--border-gray);
            border-radius: 16px;
            padding: 20px;
            width: 280px;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .class-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .class-card h3 { font-size: 20px; color: var(--dark-blue); margin-bottom: 12px; }
        .class-stats p { margin: 8px 0; color: var(--gray); font-size: 14px; }
        .next-activity {
            background: #FFF4E5;
            padding: 8px 12px;
            border-radius: 8px;
            margin: 12px 0;
            font-size: 13px;
            color: var(--orange);
        }
        .btn-view {
            background: transparent;
            border: 1px solid var(--orange);
            color: var(--orange);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
            margin-top: 8px;
        }
        .btn-view:hover { background: var(--orange); color: white; }
        .activities-list { display: flex; flex-direction: column; gap: 12px; }
        .activity-item {
            background: var(--white);
            border: 1px solid var(--border-gray);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .activity-info h4 { font-size: 18px; color: var(--dark-blue); margin-bottom: 6px; }
        .activity-meta { display: flex; gap: 16px; font-size: 13px; color: var(--gray); }
        .btn-start {
            background: var(--orange);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        .btn-start:hover { opacity: 0.9; }
        .assessments-table {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border-gray);
            overflow: hidden;
        }
        .assessments-table table { width: 100%; border-collapse: collapse; }
        .assessments-table th {
            background: var(--light-gray);
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--dark-blue);
        }
        .assessments-table td { padding: 12px 16px; border-bottom: 1px solid var(--border-gray); }
        .rating { color: #FFB81C; font-size: 16px; letter-spacing: 2px; }
        .btn-edit {
            background: transparent;
            border: 1px solid var(--border-gray);
            color: var(--gray);
            padding: 6px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        .data-table {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border-gray);
            overflow: hidden;
            width: 100%;
        }
        .data-table table { width: 100%; border-collapse: collapse; }
        .data-table th { background: var(--light-gray); padding: 12px 16px; text-align: left; font-weight: 600; }
        .data-table td { padding: 10px 16px; border-bottom: 1px solid var(--border-gray); }
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
            padding: 24px;
            border-radius: 16px;
            width: 500px;
            max-width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-content h3 { margin-bottom: 20px; color: var(--dark-blue); }
        .modal-content select, .modal-content input, .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid var(--border-gray);
            border-radius: 8px;
        }
        .star-rating { display: flex; gap: 4px; font-size: 20px; cursor: pointer; margin: 10px 0; }
        .star { color: #ddd; transition: color 0.2s; cursor: pointer; }
        .star.selected { color: #FFB81C; }
        .modal-buttons { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; }
        .btn-cancel { background: #f0f0f0; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; }
        .btn-save { background: var(--orange); color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; }
        .class-detail { margin-bottom: 16px; padding: 12px; background: var(--light-gray); border-radius: 8px; }
        .student-list { max-height: 300px; overflow-y: auto; }
        .student-item {
            padding: 12px;
            border-bottom: 1px solid var(--border-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .student-item:last-child { border-bottom: none; }
        .loading { text-align: center; padding: 40px; color: var(--gray); }
        body.dark-mode { background: #1a1a2e; }
        body.dark-mode .sidebar { background: #16213e; border-right-color: #2a2a4a; }
        body.dark-mode .sidebar nav li { color: #aaa; }
        body.dark-mode .sidebar nav li:hover { background: #1f2a4a; color: white; }
        body.dark-mode .content { background: #1a1a2e; }
        body.dark-mode .class-card, body.dark-mode .activity-item, body.dark-mode .data-table, body.dark-mode .modal-content,
        body.dark-mode .settings-item { background: #16213e; border-color: #2a2a4a; color: #ddd; }
        body.dark-mode .class-card h3, body.dark-mode .activity-info h4, body.dark-mode .teacher-greeting h1,
        body.dark-mode .section-header h2 { color: var(--orange); }
        body.dark-mode .class-stats p, body.dark-mode .activity-meta { color: #aaa; }
        body.dark-mode th { background: #0f0f2a; color: white; }
        body.dark-mode td { border-bottom-color: #2a2a4a; color: #ccc; }
        body.dark-mode .btn-view { border-color: var(--orange); color: var(--orange); }
        body.dark-mode .btn-view:hover { background: var(--orange); color: white; }
        body.dark-mode .next-activity { background: #2a2a4a; color: var(--orange); }
        /* Conversation list hover effect */
#messagesContainer div[onclick]:hover {
    background: #f5f5f5 !important;
    /*transform: translateX(2px)  !important;*/
    transition: all 0.2s;
}

body.dark-mode #messagesContainer div[onclick]:hover {
    background: #2a2a4a !important;
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

/* Prevent browser password manager */
#newMessageModal input,
#newMessageModal textarea {
    transition: background-color 5000s ease-in-out 0s;
}

#conversationMessages button {
    transition: transform 0.2s;
}

#conversationMessages button:hover {
    transform: scale(1.1);
}

.teacher-message {
    text-align: right;
    margin-bottom: 5px;
}
.teacher-message .bubble {
    display: inline-block;
    max-width: 70%;
    background: #FF6B35;
    color: white;
    padding: 8px 12px;
    border-radius: 18px;
    text-align: left;
}
.teacher-delete {
    text-align: right;
    margin-bottom: 15px;
}
.teacher-delete button {
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="top-bar">
            <div class="logo">👩‍🏫 Kinderbot Teacher</div>
            <div class="user-info">
                <span id="teacherName">👤 Teacher</span>
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
                        <li data-page="myclasses">📚 My Classes</li>
                        <li data-page="activities">📝 Activities</li>
                        <li data-page="reports">📊 Reports</li>
                        <li data-page="messages">💬 Messages
                            <span id="messagesUnreadBadge" style="background:#dc3545; color:white; border-radius:10px; padding:2px 6px; font-size:10px; margin-left:5px; display:none;">0</span>
                        </li>
                        <li data-page="settings">⚙️ Settings</li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <div id="dashboardPage" class="page-content active">
                    <div class="teacher-greeting"><h1 id="greetingMessage">Welcome back! 👋</h1><p class="date" id="currentDate"></p></div>
                    <div class="section-header"><h2>My Classes</h2></div>
                    <div id="classesGrid" class="classes-grid"><div class="loading">Loading classes...</div></div>
                    <div class="section-header"><h2>Today's Activities</h2></div>
                    <div id="todayActivities" class="activities-list"><div class="loading">Loading activities...</div></div>
                    <div class="section-header"><h2>Recent Assessments</h2><button class="btn-primary" id="quickAssessBtn">+ Quick Assessment</button></div>
                    <div id="assessmentsTable" class="assessments-table"><div class="loading">Loading assessments...</div></div>
                </div>
                <div id="myclassesPage" class="page-content"><div class="section-header"><h2>My Classes</h2></div><div id="myClassesTable" class="data-table"><div class="loading">Loading...</div></div></div>
                <div id="activitiesPage" class="page-content"><div class="section-header"><h2>All Activities</h2></div><div id="teacherActivitiesTable" class="data-table"><div class="loading">Loading...</div></div></div>
                <div id="reportsPage" class="page-content"><div class="section-header"><h2>Student Progress Reports</h2><button class="btn-primary" id="exportReportBtn">📊 Export Report</button></div><div id="reportsContainer" class="data-table"><div class="loading">Loading...</div></div></div>
                <div id="messagesPage" class="page-content"><div class="section-header"><h2>Messages</h2><button class="btn-primary" id="newMessageBtn">+ New Chat</button></div><div id="messagesContainer" class="data-table"><div class="loading">Loading...</div></div></div>
                <div id="settingsPage" class="page-content">
                    <div class="section-header"><h2>Settings</h2></div>
                    <div class="data-table">
                        <div class="settings-item"><div><strong>🌙 Dark Mode</strong><div style="font-size:13px;">Switch theme preference</div></div><div><button class="btn-small" id="darkModeBtn">Light Mode</button></div></div>
                        <div class="settings-item"><div><strong>🔑 Change password</strong><div style="font-size:13px;">Update your account password</div></div><div><button class="btn-small" id="editAccountBtn">Change</button></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="classDetailsModal" class="modal"><div class="modal-content"><h3 id="classDetailsTitle">Class Details</h3><div id="classDetailsContent"></div><div class="modal-buttons"><button class="btn-cancel" onclick="closeClassModal()">Close</button></div></div></div>
    <div id="assessmentModal" class="modal"><div class="modal-content"><h3>Quick Assessment</h3><label>Student:</label><select id="modalStudent"><option value="">Select Student</option></select><label>Activity:</label><select id="modalActivity"><option value="">Select Activity</option></select><label>Rating:</label><div class="star-rating" id="starRating"><span class="star" data-value="1">★</span><span class="star" data-value="2">★</span><span class="star" data-value="3">★</span><span class="star" data-value="4">★</span><span class="star" data-value="5">★</span></div><label>Comments:</label><textarea id="modalComments" rows="3"></textarea><div class="modal-buttons"><button class="btn-cancel" onclick="closeAssessmentModal()">Cancel</button><button class="btn-save" onclick="saveAssessment()">Save</button></div></div></div>


<!-- New Chat Modal -->
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
                <button class="btn-primary" onclick="sendNewTeacherMessage()">Send</button>
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

 <!--Conversation Modal-->
<div id="conversationModal" class="modal">
    <div class="modal-content" style="max-width:600px; width:35% max-height:80vh; overflow-y:auto;">
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
        // Set current date
        document.getElementById('currentDate').innerHTML = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        // Get teacher name from Laravel
        const teacherName = @json($teacherName ?? 'Teacher');
        document.getElementById('teacherName').innerHTML = `👤 ${teacherName}`;
        document.getElementById('greetingMessage').innerHTML = `Welcome back, ${teacherName}! 👋`;

        let currentClassData = null;
        let selectedRating = 0;

        // ==================== API CALLS ====================

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

        async function postAPI(url, data) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                return await response.json();
            } catch (error) {
                console.error('API Error:', error);
                return { success: false };
            }
        }

        // ==================== DASHBOARD FUNCTIONS ====================

        async function loadClasses() {
            const container = document.getElementById('classesGrid');
            const data = await fetchAPI('/api/teacher/classes');

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No classes assigned yet.</p>';
                return;
            }

            container.innerHTML = data.map(cls => `
                <div class="class-card" onclick="viewClassDetails(${JSON.stringify(cls).replace(/"/g, '&quot;')})">
                    <h3>${cls.name}</h3>
                    <div class="class-stats"><p>👥 ${cls.students} students</p><p>🕒 ${cls.time || 'Schedule TBD'}</p></div>
                    <div class="next-activity">Next: ${cls.nextActivity || 'No activities'}</div>
                    <button class="btn-view" onclick="event.stopPropagation(); viewClassDetails(${JSON.stringify(cls).replace(/"/g, '&quot;')})">View Class</button>
                </div>
            `).join('');
        }

        async function loadTodayActivities() {
            const container = document.getElementById('todayActivities');
            const data = await fetchAPI('/api/teacher/today-activities');

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No activities scheduled for today.</p>';
                return;
            }

            container.innerHTML = data.map(a => `
                <div class="activity-item">
                    <div class="activity-info">
                        <h4>${a.title}</h4>
                        <div class="activity-meta"><span>📚 ${a.class}</span><span>⏱️ ${a.duration}</span></div>
                    </div>
                    <button class="btn-start" onclick="startActivity('${a.title}', '${a.class}')">Start Activity</button>
                </div>
            `).join('');
        }

        async function loadAssessments() {
            const container = document.getElementById('assessmentsTable');
            const data = await fetchAPI('/api/teacher/assessments');

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No assessments yet.</p>';
                return;
            }

            let html = '<table><thead><tr><th>Student</th><th>Activity</th><th>Rating</th><th>Date</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(a => {
                html += `<tr>
                    <td><strong>${a.student}</strong></td>
                    <td>${a.activity}</td>
                    <td class="rating">${'★'.repeat(a.rating)}${'☆'.repeat(5-a.rating)}</td>
                    <td>${a.date}</td>
                    <td><button class="btn-edit" onclick="editAssessment(${a.id})">Edit</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // ==================== PAGE LOADERS ====================

        async function displayMyClasses() {
            const container = document.getElementById('myClassesTable');
            const data = await fetchAPI('/api/teacher/classes');

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No classes assigned.</p>';
                return;
            }

            let html = '<table><thead><tr><th>Class</th><th>Students</th><th>Schedule</th><th>Next Activity</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(cls => {
                html += `<tr>
                    <td><strong>${cls.name}</strong></td>
                    <td>${cls.students}</td>
                    <td>${cls.time || 'TBD'}</td>
                    <td>${cls.nextActivity || 'None'}</td>
                    <td><button class="btn-small" onclick="viewClassDetails(${JSON.stringify(cls).replace(/"/g, '&quot;')})">View</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        async function displayTeacherActivities() {
            const container = document.getElementById('teacherActivitiesTable');
            const data = await fetchAPI('/api/teacher/all-activities');

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No activities available.</p>';
                return;
            }

            let html = '<table><thead><tr><th>Activity</th><th>Class</th><th>Duration</th><th>Difficulty</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(a => {
                html += `<tr>
                    <td><strong>${a.title}</strong></td>
                    <td>${a.class}</td>
                    <td>${a.duration} min</td>
                    <td>${a.difficulty}</td>
                    <td><button class="btn-small" onclick="startActivity('${a.title}', '${a.class}')">Start</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        async function displayReports() {
            const container = document.getElementById('reportsContainer');
            const data = await fetchAPI('/api/teacher/assessments');

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No assessment data yet.</p>';
                return;
            }

            let html = '<table><thead><tr><th>Student</th><th>Activity</th><th>Rating</th><th>Comments</th><th>Date</th></tr></thead><tbody>';
            data.forEach(a => {
                html += `<tr>
                    <td><strong>${a.student}</strong></td>
                    <td>${a.activity}</td>
                    <td>${'★'.repeat(a.rating)}</td>
                    <td>${a.comments || '-'}</td>
                    <td>${a.date}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }

async function displayMessages() {
    const messages = await fetchAPI('/api/teacher/messages');
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
        let icon = conv.type === 'parent' ? '👨‍👩‍👧' : (conv.type === 'coordinator' ? '📋' : '👥');
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
                <div style="width:50px; height:50px; background:${conv.type === 'parent' ? '#FF6B35' : '#1E3A5F'}; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; color:white;">
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

async function openConversation(partnerId, partnerName, partnerType) {
    currentConversationPartnerId = partnerId;
    currentConversationPartnerName = partnerName;

    document.getElementById('conversationTitle').innerHTML = `💬 Conversation with ${partnerName}`;
    document.getElementById('conversationModal').style.display = 'flex';
    document.getElementById('replyMessage').value = '';

    // Mark messages as read
    await fetch(`/api/teacher/mark-as-read/${partnerId}`, {
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
    const messages = await fetchAPI(`/api/teacher/conversation/${participantId}`);

    if (messages.length === 0) {
        container.innerHTML = '<div style="text-align:center; padding:20px;">No messages yet. Start the conversation!</div>';
        return;
    }

    let html = '';
    let lastDate = '';

    messages.forEach(msg => {
        const isTeacher = msg.sender_type === 'teacher';

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

        if (isTeacher) {
            html += `<div class="teacher-message">
                        <div class="bubble">
                            <div style="font-size:12px; font-weight:500;">${msg.sender}</div>
                            <div style="font-size:14px;">${msg.message}</div>
                            <div style="font-size:10px; opacity:0.6; margin-top:4px;">${dateDisplay}</div>
                        </div>
                    </div>
                    <div class="teacher-delete">
                        <button onclick="deleteTeacherMsg(${msg.id})">x</button>
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
async function deleteTeacherMsg(messageId) {
    if (!confirm('Delete this message?')) return;

    const response = await fetch(`/api/teacher/messages/${messageId}`, {
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
}

async function sendReply() {
    const replyText = document.getElementById('replyMessage').value;

    if (!replyText) {
        alert('Please enter a reply message');
        return;
    }

    const response = await fetch('/api/teacher/reply-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: currentConversationPartnerId,
            subject: 'Re: Conversation',
            message: replyText
        })
    });

    const data = await response.json();

    if (data.success) {
        alert('✅ Reply sent successfully!');
        document.getElementById('replyMessage').value = '';
        await loadConversation(currentConversationPartnerId);
        displayMessages();
    } else {
        alert('❌ Error: ' + data.message);
    }
}

        // ==================== MODAL FUNCTIONS ====================

        function viewClassDetails(classData) {
            currentClassData = classData;
            document.getElementById('classDetailsTitle').innerHTML = `${classData.name} - Class Details`;

            let studentsHtml = '<div class="student-list">';
            if (classData.studentsList && classData.studentsList.length > 0) {
                classData.studentsList.forEach(s => {
                    studentsHtml += `<div class="student-item"><span>👦 ${s}</span><button class="btn-small" onclick="quickAssessStudent('${s}')">Quick Assess</button></div>`;
                });
            } else {
                studentsHtml += '<div class="student-item">No students enrolled</div>';
            }
            studentsHtml += '</div>';

            document.getElementById('classDetailsContent').innerHTML = `
                <div class="class-detail"><strong>👩‍🏫 Teacher:</strong> ${classData.teacher || 'Not assigned'}</div>
                <div class="class-detail"><strong>👥 Students:</strong> ${classData.students} students</div>
                <div class="class-detail"><strong>🕒 Schedule:</strong> ${classData.time || 'Schedule TBD'}</div>
                <div class="class-detail"><strong>📚 Next Activity:</strong> ${classData.nextActivity || 'No activities'}</div>
                <div class="section-header" style="margin-top:20px;"><h3>Student List</h3></div>
                ${studentsHtml}
            `;
            document.getElementById('classDetailsModal').style.display = 'flex';
        }

        function closeClassModal() {
            document.getElementById('classDetailsModal').style.display = 'none';
        }

        function quickAssessStudent(studentName) {
            closeClassModal();
            setTimeout(async () => {
                await loadStudentsAndActivitiesForModal();
                document.getElementById('modalStudent').value = studentName;
                openAssessmentModal();
            }, 200);
        }

        async function loadStudentsAndActivitiesForModal() {
            const students = await fetchAPI('/api/teacher/students-list');
            const activities = await fetchAPI('/api/teacher/activities-list');

            const studentSelect = document.getElementById('modalStudent');
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            students.forEach(s => {
                studentSelect.innerHTML += `<option value="${s.full_name}">${s.full_name}</option>`;
            });

            const activitySelect = document.getElementById('modalActivity');
            activitySelect.innerHTML = '<option value="">Select Activity</option>';
            activities.forEach(a => {
                activitySelect.innerHTML += `<option value="${a.title}">${a.title}</option>`;
            });
        }

        function openAssessmentModal() {
            selectedRating = 0;
            updateStarDisplay(0);
            document.getElementById('assessmentModal').style.display = 'flex';
        }

        function closeAssessmentModal() {
            document.getElementById('assessmentModal').style.display = 'none';
            document.getElementById('modalComments').value = '';
        }

        function updateStarDisplay(rating) {
            document.querySelectorAll('#starRating .star').forEach((star, i) => {
                if (i < rating) star.classList.add('selected');
                else star.classList.remove('selected');
            });
            selectedRating = rating;
        }

        async function saveAssessment() {
            const student = document.getElementById('modalStudent').value;
            const activity = document.getElementById('modalActivity').value;
            const comments = document.getElementById('modalComments').value;

            if (!student || !activity || selectedRating === 0) {
                alert('Please fill all fields and select a rating');
                return;
            }

            const result = await postAPI('/api/teacher/assessment', {
                student: student,
                activity: activity,
                rating: selectedRating,
                comments: comments
            });

            if (result.success) {
                alert('✅ Assessment saved successfully!');
                closeAssessmentModal();
                loadAssessments();
                displayReports();
            } else {
                alert('❌ Error: ' + (result.message || 'Failed to save assessment'));
            }
        }

        function startActivity(activityName, className) {
            alert(`✅ Started "${activityName}" for ${className}\n\nActivity log has been recorded.`);
        }

    let recipientsList = [];

async function loadRecipients() {
    const recipients = await fetchAPI('/api/teacher/recipients');
    recipientsList = recipients;

    const select = document.getElementById('messageTo');
    select.innerHTML = '<option value="">-- Select Recipient --</option>';

    recipients.forEach(r => {
        let icon = r.type === 'parent' ? '👨‍👩‍👧' : (r.type === 'coordinator' ? '📋' : '👥');
        select.innerHTML += `<option value="${r.id}" data-type="${r.type}">${icon} ${r.name} (${r.type})</option>`;
    });
}

function openMessageModal() {
    loadRecipients();
    document.getElementById('messageSubject').value = '';
    document.getElementById('messageBody').value = '';
    document.getElementById('messageModal').style.display = 'flex';
}

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        async function sendMessage() {
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

    const result = await postAPI('/api/teacher/send-message', {
        receiver_id: receiverId,
        receiver_type: receiverType,
        subject: subject,
        message: message
    });

    if (result.success) {
        alert('✅ Message sent successfully!');
        closeMessageModal();
        displayMessages();
    } else {
        alert('❌ Error: ' + result.message);
    }
}
// ==================== NEW CHAT (WHATSAPP STYLE) ====================

let selectedTeacherRecipient = null;
let allTeacherRecipients = [];

async function loadTeacherRecipients() {
    const recipients = await fetchAPI('/api/teacher/recipients');
    allTeacherRecipients = recipients;
    console.log('Teacher recipients loaded:', allTeacherRecipients);
}

function openNewTeacherMessageModal() {
    selectedTeacherRecipient = null;
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

function searchTeacherRecipients() {
    const searchTerm = document.getElementById('recipientSearch').value.toLowerCase().trim();
    const resultsDiv = document.getElementById('recipientSearchResults');

    if (searchTerm.length === 0) {
        resultsDiv.classList.remove('show');
        return;
    }

    if (!allTeacherRecipients || allTeacherRecipients.length === 0) {
        resultsDiv.innerHTML = '<div style="padding:10px 15px; color:gray;">Loading users...</div>';
        resultsDiv.classList.add('show');
        return;
    }

    const filtered = allTeacherRecipients.filter(r =>
        r.name.toLowerCase().includes(searchTerm)
    );

    if (filtered.length === 0) {
        resultsDiv.innerHTML = '<div style="padding:10px 15px; color:gray;">No users found</div>';
        resultsDiv.classList.add('show');
        return;
    }

    let html = '';
    filtered.forEach(r => {
        let icon = r.type === 'parent' ? '👨‍👩‍👧' : '📋';
        html += `
            <div class="recipient-search-item" onclick="selectTeacherRecipient('${r.id}', '${r.name}', '${r.type}')">
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

function selectTeacherRecipient(id, name, type) {
    selectedTeacherRecipient = { id, name, type };
    document.getElementById('recipientSearch').value = name;
    document.getElementById('recipientSearchResults').classList.remove('show');
    document.getElementById('messageInputSection').style.display = 'block';
    document.getElementById('newMessageText').focus();

    // HIDE X button (Cancel button handles closing now)
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'none';
}

async function sendNewTeacherMessage() {
    const message = document.getElementById('newMessageText').value.trim();

    if (!selectedTeacherRecipient) {
        alert('Please select a recipient');
        return;
    }
    if (!message) {
        alert('Please enter a message');
        return;
    }

    const response = await fetch('/api/teacher/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: selectedTeacherRecipient.id,
            receiver_type: selectedTeacherRecipient.type,
            message: message,
            subject: null
        })
    });

    const data = await response.json();
    if (data.success) {
        alert('✅ Message sent successfully!');
        closeNewMessageModal();
        displayMessages();
        openConversation(selectedTeacherRecipient.id, selectedTeacherRecipient.name, selectedTeacherRecipient.type);
    } else {
        alert('❌ Error: ' + data.message);
    }
}

// Update event listeners
document.getElementById('newMessageBtn')?.addEventListener('click', openNewTeacherMessageModal);
document.getElementById('recipientSearch')?.addEventListener('input', searchTeacherRecipients);

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
            await loadCoordinatorConversation(currentConversationPartnerId);
        }
    } else {
        alert('❌ Error: ' + (data.message || 'Could not delete message'));
    }
}

async function updateTotalUnreadCount() {
    try {
        const response = await fetch('/api/teacher/unread-count', {
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
        // ==================== SETUP ====================

        document.querySelectorAll('#starRating .star').forEach(star => {
            star.addEventListener('click', () => updateStarDisplay(parseInt(star.getAttribute('data-value'))));
        });

        // Sidebar navigation
        const sidebarItems = document.querySelectorAll('.sidebar nav li');
        const pages = {
            dashboard: document.getElementById('dashboardPage'),
            myclasses: document.getElementById('myclassesPage'),
            activities: document.getElementById('activitiesPage'),
            reports: document.getElementById('reportsPage'),
            messages: document.getElementById('messagesPage'),
            settings: document.getElementById('settingsPage')
        };

        sidebarItems.forEach(item => {
            item.addEventListener('click', async () => {
                const pageName = item.getAttribute('data-page');
                sidebarItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                Object.values(pages).forEach(p => p?.classList.remove('active'));
                if (pages[pageName]) pages[pageName].classList.add('active');

                if (pageName === 'myclasses') await displayMyClasses();
                if (pageName === 'activities') await displayTeacherActivities();
                if (pageName === 'reports') await displayReports();
                if (pageName === 'messages') await displayMessages();
            });
        });

        // Settings buttons
        document.getElementById('quickAssessBtn')?.addEventListener('click', async () => {
            await loadStudentsAndActivitiesForModal();
            openAssessmentModal();
        });
        document.getElementById('exportReportBtn')?.addEventListener('click', () => alert('📊 Report export feature coming soon!'));
        //document.getElementById('newMessageBtn')?.addEventListener('click', () => openMessageModal());
        document.getElementById('toggleNotificationsBtn')?.addEventListener('click', () => alert('🔔 Notification settings saved!'));
        document.getElementById('viewScheduleBtn')?.addEventListener('click', () => alert('📅 Schedule view coming soon!'));

        // Dark mode
        let darkMode = localStorage.getItem('teacher_theme') === 'dark';
        function applyDarkMode() {
            document.body.classList.toggle('dark-mode', darkMode);
            document.getElementById('darkModeBtn').innerHTML = darkMode ? 'Dark Mode 🌙' : 'Light Mode ☀️';
        }
        applyDarkMode();
        document.getElementById('darkModeBtn')?.addEventListener('click', () => {
            darkMode = !darkMode;
            localStorage.setItem('teacher_theme', darkMode ? 'dark' : 'light');
            applyDarkMode();
        });


    // ==================== PASSWORD CHANGE FUNCTIONS ====================

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
        const response = await fetch('/api/teacher/change-password', {
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

            // Clear the form for next time
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';

            // OPTIONAL: Ask browser to save the NEW password
            // This creates a temporary login form to trigger browser save
            triggerBrowserSavePassword(newPassword);
        } else {
            alert('❌ ' + data.message);
            // Clear only the password fields, keep current password for retry
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        }
    } catch (error) {
        alert('❌ Error changing password. Please try again.');
    }
}

// OPTIONAL: Function to trigger browser password save (only on success)
function triggerBrowserSavePassword(newPassword) {
    // Create a hidden temporary form to trigger browser's password save
    const tempForm = document.createElement('form');
    tempForm.method = 'POST';
    tempForm.action = '/dummy-login';
    tempForm.style.display = 'none';

    const usernameInput = document.createElement('input');
    usernameInput.type = 'text';
    usernameInput.name = 'email';
    usernameInput.value = teacherName;

    const passwordInput = document.createElement('input');
    passwordInput.type = 'password';
    passwordInput.name = 'password';
    passwordInput.value = newPassword;

    tempForm.appendChild(usernameInput);
    tempForm.appendChild(passwordInput);
    document.body.appendChild(tempForm);

    // Submit the form (won't actually go anywhere)
    tempForm.submit();

    // Clean up
    setTimeout(() => tempForm.remove(), 1000);
}

        // Then update the Edit Account button
        document.getElementById('editAccountBtn')?.addEventListener('click', () => {
            openPasswordModal();
        });

        // Initial load
        loadClasses();
        loadTodayActivities();
        loadAssessments();
        loadTeacherRecipients();
        updateTotalUnreadCount();
    </script>
</body>
</html>
