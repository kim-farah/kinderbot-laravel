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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="top-bar">
            <div class="logo">👩‍🏫 Kinderbot Teacher</div>
            <div class="user-info">
                <span id="teacherName">👤 Teacher</span>
                <button class="logout-btn" onclick="location.href='{{ route('login') }}'">Logout</button>
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
                        <li data-page="messages">💬 Messages</li>
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
                <div id="messagesPage" class="page-content"><div class="section-header"><h2>Messages</h2><button class="btn-primary" id="newMessageBtn">+ New Message</button></div><div id="messagesContainer" class="data-table"><div class="loading">Loading...</div></div></div>
                <div id="settingsPage" class="page-content">
                    <div class="section-header"><h2>Settings</h2></div>
                    <div class="data-table">
                        <div class="settings-item"><div><strong>🔔 Notification Preferences</strong><div style="font-size:13px;">Email notifications for new activities</div></div><div><button class="btn-small" id="toggleNotificationsBtn">Enabled ✓</button></div></div>
                        <div class="settings-item"><div><strong>📅 Class Schedule</strong><div style="font-size:13px;">View your weekly teaching schedule</div></div><div><button class="btn-small" id="viewScheduleBtn">View Schedule</button></div></div>
                        <div class="settings-item"><div><strong>🔑 Account Settings</strong><div style="font-size:13px;">Update password and profile</div></div><div><button class="btn-small" id="editAccountBtn">Edit Account</button></div></div>
                        <div class="settings-item"><div><strong>🌙 Dark Mode</strong><div style="font-size:13px;">Switch theme preference</div></div><div><button class="btn-small" id="darkModeBtn">Light Mode</button></div></div>
                        <div class="settings-item"><div><strong>🌐 Language</strong><div style="font-size:13px;">Choose your preferred language</div></div><div><button class="btn-small" id="languageBtn">English</button></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="classDetailsModal" class="modal"><div class="modal-content"><h3 id="classDetailsTitle">Class Details</h3><div id="classDetailsContent"></div><div class="modal-buttons"><button class="btn-cancel" onclick="closeClassModal()">Close</button></div></div></div>
    <div id="assessmentModal" class="modal"><div class="modal-content"><h3>Quick Assessment</h3><label>Student:</label><select id="modalStudent"><option value="">Select Student</option></select><label>Activity:</label><select id="modalActivity"><option value="">Select Activity</option></select><label>Rating:</label><div class="star-rating" id="starRating"><span class="star" data-value="1">★</span><span class="star" data-value="2">★</span><span class="star" data-value="3">★</span><span class="star" data-value="4">★</span><span class="star" data-value="5">★</span></div><label>Comments:</label><textarea id="modalComments" rows="3"></textarea><div class="modal-buttons"><button class="btn-cancel" onclick="closeAssessmentModal()">Cancel</button><button class="btn-save" onclick="saveAssessment()">Save</button></div></div></div>
    <div id="messageModal" class="modal"><div class="modal-content"><h3>Send Message</h3><label>To:</label><input type="text" id="messageTo" class="form-input" readonly><label>Subject:</label><input type="text" id="messageSubject" class="form-input"><label>Message:</label><textarea id="messageBody" rows="4" class="form-input"></textarea><div class="modal-buttons"><button class="btn-cancel" onclick="closeMessageModal()">Cancel</button><button class="btn-save" onclick="sendMessage()">Send</button></div></div></div>

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
            const container = document.getElementById('messagesContainer');
            const data = await fetchAPI('/api/teacher/messages') || [];

            if (data.length === 0) {
                container.innerHTML = '<p style="text-align:center;padding:40px;">No messages yet.</p>';
                return;
            }

            let html = '<table><thead><tr><th>From</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(m => {
                html += `<tr>
                    <td><strong>${m.from}</strong></td>
                    <td>${m.message}</td>
                    <td>${m.date}</td>
                    <td><button class="btn-small" onclick="openMessageModal('${m.from}')">Reply</button></td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
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

        function openMessageModal(to) {
            document.getElementById('messageTo').value = to;
            document.getElementById('messageSubject').value = '';
            document.getElementById('messageBody').value = '';
            document.getElementById('messageModal').style.display = 'flex';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        async function sendMessage() {
            const to = document.getElementById('messageTo').value;
            const subject = document.getElementById('messageSubject').value;
            const body = document.getElementById('messageBody').value;

            if (!body) {
                alert('Please enter a message');
                return;
            }

            const result = await postAPI('/api/teacher/send-message', {
                to: to,
                subject: subject,
                message: body
            });

            if (result.success) {
                alert(`✅ Message sent to ${to}!`);
                closeMessageModal();
                displayMessages();
            } else {
                alert('❌ Error sending message');
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
        document.getElementById('newMessageBtn')?.addEventListener('click', () => openMessageModal('Coordinator'));
        document.getElementById('toggleNotificationsBtn')?.addEventListener('click', () => alert('🔔 Notification settings saved!'));
        document.getElementById('viewScheduleBtn')?.addEventListener('click', () => alert('📅 Schedule view coming soon!'));
        document.getElementById('editAccountBtn')?.addEventListener('click', () => {
            const newPass = prompt('Enter new password:');
            if (newPass && newPass.length >= 6) alert('✅ Password updated!');
            else if (newPass) alert('Password must be at least 6 characters');
        });

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

        // Language
        let langIdx = 0, langs = ['English', 'العربية', 'Français'];
        document.getElementById('languageBtn')?.addEventListener('click', () => {
            langIdx = (langIdx + 1) % langs.length;
            document.getElementById('languageBtn').innerHTML = langs[langIdx];
            alert(`Language set to ${langs[langIdx]}`);
        });

        // Initial load
        loadClasses();
        loadTodayActivities();
        loadAssessments();
    </script>
</body>
</html>
