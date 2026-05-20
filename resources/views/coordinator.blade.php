<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Dashboard - Kinderbot</title>
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
            transition: all 0.2s;
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
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .section-header h2 { font-size: 24px; color: var(--dark-blue); margin: 0; }
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
        .btn-outline {
            background: transparent;
            border: 1px solid var(--orange);
            color: var(--orange);
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-small {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: 6px;
            border: 1px solid var(--border-gray);
            background: white;
            cursor: pointer;
        }
        .btn-danger { background: var(--danger); color: white; border: none; }

        .class-cards {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            border: 1px solid var(--border-gray);
            border-radius: 12px;
            padding: 20px;
            width: 260px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover { transform: translateY(-2px); }
        .card h3 { font-size: 18px; color: var(--dark-blue); margin-bottom: 8px; }
        .card .grade { color: var(--orange); font-size: 13px; margin-bottom: 12px; }
        .card .stats, .card .teacher { color: var(--gray); font-size: 13px; margin: 6px 0; }
        .data-table {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-gray);
            overflow-x: auto;
            width: 100%;
            margin-bottom: 30px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-gray); }
        th { background: var(--light-gray); font-weight: 600; color: var(--dark-blue); }
        .badge { padding: 4px 8px; border-radius: 20px; font-size: 12px; background: #f0f0f0; }
        .badge.published { background: #d4edda; color: #155724; }
        .badge.draft { background: #fff3cd; color: #856404; }
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
            width: 550px;
            max-width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 { margin: 0; color: var(--dark-blue); }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--gray); }
        .modal-body { padding: 20px 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 6px; font-size: 14px; }
        .form-input, .form-select { width: 100%; padding: 10px 12px; border: 1px solid var(--border-gray); border-radius: 8px; font-size: 14px; }
        .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; }
        .section-card {
            border: 1px solid var(--border-gray);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .settings-item {
            padding: 16px;
            border-bottom: 1px solid var(--border-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .settings-item:last-child { border-bottom: none; }
        .empty-state { text-align: center; padding: 40px; color: var(--gray); }
        .add-student-btn {
            margin-top: 16px;
            padding: 10px;
            background: var(--light-gray);
            border: 1px dashed var(--border-gray);
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            color: var(--orange);
        }
        .time-badge {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
        }
        .time-badge.just-now { background: #d4edda; color: #155724; }
        .time-badge.recent { background: #fff3cd; color: #856404; }
        .time-badge.old { background: #f8d7da; color: #721c24; }
        body.dark-mode { background: #1a1a2e; }
        body.dark-mode .sidebar { background: #16213e; border-right-color: #2a2a4a; }
        body.dark-mode .sidebar nav li { color: #aaa; }
        body.dark-mode .sidebar nav li:hover { background: #1f2a4a; color: white; }
        body.dark-mode .content { background: #1a1a2e; }
        body.dark-mode .card, body.dark-mode .data-table, body.dark-mode .modal-content, body.dark-mode .settings-item,
        body.dark-mode .section-card { background: #16213e; border-color: #2a2a4a; color: #ddd; }
        body.dark-mode .card h3, body.dark-mode .section-header h2 { color: var(--orange); }
        body.dark-mode .card .stats, body.dark-mode .card .teacher { color: #aaa; }
        body.dark-mode th { background: #0f0f2a; color: white; }
        body.dark-mode td { border-bottom-color: #2a2a4a; color: #ccc; }
        body.dark-mode .btn-outline { border-color: var(--orange); color: var(--orange); }
        body.dark-mode .form-input, body.dark-mode .form-select { background: #0f0f2a; border-color: #2a2a4a; color: white; }

        /* Summary Cards Styles */
.summary-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}
.summary-card {
    background: white;
    border-radius: 16px;
    padding: 20px 24px;
    flex: 1;
    min-width: 180px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid var(--border-gray);
    transition: transform 0.2s, box-shadow 0.2s;
}
.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.summary-card-icon {
    font-size: 40px;
}
.summary-card-info h3 {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark-blue);
    margin: 0;
}
.summary-card-info p {
    font-size: 14px;
    color: var(--gray);
    margin: 5px 0 0 0;
}
body.dark-mode .summary-card {
    background: #16213e;
    border-color: #2a2a4a;
}
body.dark-mode .summary-card-info h3 {
    color: var(--orange);
}

/* Conversation modal - match teacher exactly */
#conversationModal .modal-content {
    padding: 20px;
}

#conversationModal h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-blue);
    margin: 0;
}

#conversationModal .modal-close {
    color: #999;
    font-size: 24px;
    line-height: 1;
}

#conversationModal .modal-close:hover {
    color: #333;
}

#conversationModal .form-group label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

#conversationModal textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border-gray);
    border-radius: 8px;
    font-family: inherit;
    resize: vertical;
}

#conversationModal textarea:focus {
    outline: none;
    border-color: var(--orange);
}

#conversationModal .btn-cancel {
    background: #f0f0f0;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

#conversationModal .btn-cancel:hover {
    background: #e0e0e0;
}

#conversationModal .btn-primary {
    background: var(--orange);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: opacity 0.2s;
}

#conversationModal .btn-primary:hover {
    opacity: 0.9;
}
/* Modal button styles - match teacher */
.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
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

.btn-primary {
    background: var(--orange);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: opacity 0.2s;
}

.btn-primary:hover {
    opacity: 0.9;
}

/*Conversation list hover effect for Coordinator*/
#coordinatorMessagesContainer div[onclick]:hover {
    background: #f5f5f5 !important;
    /*transform: translateX(2px);*/
    transition: all 0.2s;
}


body.dark-mode #coordinatorMessagesContainer div[onclick]:hover {
    background: #2a2a4a !important;
}


/* New Chat Modal - Complete Style (Match Parent) */
#newMessageModal .modal-content {
    padding: 24px;
    border-radius: 16px;
    background: white;
}

#newMessageModal h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--dark-blue);
    margin-bottom: 20px;
}

#newMessageModal .recipient-search-container {
    position: relative;
    width: 100%;
}

#newMessageModal .recipient-search-input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border-gray);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

#newMessageModal .recipient-search-input:focus {
    border-color: var(--orange);
}

#newMessageModal .recipient-search-results {
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

#newMessageModal .recipient-search-results.show {
    display: block;
}

#newMessageModal .recipient-search-item {
    padding: 10px 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: background 0.2s;
}

#newMessageModal .recipient-search-item:hover {
    background: #f5f5f5;
}

#newMessageModal .recipient-search-item .avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--orange);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: white;
}

#newMessageModal .recipient-search-item .info {
    flex: 1;
}

#newMessageModal .recipient-search-item .name {
    font-weight: 500;
    font-size: 14px;
    color: #333;
}

#newMessageModal .recipient-search-item .type {
    font-size: 11px;
    color: #888;
}

#newMessageModal #messageInputSection {
    border-top: 1px solid #eee;
    padding-top: 20px;
}

#newMessageModal textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border-gray);
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
}

#newMessageModal textarea:focus {
    outline: none;
    border-color: var(--orange);
}

#newMessageModal .modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 15px;
}

#newMessageModal .btn-cancel {
    background: #f0f0f0;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

#newMessageModal .btn-cancel:hover {
    background: #e0e0e0;
}

#newMessageModal .btn-primary {
    background: var(--orange);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: opacity 0.2s;
}

#newMessageModal .btn-primary:hover {
    opacity: 0.9;
}

/* Dark mode support */
body.dark-mode #newMessageModal .modal-content {
    background: #16213e;
}

body.dark-mode #newMessageModal .recipient-search-results {
    background: #16213e;
    border-color: #2a2a4a;
}

body.dark-mode #newMessageModal .recipient-search-item:hover {
    background: #1f2a4a;
}

body.dark-mode #newMessageModal .recipient-search-item .name {
    color: #ddd;
}

body.dark-mode #newMessageModal .recipient-search-item .type {
    color: #aaa;
}

body.dark-mode #newMessageModal textarea {
    background: #0f0f2a;
    border-color: #2a2a4a;
    color: white;
}

/* Prevent browser password manager */
#newMessageModal input,
#newMessageModal textarea {
    transition: background-color 5000s ease-in-out 0s;
}
/* FIX: Remove shift from search results only */
#newMessageModal .recipient-search-item,
#newMessageModal .recipient-search-item:hover,
.recipient-search-item,
.recipient-search-item:hover {
    transform: translateX(0) !important;
    transform: translateY(0) !important;
    -webkit-transform: translateX(0) !important;
    -webkit-transform: translateY(0) !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    transition: none !important;
}
/* Prevent card hover effect on search results */
#newMessageModal .card:hover {
    transform: none !important;
}

#conversationMessages button {
    transition: transform 0.2s;
}

#conversationMessages button:hover {
    transform: scale(1.1);
}

.coordinator-message {
    text-align: right;
    margin-bottom: 5px;
}
.coordinator-message .bubble {
    display: inline-block;
    max-width: 70%;
    background: #FF6B35;
    color: white;
    padding: 8px 12px;
    border-radius: 18px;
    text-align: left;
}
.coordinator-delete {
    text-align: right;
    margin-bottom: 15px;
}
.coordinator-delete button {
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
            <div class="logo">Kinderbot CMS</div>
            <div class="user-info">
                <span>👤 Coordinator</span>
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
                        <li data-page="classes">📚 Classes</li>
                        <li data-page="teachers">👥 Teachers</li>
                        <li data-page="parents">👨‍👩‍👧 Parents</li>
                        <li data-page="students">👧 Students</li>
                        <li data-page="activities">📝 Activities</li>
                        <li data-page="messages">💬 Messages
                            <span id="messagesUnreadBadge" style="background:#dc3545; color:white; border-radius:10px; padding:2px 6px; font-size:10px; margin-left:5px; display:none;">0</span>
                        </li>
                        <li data-page="settings">⚙️ Settings</li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <!-- DASHBOARD PAGE - Now shows ALL data -->
                <div id="dashboardPage" class="page-content active">
                    <!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="summary-card-icon">📚</div>
        <div class="summary-card-info">
            <h3 id="totalClasses">0</h3>
            <p>Total Classes</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-card-icon">👥</div>
        <div class="summary-card-info">
            <h3 id="totalTeachers">0</h3>
            <p>Total Teachers</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-card-icon">👨‍👩‍👧</div>
        <div class="summary-card-info">
            <h3 id="totalParents">0</h3>
            <p>Total Parents</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-card-icon">👧</div>
        <div class="summary-card-info">
            <h3 id="totalStudents">0</h3>
            <p>Total Students</p>
        </div>
    </div>
</div>
                    <!-- Classes Section -->
                    <div class="section-header"><h2>Classes</h2>
                    </div>
                    <div id="classCardsContainer" class="class-cards"></div>

                    <!-- Teachers Section -->
                    <div class="section-header"><h2>Teachers</h2>
                    </div>
                    <div id="teachersDashboardContainer" class="data-table"></div>

                    <!-- Parents Section -->
                    <div class="section-header"><h2>Parents</h2>
                    </div>
                    <div id="parentsDashboardContainer" class="data-table"></div>

                    <!-- Students Section -->
                    <div class="section-header"><h2>Students</h2>

                    </div>
                    <div id="studentsDashboardContainer" class="data-table"></div>

                    <!-- All Activities Section -->
                    <div class="section-header"><h2>All Activities</h2>
                    </div>
                    <div id="allActivitiesContainer" class="data-table"></div>


                </div>

                <!-- CLASSES PAGE -->
                <div id="classesPage" class="page-content">
                    <div class="section-header"><h2>All Classes</h2><button class="btn-primary" id="newClassBtn2">+ New Class</button></div>
                    <div id="classesTableContainer" class="data-table"></div>
                </div>

                <!-- TEACHERS PAGE -->
                <div id="teachersPage" class="page-content">
                    <div class="section-header"><h2>Teachers</h2><button class="btn-primary" id="newTeacherBtn">+ New Teacher</button></div>
                    <div id="teachersTableContainer" class="data-table"></div>
                </div>

                <!-- PARENTS PAGE -->
                <div id="parentsPage" class="page-content">
                    <div class="section-header"><h2>Parents</h2><button class="btn-primary" id="newParentBtn">+ New Parent</button></div>
                    <div id="parentsTableContainer" class="data-table"></div>
                </div>

                <!-- STUDENTS PAGE -->
                <div id="studentsPage" class="page-content">
                    <div class="section-header"><h2>Students</h2><button class="btn-primary" id="newStudentBtn">+ New Student</button></div>
                    <div id="studentsTableContainer" class="data-table"></div>
                </div>

                <!-- ACTIVITIES PAGE -->
                <div id="activitiesPage" class="page-content">
                    <div class="section-header"><h2>All Activities</h2><button class="btn-primary" id="newActivityBtn2">+ New Activity</button></div>
                    <div id="activitiesTableContainer" class="data-table"></div>
                    <div id="recentActivitiesTableContainer" class="data-table"></div>
                </div>

                <!-- MESSAGES PAGE -->
                <div id="messagesPage" class="page-content">
                    <div class="section-header">
                    <h2>Messages</h2>
                    <button class="btn-primary" id="newCoordinatorMessageBtn">+ New Chat</button>
                </div>
                <div id="coordinatorMessagesContainer" class="data-table"></div>
                </div>

                <!-- SETTINGS PAGE -->
                <div id="settingsPage" class="page-content">
                    <div class="section-header"><h2>Settings</h2></div>
                    <div class="data-table">
                        <div class="settings-item"><div><strong>School Year</strong><div style="font-size:13px;">Current academic year</div></div><div><button class="btn-small" id="schoolYearBtn">2024-2025</button></div></div>
                        <div class="settings-item"><div><strong>System Theme</strong><div style="font-size:13px;">Light / Dark mode</div></div><div><button class="btn-small" id="themeBtn">Light</button></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (same as before) -->
    <div id="sectionsModal" class="modal"><div class="modal-content"><div class="modal-header"><h3 id="modalClassName">Class Sections</h3><button class="modal-close" onclick="closeSectionsModal()">&times;</button></div><div class="modal-body" id="sectionsModalBody"></div></div></div>
    <div id="addSectionModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Add New Section</h3><button class="modal-close" onclick="closeAddSectionModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Section Name</label><input type="text" id="newSectionName" placeholder="e.g., A, B" class="form-input"></div><div class="form-group"><label>Teacher</label><select id="newSectionTeacher" class="form-select"></select></div><div class="form-group"><label>Max Students</label><input type="number" id="newSectionMaxStudents" value="25" class="form-input"></div><div class="form-actions"><button class="btn-cancel" onclick="closeAddSectionModal()">Cancel</button><button class="btn-primary" onclick="saveNewSection()">Add Section</button></div></div></div></div>
    <div id="addStudentToSectionModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Add Student to Section</h3><button class="modal-close" onclick="closeAddStudentToSectionModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Select Student</label><select id="existingStudentId" class="form-select"><option value="">-- Select Student --</option></select></div><div class="form-actions"><button class="btn-cancel" onclick="closeAddStudentToSectionModal()">Cancel</button><button class="btn-primary" onclick="saveExistingStudent()">Add Student</button></div></div></div></div>
    <div id="newStudentModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Add New Student</h3><button class="modal-close" onclick="closeNewStudentModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Student Name</label><input type="text" id="newStudentName" class="form-input" placeholder="Full name"></div><div class="form-group"><label>Date of Birth</label><input type="date" id="newStudentDob" class="form-input"><small style="color:gray;">Age will be calculated automatically</small></div><div class="form-group"><label>Parent</label><select id="newStudentParentId" class="form-select"><option value="">-- Select Parent --</option></select></div><div class="form-actions"><button class="btn-cancel" onclick="closeNewStudentModal()">Cancel</button><button class="btn-primary" onclick="saveNewStudentRecord()">Save Student</button></div></div></div></div>
    <div id="editClassModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Class</h3><button class="modal-close" onclick="closeEditClassModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Class Name</label><input type="text" id="editClassName" class="form-input"></div><div class="form-group"><label>Teacher</label><input type="text" id="editClassTeacher" class="form-input"></div><div class="form-actions"><button class="btn-cancel" onclick="closeEditClassModal()">Cancel</button><button class="btn-primary" onclick="saveEditedClass()">Save</button></div></div></div></div>
    <div id="editTeacherModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Teacher</h3><button class="modal-close" onclick="closeEditTeacherModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Name</label><input type="text" id="editTeacherName" class="form-input"></div><div class="form-group"><label>Email</label><input type="email" id="editTeacherEmail" class="form-input"></div><div class="form-group"><label>Phone</label><input type="text" id="editTeacherPhone" class="form-input"></div><div class="form-group"><label>Class</label><input type="text" id="editTeacherClass" class="form-input"></div><div class="form-actions"><button class="btn-secondary" onclick="closeEditTeacherModal()">Cancel</button><button class="btn-primary" onclick="saveEditedTeacher()">Save</button></div></div></div></div>
    <div id="editParentModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Parent</h3><button class="modal-close" onclick="closeEditParentModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Name</label><input type="text" id="editParentName" class="form-input"></div><div class="form-group"><label>Email</label><input type="email" id="editParentEmail" class="form-input"></div><div class="form-group"><label>Phone</label><input type="text" id="editParentPhone" class="form-input"></div><div class="form-group"><label>Child</label><input type="text" id="editParentChild" class="form-input"></div><div class="form-actions"><button class="btn-secondary" onclick="closeEditParentModal()">Cancel</button><button class="btn-primary" onclick="saveEditedParent()">Save</button></div></div></div></div>


<!-- New Chat Modal -->
<div id="newMessageModal" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">New Chat</h3>
            <button class="modal-close" onclick="closeCoordinatorNewMessageModal()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div class="recipient-search-container">
            <input type="text" id="coordinatorRecipientSearch" class="recipient-search-input" placeholder="Search by name..." autocomplete="off">
            <div id="coordinatorRecipientSearchResults" class="recipient-search-results"></div>
        </div>
        <div id="coordinatorMessageInputSection" style="display:none; margin-top:20px;">
            <textarea id="coordinatorNewMessageText" rows="3" class="form-input" placeholder="Type your message here..."></textarea>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeCoordinatorNewMessageModal()">Cancel</button>
                <button class="btn-primary" onclick="sendCoordinatorNewMessage()">Send</button>
            </div>
        </div>
    </div>
</div>


<!-- Conversation Modal -->
<div id="conversationModal" class="modal">
    <div class="modal-content" style="max-width:600px;width:35% max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 id="conversationTitle" style="margin:0; font-size:18px;">Conversation</h3>
            <button class="modal-close" onclick="closeConversationModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:black;">&times;</button>
        </div>
        <div id="conversationMessages" style="max-height:400px; overflow-y:auto; margin-bottom:20px; padding:0 5px;">
            <div style="text-align:center; padding:20px;">Loading messages...</div>
        </div>
        <div id="replySection" style="border-top:1px solid #eee; padding-top:15px;">
            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:8px; font-size:14px; font-weight:500;">Reply:</label>
                <textarea id="replyMessage" rows="3" class="form-input" placeholder="Type your reply here..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; resize:vertical;"></textarea>
            </div>
            <div class="modal-buttons" style="display:flex; justify-content:flex-end; gap:12px;">
                <button class="btn-cancel" onclick="closeConversationModal()" style="padding:8px 16px; border-radius:8px; border:none; background:#f0f0f0; cursor:pointer;">Cancel</button>
                <button class="btn-primary" onclick="sendCoordinatorReply()" style="padding:8px 16px; border-radius:8px; border:none; background:#FF6B35; color:white; cursor:pointer;">Send Reply</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Get data from Laravel backend
    const classesData = @json($classes);
    const activitiesData = @json($activities);
    const teacherLogData = @json($teacherLog);

    let currentClassId = null;
    let currentSectionId = null;

    // ==================== HELPER FUNCTIONS ====================

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

    function getRelativeTime(timestamp) {
        if (!timestamp) return { text: "Just now", class: "just-now" };
        let date = new Date(timestamp);
        if (isNaN(date.getTime())) return { text: "Just now", class: "just-now" };
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);
        if (diffMins < 1) return { text: "Just now", class: "just-now" };
        if (diffMins < 60) return { text: `${diffMins} minute${diffMins === 1 ? '' : 's'} ago`, class: "recent" };
        if (diffHours < 24) return { text: `${diffHours} hour${diffHours === 1 ? '' : 's'} ago`, class: "recent" };
        if (diffDays === 1) return { text: "Yesterday", class: "recent" };
        return { text: `${diffDays} days ago`, class: "old" };
    }

    // ==================== SUMMARY CARDS ====================

    function loadSummaryCards() {
        fetch('/api/classes-with-details').then(r => r.json()).then(data => {
            document.getElementById('totalClasses').innerText = data.length;
        });
        fetch('/api/teachers').then(r => r.json()).then(data => {
            document.getElementById('totalTeachers').innerText = data.length;
        });
        fetch('/api/parents').then(r => r.json()).then(data => {
            document.getElementById('totalParents').innerText = data.length;
        });
        fetch('/api/students').then(r => r.json()).then(data => {
            document.getElementById('totalStudents').innerText = data.length;
        });
    }

    // ==================== DASHBOARD FUNCTIONS ====================

    function loadTeachersForDashboard() {
        const container = document.getElementById('teachersDashboardContainer');
        if (!container) return;
        fetch('/api/teachers').then(r => r.json()).then(data => {
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:20px;">No teachers yet.</p>'; return; }
            let html = '<table><thead><tr><th>Name</th><th>Email</th><th>Phone</th><tr></thead><tbody>';
            data.forEach(t => {
                html += `<tr><td><strong>${t.full_name}</strong></td><td>${t.email || '-'}</td><td>${t.phone || '-'}</td></tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }

    function loadParentsForDashboard() {
    const container = document.getElementById('parentsDashboardContainer');
    if (!container) return;
    fetch('/api/parents').then(r => r.json()).then(data => {
        if (data.length === 0) {
            container.innerHTML = '<p style="text-align:center;padding:20px;">No parents yet.</p>';
            return;
        }
        let html = '<table><thead><tr><th>Name</th><th>Email</th><th>Phone</th></tr></thead><tbody>';
        data.forEach(p => {
            html += `<tr>
                <td><strong>${p.full_name}</strong></td>
                <td>${p.email || '-'}</td>
                <td>${p.phone || '-'}</td>
            </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    });
}

function loadStudentsForDashboard() {
    const container = document.getElementById('studentsDashboardContainer');
    if (!container) return;
    fetch('/api/students').then(r => r.json()).then(data => {
        if (data.length === 0) {
            container.innerHTML = '<p style="text-align:center;padding:20px;">No students yet.</p>';
            return;
        }
        let html = '<table><thead><tr><th>Name</th><th>Date of Birth</th><th>Parent</th><th>Status</th></tr></thead><tbody>';
        data.forEach(s => {
            const status = s.is_enrolled ? 'Enrolled' : 'Not Enrolled';
            const dob = s.date_of_birth ? new Date(s.date_of_birth).toLocaleDateString() : '-';
            html += `<tr>
                <td><strong>${escapeHtml(s.full_name)}</strong></td>
                <td>${dob}</td>
                <td>${s.parent_name || '-'}</td>
                <td>${status}</td>
            </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    });
}

    // ==================== CLASS FUNCTIONS ====================

    function displayClassesDashboard() {
    const container = document.getElementById('classCardsContainer');
    if (!container) return;
    container.innerHTML = '<p style="text-align: center; padding: 40px;">Loading classes...</p>';
    fetch('/api/classes-with-details')
        .then(response => response.json())
        .then(classesWithData => {
            if (classesWithData.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px;">No classes yet. Click "+ New Class" to create one.</p>';
                return;
            }
            container.innerHTML = classesWithData.map(c => {
                const gradeLevelNum = parseInt(c.grade_level);
                const minAge = gradeLevelNum + 3;
                const maxAge = gradeLevelNum + 4;
                return `
                    <div class="card">
                        <h3>${c.name}</h3>
                        <div class="grade">Ages ${minAge}-${maxAge}</div>
                        <div class="stats">👥 ${c.totalStudents} students</div>
                        <div class="sections" style="font-size: 12px; color: var(--gray); margin: 8px 0;">📚 Sections: ${c.sectionsList || 'None'}</div>
                        <div style="display: flex; gap: 8px; margin-top: 12px;">
                            <button class="btn-outline" onclick="viewSections(${c.id})" style="flex: 1;">📋 View Sections</button>
                        </div>
                    </div>
                `;
            }).join('');
        });
}

function loadClassesTable() {
    const container = document.getElementById('classesTableContainer');
    if (!container) return;
    container.innerHTML = '<p style="text-align: center; padding: 40px;">Loading classes...</p>';
    fetch('/api/classes-with-details')
        .then(response => response.json())
        .then(classesWithData => {
            if (classesWithData.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px;">No classes yet. Click "+ New Class" to create one.</p>';
                return;
            }
            let html = '<table class="data-table"><thead><tr><th>Class Name</th><th>Age Range</th><th>Students</th><th>Sections</th><th>Actions</th></tr></thead><tbody>';
            classesWithData.forEach(c => {
                const gradeLevelNum = parseInt(c.grade_level);
                const minAge = gradeLevelNum + 3;
                const maxAge = gradeLevelNum + 4;
                html += `<tr>
                    <td><strong>${c.name}</strong></td>
                    <td>Ages ${minAge}-${maxAge}</td>
                    <td>${c.totalStudents}</td>
                    <td>${c.sectionsList || 'None'}</td>
                    <td>
                        <button class="btn-outline" onclick="viewSections(${c.id})">📋 View Sections</button>
                        <button class="btn-small" onclick="editClass(${c.id})">✏️ Edit</button>
                        <button class="btn-small btn-danger" onclick="deleteClass(${c.id}, '${c.name}')">🗑️ Delete</button>
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
}
   function addNewClass() {
    const className = prompt('Enter class name:');
    if (!className) return;
    const gradeLevel = prompt('Grade level (0=KG1,1=KG2,2=KG3,3=Grade1,4=Grade2):');
    if (gradeLevel === null) return;

    fetch('{{ route("classes.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ name: className, grade_level: parseInt(gradeLevel) })
    }).then(response => response.json()).then(data => {
        if (data.success) {
            alert('✅ Class added!');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    });
}

   function editClass(id) {
    fetch(`/api/classes/${id}`).then(r => r.json()).then(classData => {
        const newName = prompt('Edit class name:', classData.name);
        if (!newName) return;

        const newGradeLevel = prompt('Grade level (0=KG1,1=KG2,2=KG3,3=Grade1,4=Grade2):', classData.grade_level);
        if (newGradeLevel === null) return;

        fetch(`/api/classes/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                name: newName,
                grade_level: parseInt(newGradeLevel)
            })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                alert('✅ Class updated!');
                displayClassesDashboard();
                loadClassesTable();
            } else {
                alert('❌ Error: ' + data.message);
            }
        });
    });
}

    function deleteClass(id, name) {
        if (confirm(`Delete "${name}"?`)) {
            fetch(`/api/classes/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } });
        }
    }

    // ==================== ACTIVITY FUNCTIONS ====================
function displayAllActivities() {
    const container = document.getElementById('allActivitiesContainer');
    if (!container) return;
    if (activitiesData.length === 0) {
        container.innerHTML = '<p style="text-align:center;padding:40px;">No activities yet.</p>';
        return;
    }

    let html = '<table class="data-table"><thead><tr>';
    html += '<th>Title</th>';
    html += '<th>Class</th>';
    html += '<th>Status</th>';
    html += '<th>Created At</th>';
    html += '</thead><tbody>';

    activitiesData.forEach(a => {
        const classObj = classesData.find(c => c.id == a.class_id);
        const className = classObj ? classObj.name : 'Unknown';
        const createdDate = a.created_at ? new Date(a.created_at).toLocaleDateString() : '-';

        html += `<tr>
            <td><strong>${escapeHtml(a.title)}</strong></td>
            <td>${escapeHtml(className)}</td>
            <td><span class="badge ${a.is_published ? 'published' : 'draft'}">${a.is_published ? 'Published' : 'Draft'}</span></td>
            <td>${createdDate}</td>
        </tr>`;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

function loadActivitiesTable() {
    fetch('/api/activities').then(r => r.json()).then(data => {
        const container = document.getElementById('activitiesTableContainer');
        if (data.length === 0) {
            container.innerHTML = '<p style="text-align:center;padding:40px;">No activities yet.</p>';
            return;
        }

        let html = '<table class="data-table"><thead><tr>';
        html += '<th>Title</th>';
        html += '<th>Class</th>';
        html += '<th>Status</th>';
        html += '<th>Created At</th>';
        html += '<th>Actions</th>';
        html += '</thead><tbody>';

        data.forEach(a => {
            const classObj = classesData.find(c => c.id == a.class_id);
            const className = classObj ? classObj.name : 'Unknown';
            const createdDate = a.created_at ? new Date(a.created_at).toLocaleDateString() : '-';

            html += `<tr>
                <td><strong>${escapeHtml(a.title)}</strong></td>
                <td>${escapeHtml(className)}</td>
                <td><span class="badge ${a.is_published ? 'published' : 'draft'}">${a.is_published ? 'Published' : 'Draft'}</span></td>
                <td>${createdDate}</td>
                <td>
                    <button class="btn-small" onclick="viewActivity(${a.id})">👁️ View</button>
                    <button class="btn-small" onclick="editActivity(${a.id})">✏️ Edit</button>
                    <button class="btn-small btn-danger" onclick="deleteActivity(${a.id}, '${escapeHtml(a.title)}')">🗑️ Delete</button>
                </td>
            </tr>`;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    });
}


    function deleteActivity(id, title) { if (confirm(`Delete "${title}"?`)) { fetch(`/api/activities/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }

  function editActivity(id) {
    window.location.href = `/activities/${id}/edit`;
}

function viewActivity(id) {
    window.location.href = `/activities/${id}`;
}
    // ==================== SECTION FUNCTIONS ====================

    function viewSections(classId) {
        currentClassId = classId;
        const classItem = classesData.find(c => c.id == classId);
        document.getElementById('modalClassName').innerHTML = `${classItem ? classItem.name : 'Class'} - Sections`;
        loadSectionsForClass(classId);
        document.getElementById('sectionsModal').style.display = 'flex';
    }

    function loadSectionsForClass(classId) {
        fetch(`/api/sections/${classId}/details`).then(r => r.json()).then(data => {
            const body = document.getElementById('sectionsModalBody');
            const isDashboard = document.getElementById('dashboardPage').classList.contains('active');

            if (data.length === 0) {
                body.innerHTML = `<div class="empty-state"><p>No sections found.</p>` + (isDashboard ? '' : `<button class="btn-primary" onclick="openAddSectionModal()">+ Add First Section</button>`) + `</div>`;
                return;
            }

            let html = '';
            data.forEach(section => {
                html += `<div class="section-card">
                    <div style="display: flex; justify-content: space-between;">
                        <h4>Section ${section.section_name}</h4>
                        <div>` + (isDashboard ? '' : `<button class="btn-small" onclick="editSection(${section.id})">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteSection(${section.id})">🗑️ Delete</button>`) + `</div>
                    </div>
                    <div><strong>👩‍🏫 Teacher:</strong> ${section.teacher_name || 'Not assigned'}</div>
                    <div><strong>👥 Students:</strong> ${section.students.length} / ${section.max_students}</div>
                    <div><strong>Student List:</strong></div>
                    ${section.students.map(s => `<div style="display: flex; justify-content: space-between; padding: 5px;"><span>👦 ${s.full_name}</span>` + (isDashboard ? '' : `<button class="btn-small btn-danger" onclick="removeStudent(${section.id}, ${s.id})">Remove</button>`) + `</div>`).join('')}
                    ` + (isDashboard ? '' : `<button class="add-student-btn" onclick="openAddStudentToSectionModal(${section.id})">+ Add Student</button>`) + `
                </div>`;
            });

            if (!isDashboard) {
                html += `<button class="btn-primary" onclick="openAddSectionModal()" style="width:100%; margin-top:16px;">+ Add New Section</button>`;
            }

            body.innerHTML = html;
        });
    }

    function openAddSectionModal() {
        fetch('/api/teachers/list').then(r => r.json()).then(teachers => {
            let teacherOptions = '<option value="">Select Teacher</option>';
            teachers.forEach(t => { teacherOptions += `<option value="${t.id}">${t.full_name}</option>`; });
            document.getElementById('addSectionModal').querySelector('.modal-body').innerHTML = `
                <div><label>Section Name</label><input type="text" id="newSectionName" class="form-input"></div>
                <div><label>Teacher</label><select id="newSectionTeacher" class="form-select">${teacherOptions}</select></div>
                <div><label>Max Students</label><input type="number" id="newSectionMaxStudents" value="25" class="form-input"></div>
                <div class="form-actions"><button class="btn-cancel" onclick="closeAddSectionModal()">Cancel</button><button class="btn-primary" onclick="saveNewSection()">Add Section</button></div>
            `;
        });
        document.getElementById('sectionsModal').style.display = 'none';
        document.getElementById('addSectionModal').style.display = 'flex';
    }

    function closeAddSectionModal() {
        document.getElementById('addSectionModal').style.display = 'none';
        if (currentClassId) { document.getElementById('sectionsModal').style.display = 'flex'; loadSectionsForClass(currentClassId); }
    }

    function saveNewSection() {
        const sectionName = document.getElementById('newSectionName').value;
        const teacherId = document.getElementById('newSectionTeacher').value;
        const maxStudents = document.getElementById('newSectionMaxStudents').value;
        if (!sectionName) { alert('Enter section name'); return; }
        fetch('/api/sections/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ class_id: currentClassId, section_name: sectionName, teacher_id: teacherId || null, max_students: maxStudents })
        }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Section added!'); closeAddSectionModal(); loadSectionsForClass(currentClassId); loadClassesTable(); displayClassesDashboard(); } else { alert('❌ Error: ' + data.message); } });
    }

    function editSection(sectionId) {
        fetch(`/api/sections/${sectionId}`)
            .then(response => response.json())
            .then(section => {
                const newName = prompt('New section name:', section.section_name);
                if (!newName) return;
                const newMax = prompt('Max students:', section.max_students);
                if (!newMax) return;
                fetch('/api/teachers/list')
                    .then(r => r.json())
                    .then(teachers => {
                        let teacherList = '0 = No teacher\n';
                        teachers.forEach(t => { teacherList += `${t.id} = ${t.full_name}\n`; });
                        const teacherId = prompt(`Enter teacher ID:\n${teacherList}`, section.teacher_id || '0');
                        fetch(`/api/sections/${sectionId}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ section_name: newName, max_students: parseInt(newMax), teacher_id: teacherId === '0' ? null : parseInt(teacherId) })
                        }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Section updated!'); loadSectionsForClass(currentClassId); } });
                    });
            });
    }

    function deleteSection(sectionId) {
        if (confirm('Delete this section?')) {
            fetch(`/api/sections/${sectionId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Section deleted!'); loadSectionsForClass(currentClassId); } });
        }
    }

    // ==================== STUDENT SECTION FUNCTIONS ====================

    function openAddStudentToSectionModal(sectionId) {
        currentSectionId = sectionId;
        fetch(`/api/sections/${sectionId}/available-students`)
            .then(response => response.json())
            .then(students => {
                let options = '<option value="">-- Select Student --</option>';
                students.forEach(s => { options += `<option value="${s.id}">${s.full_name} (DOB: ${s.date_of_birth})</option>`; });
                if (students.length === 0) options = '<option value="">No eligible students available</option>';
                document.getElementById('existingStudentId').innerHTML = options;
                document.getElementById('sectionsModal').style.display = 'none';
                document.getElementById('addStudentToSectionModal').style.display = 'flex';
            });
    }

    function closeAddStudentToSectionModal() {
        document.getElementById('addStudentToSectionModal').style.display = 'none';
        if (currentClassId) { document.getElementById('sectionsModal').style.display = 'flex'; loadSectionsForClass(currentClassId); }
    }

    function saveExistingStudent() {
        const studentId = document.getElementById('existingStudentId').value;
        if (!studentId) { alert('Please select a student'); return; }
        fetch('/api/sections/add-student', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ section_id: currentSectionId, student_id: studentId })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                alert('✅ Student added to section!');
                closeAddStudentToSectionModal();
                loadSectionsForClass(currentClassId);
                displayClassesDashboard();
                loadClassesTable();
            }
        });
    }

    function removeStudent(sectionId, studentId) {
        if (confirm('Remove student from section?')) {
            fetch(`/api/sections/${sectionId}/student/${studentId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    alert('✅ Student removed');
                    loadSectionsForClass(currentClassId);
                    displayClassesDashboard();
                    loadClassesTable();
                }
            });
        }
    }

    function closeSectionsModal() { document.getElementById('sectionsModal').style.display = 'none'; }

    // ==================== TEACHER FUNCTIONS ====================

    function loadTeachersTable() {
        fetch('/api/teachers').then(r => r.json()).then(data => {
            const container = document.getElementById('teachersTableContainer');
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:40px;">No teachers yet.</p>'; return; }
            let html = '<table class="data-table"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(t => {
                html += `<tr>
                    <td><strong>${t.full_name}</strong></td>
                    <td>${t.email || '-'}</td>
                    <td>${t.phone || '-'}</td>
                    <td><button class="btn-small" onclick="editTeacher(${t.id}, '${t.full_name}', '${t.email || ''}', '${t.phone || ''}')">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteTeacher(${t.id}, '${t.full_name}')">🗑️ Delete</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }


    function addNewTeacher() {
    const fullName = prompt('Enter teacher name:');
    if (!fullName) return;
    const email = prompt('Email (required - credentials will be sent here):');
    if (!email) { alert('Email is required'); return; }
    const phone = prompt('Phone (required):');
    if (!phone) { alert('Phone is required'); return; }

    fetch('/api/teachers/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ full_name: fullName, email: email, phone: phone || '' })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert(`✅ Teacher added successfully!\n\n📧 Credentials sent to: ${data.email}\n\n⚠️ The teacher will receive their password via email.`);
            loadTeachersTable();
            loadTeachersForDashboard();
        } else {
            alert('❌ Error: ' + data.message);
        }
    });
}

    function editTeacher(id, currentName, currentEmail, currentPhone) {
        const newName = prompt('Edit name:', currentName);
        if (!newName) return;
        const newEmail = prompt('Edit email:', currentEmail);
        const newPhone = prompt('Edit phone:', currentPhone);
        fetch(`/api/teachers/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ full_name: newName, email: newEmail || '', phone: newPhone || '' })
        }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Teacher updated!'); loadTeachersTable(); loadTeachersForDashboard(); } else { alert('❌ Error: ' + data.message); } });
    }

    function deleteTeacher(id, name) { if (confirm(`Delete "${name}"?`)) { fetch(`/api/teachers/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }

    // ==================== PARENT FUNCTIONS ====================

    function loadParentsTable() {
        fetch('/api/parents').then(r => r.json()).then(data => {
            const container = document.getElementById('parentsTableContainer');
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:40px;">No parents yet.</p>'; return; }
            let html = '<table class="data-table"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(p => {
                html += `<tr>
                    <td><strong>${p.full_name}</strong></td>
                    <td>${p.email || '-'}</td>
                    <td>${p.phone || '-'}</td>
                    <td><button class="btn-small" onclick="editParent(${p.id}, '${p.full_name}', '${p.email || ''}', '${p.phone || ''}')">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteParent(${p.id}, '${p.full_name}')">🗑️ Delete</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }


function addNewParent() {
    const fullName = prompt('Enter parent name:');
    if (!fullName) return;
    const email = prompt('Email (required - credentials will be sent here):');
    if (!email) { alert('Email is required'); return; }
    const phone = prompt('Phone (required):');
    if (!phone) { alert('Phone is required'); return; }

    fetch('/api/parents/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ full_name: fullName, email: email, phone: phone || '' })
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert(`✅ Parent added successfully!\n\n📧 Credentials sent to: ${data.email}\n\n⚠️ The parent will receive their password via email.`);
            loadParentsTable();
            loadParentsForDashboard();
        } else {
            alert('❌ Error: ' + data.message);
        }
    });
}
    function editParent(id, currentName, currentEmail, currentPhone) {
        const newName = prompt('Edit name:', currentName);
        if (!newName) return;
        const newEmail = prompt('Edit email:', currentEmail);
        if (!newEmail) return;
        const newPhone = prompt('Edit phone:', currentPhone);
        fetch(`/api/parents/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ full_name: newName, email: newEmail, phone: newPhone || '' })
        }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Parent updated!'); loadParentsTable(); loadParentsForDashboard(); } else { alert('❌ Error: ' + data.message); } });
    }

    function deleteParent(id, name) { if (confirm(`Delete "${name}"?`)) { fetch(`/api/parents/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }

    // ==================== STUDENT PAGE FUNCTIONS ====================


   function loadStudentsTable() {
    fetch('/api/students').then(response => response.json()).then(data => {
        const container = document.getElementById('studentsTableContainer');
        if (data.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px;">No students yet. Click "+ New Student" to create one.</p>';
            return;
        }

        let html = '<table class="data-table"><thead><tr><th>Name</th><th>Date of Birth</th><th>Suggested Class</th><th>Status</th><th>Parent</th><th>Actions</th></tr></thead><tbody>';

        data.forEach(student => {
            const status = student.is_enrolled ? '<span class="badge published">Enrolled</span>' : '<span class="badge draft">Not Enrolled</span>';
            const dob = student.date_of_birth ? new Date(student.date_of_birth).toLocaleDateString() : '-';

            html += `<tr>
                <td><strong>${escapeHtml(student.full_name)}</strong></td>
                <td>${dob}</td>
                <td>${student.suggested_class_name || '-'}</td>
                <td>${status}</td>
                <td>${student.parent_name || 'No parent assigned'}</td>
                <td>
                    <button class="btn-small" onclick="openEditStudentModal(${student.id})">✏️ Edit</button>
                    <button class="btn-small btn-danger" onclick="deleteStudent(${student.id}, '${escapeHtml(student.full_name)}')">🗑️ Delete</button>
                </td>
            </tr>`;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    });
}

   function openEditStudentModal(id) {
    fetch(`/api/students/${id}`).then(response => response.json()).then(student => {
        document.getElementById('editStudentId').value = student.id;
        document.getElementById('editStudentName').value = student.full_name;
        document.getElementById('editStudentDob').value = student.date_of_birth;

        fetch('/api/parents').then(response => response.json()).then(parents => {
            let options = '<option value="">-- Select Parent --</option>';
            parents.forEach(p => {
                options += `<option value="${p.id}" ${student.parent_id == p.id ? 'selected' : ''}>${escapeHtml(p.full_name)}</option>`;
            });
            document.getElementById('editStudentParentId').innerHTML = options;
            document.getElementById('editStudentModal').style.display = 'flex';
        });
    });
}

    function closeEditStudentModal() { document.getElementById('editStudentModal').style.display = 'none'; }

    function updateStudentRecord() {
        const id = document.getElementById('editStudentId').value;
        const fullName = document.getElementById('editStudentName').value;
        const dob = document.getElementById('editStudentDob').value;
        const parentId = document.getElementById('editStudentParentId').value;
        if (!fullName) { alert('Please enter student name'); return; }
        fetch(`/api/students/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ full_name: fullName, date_of_birth: dob, parent_id: parentId || null })
        }).then(response => response.json()).then(data => {
            if (data.success) { alert('✅ Student updated successfully!'); closeEditStudentModal(); loadStudentsTable(); loadStudentsForDashboard(); } else { alert('❌ Error: ' + data.message); }
        });
    }


   function openNewStudentModal() {
    fetch('/api/parents').then(r => r.json()).then(parents => {
        let options = '<option value="">-- Select Parent --</option>';
        parents.forEach(p => {
            options += `<option value="${p.id}">${escapeHtml(p.full_name)}</option>`;
        });
        document.getElementById('newStudentParentId').innerHTML = options;
    });
    document.getElementById('newStudentModal').style.display = 'flex';
}

    function closeNewStudentModal() {
        document.getElementById('newStudentModal').style.display = 'none';
        document.getElementById('newStudentName').value = '';
        document.getElementById('newStudentDob').value = '';
    }

    function saveNewStudentRecord() {
        const fullName = document.getElementById('newStudentName').value;
        const dob = document.getElementById('newStudentDob').value;
        const parentId = document.getElementById('newStudentParentId').value;
        if (!fullName) { alert('Please enter student name'); return; }
        fetch('/api/students/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ full_name: fullName, date_of_birth: dob, parent_id: parentId || null })
        }).then(r => r.json()).then(data => {
            if (data.success) { alert('✅ Student added!'); closeNewStudentModal(); loadStudentsTable(); loadStudentsForDashboard(); }
            else { alert('❌ Error: ' + data.message); }
        });
    }

    function deleteStudent(id, name) { if (confirm(`Delete "${name}"?`)) { fetch(`/api/students/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }

    // ==================== COORDINATOR MESSAGES ====================


    let coordinatorRecipients = [];

    function openCoordinatorMessageModal() {
        loadCoordinatorRecipients();
        document.getElementById('coordinatorMessageSubject').value = '';
        document.getElementById('coordinatorMessageBody').value = '';
        document.getElementById('coordinatorMessageModal').style.display = 'flex';
    }

    function closeCoordinatorMessageModal() {
        document.getElementById('coordinatorMessageModal').style.display = 'none';
    }

    async function sendCoordinatorMessage() {
        const select = document.getElementById('coordinatorMessageTo');
        const receiverId = select.value;
        const selectedOption = select.options[select.selectedIndex];
        const receiverType = selectedOption?.getAttribute('data-type');
        const subject = document.getElementById('coordinatorMessageSubject').value;
        const message = document.getElementById('coordinatorMessageBody').value;

        if (!receiverId) {
            alert('Please select a recipient');
            return;
        }
        if (!message) {
            alert('Please enter a message');
            return;
        }

        const result = await postAPI('/api/coordinator/send-message', {
            receiver_id: receiverId,
            receiver_type: receiverType,
            subject: subject,
            message: message
        });

        if (result.success) {
            //alert('✅ Message sent successfully!');
            closeCoordinatorMessageModal();
            displayCoordinatorMessages();
        } else {
            alert('❌ Error: ' + result.message);
        }
    }

async function displayCoordinatorMessages() {
    const messages = await fetchAPI('/api/coordinator/messages');
    const container = document.getElementById('coordinatorMessagesContainer');

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
                lastTimestamp: new Date(m.date).getTime(),  // ← ADD THIS for sorting
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

    // ← SORT CONVERSATIONS BY LATEST MESSAGE (NEWEST FIRST)
    const sortedConversations = Object.values(conversations).sort((a, b) => {
        return b.lastTimestamp - a.lastTimestamp;
    });

    let html = '<div style="display:flex; flex-direction:column; gap:8px;">';

    sortedConversations.forEach(conv => {
        let icon = conv.type === 'teacher' ? '👩‍🏫' : '👨‍👩‍👧';
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
            <div onclick="openCoordinatorConversation('${conv.id}', '${conv.name}', '${conv.type}')" style="display:flex; align-items:center; gap:15px; background:white; border-radius:12px; padding:12px 16px; cursor:pointer; transition:background 0.2s; border:1px solid #e0e0e0;">
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

    let currentCoordinatorPartnerId = null;
    let currentCoordinatorPartnerName = null;
    let currentCoordinatorPartnerType = null;

    async function openCoordinatorConversation(partnerId, partnerName, partnerType) {
        currentCoordinatorPartnerId = partnerId;
        currentCoordinatorPartnerName = partnerName;
        currentCoordinatorPartnerType = partnerType;
        document.getElementById('conversationTitle').innerHTML = `💬 Conversation with ${partnerName}`;
        document.getElementById('conversationModal').style.display = 'flex';
        document.getElementById('replyMessage').value = '';
        await loadCoordinatorConversation(partnerId);
        displayCoordinatorMessages();
        await updateTotalUnreadCount();
    }

async function loadCoordinatorConversation(participantId) {
    const container = document.getElementById('conversationMessages');
    container.innerHTML = '<div style="text-align:center; padding:20px;">Loading messages...</div>';
    const messages = await fetchAPI(`/api/coordinator/conversation/${participantId}`);

    if (messages.length === 0) {
        container.innerHTML = '<div style="text-align:center; padding:20px;">No messages yet. Start the conversation!</div>';
        return;
    }

    let html = '';
    let lastDate = '';

    messages.forEach(msg => {
        const isCoordinator = msg.sender_type === 'coordinator';

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


if (isCoordinator) {
    html += `<div class="coordinator-message">
                <div class="bubble">
                    <div style="font-size:12px; font-weight:500;">${msg.sender}</div>
                    <div style="font-size:14px;">${msg.message}</div>
                    <div style="font-size:10px; opacity:0.6; margin-top:4px;">${dateDisplay}</div>
                </div>
            </div>
            <div class="coordinator-delete">
                <button onclick="deleteMsg(${msg.id})">x</button>
            </div>`;
}

else {
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



async function deleteMsg(messageId) {
    if (!confirm('Delete this message?')) return;

    const response = await fetch(`/api/messages/${messageId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    if (response.ok) {
        //alert('✅ Message deleted');
        if (currentCoordinatorPartnerId) {
            await loadCoordinatorConversation(currentCoordinatorPartnerId);
        }
        displayCoordinatorMessages();
    } else {
        alert('❌ Error deleting message');
    }
}

// Add escape function for security
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}


async function deleteCoordinatorMessage(messageId) {
    if (!confirm('Delete this message? It will be hidden from your view.')) return;

    const response = await fetch(`/api/messages/${messageId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    const data = await response.json();
    if (data.success) {
        alert('✅ Message deleted');
        // Refresh the current conversation
        if (currentCoordinatorPartnerId) {
            await loadCoordinatorConversation(currentCoordinatorPartnerId);
        }
    } else {
        alert('❌ Error: ' + (data.message || 'Could not delete message'));
    }
}

    function closeConversationModal() {
        document.getElementById('conversationModal').style.display = 'none';
        currentCoordinatorPartnerId = null;
    }

    async function sendCoordinatorReply() {
    const replyText = document.getElementById('replyMessage').value;
    if (!replyText) {
        alert('Please enter a reply message');
        return;
    }

    // Make sure we have the receiver type
    if (!currentCoordinatorPartnerType) {
        alert('Error: Missing recipient type');
        return;
    }

    const response = await fetch('/api/coordinator/reply-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: currentCoordinatorPartnerId,
            receiver_type: currentCoordinatorPartnerType,  // ← MAKE SURE THIS IS INCLUDED
            subject: 'Re: Conversation',
            message: replyText
        })
    });

    const data = await response.json();
    if (data.success) {
       // alert('✅ Reply sent successfully!');
        document.getElementById('replyMessage').value = '';
        await loadCoordinatorConversation(currentCoordinatorPartnerId);
        displayCoordinatorMessages();
    } else {
        alert('❌ Error: ' + data.message);
    }
}
// ==================== NEW CHAT (WHATSAPP STYLE) ====================


let selectedCoordinatorRecipient = null;
let allCoordinatorRecipients = [];

async function loadCoordinatorRecipients() {
    const recipients = await fetchAPI('/api/coordinator/recipients');
    allCoordinatorRecipients = recipients;
    console.log('Coordinator recipients loaded:', allCoordinatorRecipients);
}

function openCoordinatorNewMessageModal() {
    selectedCoordinatorRecipient = null;
    document.getElementById('coordinatorRecipientSearch').value = '';
    document.getElementById('coordinatorMessageInputSection').style.display = 'none';
    document.getElementById('coordinatorNewMessageText').value = '';
    document.getElementById('coordinatorRecipientSearchResults').innerHTML = '';
    document.getElementById('coordinatorRecipientSearchResults').classList.remove('show');
    document.getElementById('newMessageModal').style.display = 'flex';
    document.getElementById('coordinatorRecipientSearch').focus();
    // SHOW THE X BUTTON
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'flex';
}

function closeCoordinatorNewMessageModal() {
    document.getElementById('newMessageModal').style.display = 'none';
    document.getElementById('coordinatorMessageInputSection').style.display = 'none';
    document.getElementById('coordinatorRecipientSearch').value = '';
    document.getElementById('coordinatorRecipientSearchResults').innerHTML = '';
    document.getElementById('coordinatorRecipientSearchResults').classList.remove('show');
    // SHOW THE X BUTTON AGAIN
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'flex';
}

function searchCoordinatorRecipients() {
    const searchTerm = document.getElementById('coordinatorRecipientSearch').value.toLowerCase().trim();
    const resultsDiv = document.getElementById('coordinatorRecipientSearchResults');

    if (searchTerm.length === 0) {
        resultsDiv.classList.remove('show');
        return;
    }

    if (!allCoordinatorRecipients || allCoordinatorRecipients.length === 0) {
        resultsDiv.innerHTML = '<div style="padding:10px 15px; color:gray;">Loading users...</div>';
        resultsDiv.classList.add('show');
        return;
    }

    const filtered = allCoordinatorRecipients.filter(r =>
        r.name.toLowerCase().includes(searchTerm)
    );

    if (filtered.length === 0) {
        resultsDiv.innerHTML = '<div style="padding:10px 15px; color:gray;">No users found</div>';
        resultsDiv.classList.add('show');
        return;
    }

    let html = '';
    filtered.forEach(r => {
        let icon = r.type === 'teacher' ? '👩‍🏫' : '👨‍👩‍👧';
        html += `
            <div class="recipient-search-item" onclick="selectCoordinatorRecipient('${r.id}', '${r.name}', '${r.type}')">
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

function selectCoordinatorRecipient(id, name, type) {
    selectedCoordinatorRecipient = { id, name, type };
    document.getElementById('coordinatorRecipientSearch').value = name;
    document.getElementById('coordinatorRecipientSearchResults').classList.remove('show');
    document.getElementById('coordinatorMessageInputSection').style.display = 'block';
    document.getElementById('coordinatorNewMessageText').focus();
    // HIDE THE X BUTTON
    const xButton = document.querySelector('#newMessageModal .modal-close');
    if (xButton) xButton.style.display = 'none';
}

async function sendCoordinatorNewMessage() {
    const message = document.getElementById('coordinatorNewMessageText').value.trim();

    if (!selectedCoordinatorRecipient) {
        alert('Please select a recipient');
        return;
    }
    if (!message) {
        alert('Please enter a message');
        return;
    }

    const response = await fetch('/api/coordinator/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            receiver_id: selectedCoordinatorRecipient.id,
            receiver_type: selectedCoordinatorRecipient.type,
            message: message,
            subject: null
        })
    });

    const data = await response.json();
    if (data.success) {
        //alert('✅ Message sent successfully!');
        closeCoordinatorNewMessageModal();
        displayCoordinatorMessages();
        openCoordinatorConversation(selectedCoordinatorRecipient.id, selectedCoordinatorRecipient.name, selectedCoordinatorRecipient.type);
    } else {
        alert('❌ Error: ' + data.message);
    }
}
// Update event listeners
document.getElementById('newCoordinatorMessageBtn')?.addEventListener('click', openCoordinatorNewMessageModal);
document.getElementById('coordinatorRecipientSearch')?.addEventListener('input', searchCoordinatorRecipients);


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
    const response = await fetch('/api/unread-count', {
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
}

    // ==================== SIDEBAR NAVIGATION ====================

    const sidebarItems = document.querySelectorAll('.sidebar nav li');
    const pages = {
        dashboard: document.getElementById('dashboardPage'),
        classes: document.getElementById('classesPage'),
        teachers: document.getElementById('teachersPage'),
        parents: document.getElementById('parentsPage'),
        students: document.getElementById('studentsPage'),
        activities: document.getElementById('activitiesPage'),
        settings: document.getElementById('settingsPage'),
        messages: document.getElementById('messagesPage')
    };

    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            const pageName = item.getAttribute('data-page');
            sidebarItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            Object.values(pages).forEach(p => p?.classList.remove('active'));
            if (pages[pageName]) pages[pageName].classList.add('active');
            if (pageName === 'classes') loadClassesTable();
            if (pageName === 'teachers') loadTeachersTable();
            if (pageName === 'parents') loadParentsTable();
            if (pageName === 'students') loadStudentsTable();
            if (pageName === 'activities') { loadActivitiesTable();}
            if (pageName === 'messages') displayCoordinatorMessages();
        });
    });


    document.getElementById('newClassBtn')?.addEventListener('click', addNewClass);
    document.getElementById('newClassBtn2')?.addEventListener('click', addNewClass);
    document.getElementById('newTeacherBtn')?.addEventListener('click', addNewTeacher);
    document.getElementById('newParentBtn')?.addEventListener('click', addNewParent);
    document.getElementById('newStudentBtn')?.addEventListener('click', openNewStudentModal);
    document.getElementById('newActivityBtn2')?.addEventListener('click', () => window.location.href = '{{ route("create-activity") }}');
    document.getElementById('newCoordinatorMessageBtn')?.addEventListener('click', openCoordinatorMessageModal);

    // ==================== SETTINGS BUTTONS ====================
    document.getElementById('schoolYearBtn')?.addEventListener('click', () => { const ny = prompt('School year (YYYY-YYYY):', '2024-2025'); if (ny) document.getElementById('schoolYearBtn').innerText = ny; });
    const savedTheme = localStorage.getItem('coordinator_theme');
    if (savedTheme === 'dark') { document.body.classList.add('dark-mode'); document.getElementById('themeBtn').innerHTML = 'Dark Mode 🌙'; }

    // Load all dashboard data
    loadSummaryCards();
    displayClassesDashboard();
    displayAllActivities();
    //displayTeacherLog();
    loadTeachersForDashboard();
    loadParentsForDashboard();
    loadStudentsForDashboard();
    loadCoordinatorRecipients();
    updateTotalUnreadCount();

</script>
</body>
</html>
