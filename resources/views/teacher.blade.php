<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <span>👤 Ms. Sara</span>
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
                    <div class="teacher-greeting"><h1>Welcome back, Ms. Sara! 👋</h1><p class="date" id="currentDate"></p></div>
                    <div class="section-header"><h2>My Classes</h2></div>
                    <div id="classesGrid" class="classes-grid"></div>
                    <div class="section-header"><h2>Today's Activities</h2></div>
                    <div id="todayActivities" class="activities-list"></div>
                    <div class="section-header"><h2>Recent Assessments</h2><button class="btn-primary" id="quickAssessBtn">+ Quick Assessment</button></div>
                    <div id="assessmentsTable" class="assessments-table"></div>
                </div>
                <div id="myclassesPage" class="page-content"><div class="section-header"><h2>My Classes</h2></div><div id="myClassesTable" class="data-table"></div></div>
                <div id="activitiesPage" class="page-content"><div class="section-header"><h2>All Activities</h2></div><div id="teacherActivitiesTable" class="data-table"></div></div>
                <div id="reportsPage" class="page-content"><div class="section-header"><h2>Student Progress Reports</h2><button class="btn-primary" id="exportReportBtn">📊 Export Report</button></div><div id="reportsContainer" class="data-table"></div></div>
                <div id="messagesPage" class="page-content"><div class="section-header"><h2>Messages</h2><button class="btn-primary" id="newMessageBtn">+ New Message</button></div><div id="messagesContainer" class="data-table"></div></div>
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

    <div id="classDetailsModal" class="modal"><div class="modal-content"><h3 id="classDetailsTitle">Class Details</h3><div id="classDetailsContent"></div><div class="modal-buttons"><button class="btn-cancel" onclick="closeClassModal()">Close</button></div></div></div>
    <div id="assessmentModal" class="modal"><div class="modal-content"><h3>Quick Assessment</h3><label>Student:</label><select id="modalStudent"><option value="">Select Student</option><option value="Sara Habchy">Sara Habchy</option><option value="Michael Fadel">Michael Fadel</option><option value="Jean Dagher">Jean Dagher</option><option value="Ahmad Hassan">Ahmad Hassan</option><option value="Leila Khoury">Leila Khoury</option></select><label>Activity:</label><select id="modalActivity"><option value="">Select Activity</option><option value="Build a Robot">Build a Robot</option><option value="Spinning Top">Spinning Top</option><option value="Direction Car">Direction Car</option><option value="See Saw">See Saw</option><option value="Car Launcher">Car Launcher</option></select><label>Rating:</label><div class="star-rating" id="starRating"><span class="star" data-value="1">★</span><span class="star" data-value="2">★</span><span class="star" data-value="3">★</span><span class="star" data-value="4">★</span><span class="star" data-value="5">★</span></div><label>Comments:</label><textarea id="modalComments" rows="3"></textarea><div class="modal-buttons"><button class="btn-cancel" onclick="closeAssessmentModal()">Cancel</button><button class="btn-save" onclick="saveAssessment()">Save</button></div></div></div>

    <script>
        document.getElementById('currentDate').innerHTML = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        const teacherClasses = [
            { id: 1, name: "KG1 - Section A", students: 18, time: "9:00 - 10:30 AM", nextActivity: "Car Launcher", teacher: "Ms. Sara", room: "Room 101", studentsList: ["Sara Habchy", "Michael Fadel", "Jean Dagher", "Ahmad Hassan", "Leila Khoury"] },
            { id: 2, name: "KG2 - Section A", students: 15, time: "10:30 - 12:00 PM", nextActivity: "Precision In Motion", teacher: "Ms. Sara", room: "Room 102", studentsList: ["Omar Ali", "Nour Fares", "Hadi Saleh", "Layla Karam", "Rami Khoury"] },
            { id: 3, name: "KG2 - Section B", students: 21, time: "1:30 - 3:00 PM", nextActivity: "Precision In Motion", teacher: "Ms. Sara", room: "Room 103", studentsList: ["Tarek Haddad", "Maya Nassar", "Samir Abi", "Dina Haddad", "Karim Fares"] }
        ];
        const todayActivities = [
            { id: 1, title: "Build a Robot", class: "KG1-A", time: "9:00 AM", duration: "20 min", activityKey: "BUILD A ROBOT" },
            { id: 2, title: "Precision In Motion", class: "KG2-B", time: "10:30 AM", duration: "20 min", activityKey: "PRECISION IN MOTION" },
            { id: 3, title: "Direction Car", class: "KG3-A", time: "1:00 PM", duration: "20 min", activityKey: "DIRECTION CAR" }
        ];

        function loadAssessments() {
            let a = localStorage.getItem('teacher_assessments');
            if (!a) a = JSON.stringify([{ id:1, student:"Sara Habchy", activity:"Direction Car", rating:4, comments:"", date:"2025-03-24" }, { id:2, student:"Michael Fadel", activity:"See Saw", rating:3, comments:"", date:"2025-03-24" }, { id:3, student:"Jean Dagher", activity:"Cinema", rating:4, comments:"", date:"2025-03-24" }]);
            return JSON.parse(a);
        }
        function saveAssessments(a) { localStorage.setItem('teacher_assessments', JSON.stringify(a)); }

        const sidebarItems = document.querySelectorAll('.sidebar nav li');
        const pages = { dashboard: document.getElementById('dashboardPage'), myclasses: document.getElementById('myclassesPage'), activities: document.getElementById('activitiesPage'), reports: document.getElementById('reportsPage'), messages: document.getElementById('messagesPage'), settings: document.getElementById('settingsPage') };
        sidebarItems.forEach(item => {
            item.addEventListener('click', () => {
                const pageName = item.getAttribute('data-page');
                sidebarItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                Object.values(pages).forEach(p => p?.classList.remove('active'));
                if(pages[pageName]) pages[pageName].classList.add('active');
                if(pageName === 'myclasses') displayMyClasses();
                if(pageName === 'activities') displayTeacherActivities();
                if(pageName === 'reports') displayReports();
                if(pageName === 'messages') displayMessages();
            });
        });

        function displayClasses() {
            document.getElementById('classesGrid').innerHTML = teacherClasses.map(cls => `
                <div class="class-card" onclick="viewClassDetails('${cls.name}', ${cls.id})">
                    <h3>${cls.name}</h3><div class="class-stats"><p>👥 ${cls.students} students</p><p>🕒 ${cls.time}</p></div>
                    <div class="next-activity">Next: ${cls.nextActivity}</div>
                    <button class="btn-view" onclick="event.stopPropagation(); viewClassDetails('${cls.name}', ${cls.id})">View Class</button>
                </div>
            `).join('');
        }
        function viewClassDetails(className, classId) {
            const c = teacherClasses.find(c => c.id === classId);
            if(!c) return;
            document.getElementById('classDetailsTitle').innerHTML = `${c.name} - Class Details`;
            document.getElementById('classDetailsContent').innerHTML = `
                <div class="class-detail"><strong>👩‍🏫 Teacher:</strong> ${c.teacher}</div><div class="class-detail"><strong>👥 Students:</strong> ${c.students} students</div>
                <div class="class-detail"><strong>🕒 Schedule:</strong> ${c.time}</div><div class="class-detail"><strong>📍 Room:</strong> ${c.room}</div>
                <div class="class-detail"><strong>📚 Next Activity:</strong> ${c.nextActivity}</div><div class="section-header" style="margin-top:20px;"><h3>Student List</h3></div>
                <div class="student-list">${c.studentsList.map(s => `<div class="student-item"><span>👦 ${s}</span><button class="btn-small" onclick="quickAssessStudent('${s}')">Quick Assess</button></div>`).join('')}</div>
            `;
            document.getElementById('classDetailsModal').style.display = 'flex';
        }
        function closeClassModal() { document.getElementById('classDetailsModal').style.display = 'none'; }
        function quickAssessStudent(studentName) { closeClassModal(); setTimeout(() => { document.getElementById('modalStudent').value = studentName; openAssessmentModal(); }, 200); }
        function displayActivities() {
            document.getElementById('todayActivities').innerHTML = todayActivities.map(a => `
                <div class="activity-item"><div class="activity-info"><h4>${a.title}</h4><div class="activity-meta"><span>📚 ${a.class}</span><span>⏰ ${a.time}</span><span>⏱️ ${a.duration}</span></div></div><button class="btn-start" onclick="startActivity('${a.activityKey}', '${a.class}')">Start Activity</button></div>
            `).join('');
        }
        function displayAssessments() {
            const a = loadAssessments();
            document.getElementById('assessmentsTable').innerHTML = ` <table><thead><tr><th>Student</th><th>Activity</th><th>Rating</th><th>Date</th><th>Actions</th></tr></thead><tbody>${a.map(a => `<tr><td>${a.student}</td><td>${a.activity}</td><td class="rating">${'★'.repeat(a.rating)}${'☆'.repeat(5-a.rating)}</td><td>${a.date}</td><td><button class="btn-edit" onclick="editAssessment(${a.id})">Edit</button></td></tr>`).join('')}</tbody></table>`;
        }
        function displayMyClasses() {
            document.getElementById('myClassesTable').innerHTML = `<table><thead><tr><th>Class</th><th>Students</th><th>Schedule</th><th>Next Activity</th><th>Actions</th></tr></thead><tbody>${teacherClasses.map(c => `<tr><td><strong>${c.name}</strong></td><td>${c.students}</td><td>${c.time}</td><td>${c.nextActivity}</td><td><button class="btn-small" onclick="viewClassDetails('${c.name}', ${c.id})">View</button></td></tr>`).join('')}</tbody></table>`;
        }
        function displayTeacherActivities() {
            let all = JSON.parse(localStorage.getItem('all_activities') || '[]');
            const container = document.getElementById('teacherActivitiesTable');
            if(all.length===0) { container.innerHTML = '<p style="text-align:center; padding:40px;">No activities available.</p>'; return; }
            container.innerHTML = `<table><thead><tr><th>Activity</th><th>Class</th><th>Duration</th><th>Difficulty</th><th>Actions</th></tr></thead><tbody>${all.map(a => `<tr><td><strong>${a.title}</strong></td><td>${a.class}</td><td>${a.duration} min</td><td>${a.difficulty}</td><td><button class="btn-small" onclick="startActivity('${a.title}', '${a.class}')">Start</button></td></tr>`).join('')}</tbody></table>`;
        }
        function displayReports() {
            const a = loadAssessments();
            const container = document.getElementById('reportsContainer');
            if(a.length===0) { container.innerHTML = '<p style="text-align:center; padding:40px;">No assessment data yet.</p>'; return; }
            container.innerHTML = `<table><thead><tr><th>Student</th><th>Activity</th><th>Rating</th><th>Comments</th><th>Date</th></tr></thead><tbody>${a.map(a => `<tr><td><strong>${a.student}</strong></td><td>${a.activity}</td><td>${'★'.repeat(a.rating)}</td><td>${a.comments || '-'}</td><td>${a.date}</td></tr>`).join('')}</tbody></table>`;
        }
        function displayMessages() {
            let m = localStorage.getItem('teacher_messages');
            if(!m) m = JSON.stringify([{ id:1, from:"Coordinator", message:"New activity added: Build a Robot", date:"Mar 22, 2026" }, { id:2, from:"Parent - Sara Habchy", message:"My child enjoyed the activity today!", date:"Mar 21, 2026" }]);
            m = JSON.parse(m);
            document.getElementById('messagesContainer').innerHTML = `<table><thead><tr><th>From</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead><tbody>${m.map(m => `<tr><td><strong>${m.from}</strong></td><td>${m.message}</td><td>${m.date}</td><td><button class="btn-small" onclick="replyMessage('${m.from}')">Reply</button></td></tr>`).join('')}</tbody></table>`;
        }

        function startActivity(activityName, className) {
            let log = JSON.parse(localStorage.getItem('teacher_activity_log') || '[]');
            log.unshift({ id: log.length+1, activity: activityName, class: className, teacher: "Ms. Sara", timestamp: new Date().toISOString(), duration: "20 min" });
            localStorage.setItem('teacher_activity_log', JSON.stringify(log));
            alert(`✅ Started "${activityName}" for ${className}`);
        }
        let currentEditId = null, selectedRating = 0;
        function openAssessmentModal() { selectedRating=0; updateStarDisplay(0); document.getElementById('assessmentModal').style.display='flex'; }
        function editAssessment(id) {
            const a = loadAssessments().find(a=>a.id===id);
            if(a) { currentEditId=id; document.getElementById('modalStudent').value=a.student; document.getElementById('modalActivity').value=a.activity; document.getElementById('modalComments').value=a.comments||''; selectedRating=a.rating; updateStarDisplay(a.rating); document.getElementById('assessmentModal').style.display='flex'; }
        }
        function updateStarDisplay(r) { document.querySelectorAll('#starRating .star').forEach((s,i)=>{ if(i<r) s.classList.add('selected'); else s.classList.remove('selected'); }); selectedRating=r; }
        function closeAssessmentModal() { document.getElementById('assessmentModal').style.display='none'; document.getElementById('modalComments').value=''; currentEditId=null; }
        function saveAssessment() {
            const student = document.getElementById('modalStudent').value, activity = document.getElementById('modalActivity').value, comments = document.getElementById('modalComments').value;
            if(!student || !activity || selectedRating===0) { alert('Please fill all fields'); return; }
            let a = loadAssessments();
            const today = new Date().toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
            if(currentEditId) { const i=a.findIndex(x=>x.id===currentEditId); if(i!==-1) a[i]={...a[i], student, activity, rating:selectedRating, comments, date:today}; }
            else { a.push({ id:a.length+1, student, activity, rating:selectedRating, comments, date:today }); }
            saveAssessments(a); displayAssessments(); displayReports(); closeAssessmentModal(); alert('✅ Assessment saved!');
        }
        function replyMessage(sender) { const r=prompt(`Reply to ${sender}:`); if(r) alert(`Message sent to ${sender}: "${r}"`); }

        let darkMode = localStorage.getItem('teacher_theme') === 'dark';
        function applyDarkMode() { document.body.classList.toggle('dark-mode', darkMode); document.getElementById('darkModeBtn').innerHTML = darkMode ? 'Dark Mode 🌙' : 'Light Mode ☀️'; }
        applyDarkMode();
        document.getElementById('toggleNotificationsBtn')?.addEventListener('click',()=>{ alert('🔔 Notification settings saved!'); });
        document.getElementById('viewScheduleBtn')?.addEventListener('click',()=>{
            const m = document.createElement('div'); m.className='modal'; m.style.display='flex';
            m.innerHTML = `<div class="modal-content"><h3>📅 Weekly Schedule</h3><div class="class-detail">Monday: KG1-A (9:00-10:30 AM)</div><div class="class-detail">Tuesday: KG2-A (10:30-12:00 PM)</div><div class="class-detail">Wednesday: KG2-B (1:30-3:00 PM)</div><div class="class-detail">Thursday: KG1-A (9:00-10:30 AM)</div><div class="class-detail">Friday: Planning & Meetings</div><div class="modal-buttons"><button class="btn-cancel" onclick="this.closest('.modal').remove()">Close</button></div></div>`;
            document.body.appendChild(m);
        });
        document.getElementById('editAccountBtn')?.addEventListener('click',()=>{ const p=prompt('Enter new password:'); if(p&&p.length>=6) alert('✅ Password updated!'); else if(p) alert('Password must be 6+ chars'); });
        document.getElementById('darkModeBtn')?.addEventListener('click',()=>{ darkMode=!darkMode; localStorage.setItem('teacher_theme',darkMode?'dark':'light'); applyDarkMode(); alert(`🌙 Theme changed to ${darkMode?'Dark':'Light'} mode.`); });
        let langIdx=0, langs=['English','العربية','Français'];
        document.getElementById('languageBtn')?.addEventListener('click',()=>{ langIdx=(langIdx+1)%langs.length; document.getElementById('languageBtn').innerHTML=langs[langIdx]; alert(`Language set to ${langs[langIdx]}`); });
        document.getElementById('quickAssessBtn')?.addEventListener('click',openAssessmentModal);
        document.getElementById('exportReportBtn')?.addEventListener('click',()=>alert('📊 Report exported as CSV!'));
        document.getElementById('newMessageBtn')?.addEventListener('click',()=>{ const m=prompt('Message to parents:'); if(m) alert(`Message sent: "${m}"`); });

        document.querySelectorAll('#starRating .star').forEach(s=>{ s.addEventListener('click',()=>updateStarDisplay(parseInt(s.getAttribute('data-value')))); });

        displayClasses(); displayActivities(); displayAssessments();
    </script>
</body>
</html>
