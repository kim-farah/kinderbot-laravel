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
        .section-details { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
        .section-detail { font-size: 14px; color: var(--gray); }
        .section-actions { display: flex; gap: 12px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-gray); }
        .settings-item {
            padding: 16px;
            border-bottom: 1px solid var(--border-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .settings-item:last-child { border-bottom: none; }
        .empty-state { text-align: center; padding: 40px; color: var(--gray); }
        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid var(--border-gray);
        }
        .student-item:last-child { border-bottom: none; }
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="top-bar">
            <div class="logo">Kinderbot CMS</div>
            <div class="user-info">
                <span>👤 Coordinator</span>
                <button class="logout-btn" onclick="location.href='{{ route('login') }}'">Logout</button>
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
                        <li data-page="activities">📝 Activities</li>
                        <li data-page="settings">⚙️ Settings</li>
                    </ul>
                </nav>
            </div>
            <div class="content">
                <div id="dashboardPage" class="page-content active">
                    <div class="section-header"><h2>Classes</h2><button class="btn-primary" id="newClassBtn">+ New Class</button></div>
                    <div id="classCardsContainer" class="class-cards"></div>
                    <div class="section-header"><h2>All Activities</h2><button class="btn-primary" id="newActivityBtn">+ New Activity</button></div>
                    <div id="allActivitiesContainer" class="data-table"></div>
                    <div class="section-header"><h2>Recent Activities Taught</h2></div>
                    <div id="recentActivitiesContainer" class="data-table"></div>
                </div>
                <div id="classesPage" class="page-content">
                    <div class="section-header"><h2>All Classes</h2><button class="btn-primary" id="newClassBtn2">+ New Class</button></div>
                    <div id="classesTableContainer" class="data-table"></div>
                </div>
                <div id="teachersPage" class="page-content">
                    <div class="section-header"><h2>Teachers</h2><button class="btn-primary" id="newTeacherBtn">+ New Teacher</button></div>
                    <div id="teachersTableContainer" class="data-table"></div>
                </div>
                <div id="parentsPage" class="page-content">
                    <div class="section-header"><h2>Parents</h2><button class="btn-primary" id="newParentBtn">+ New Parent</button></div>
                    <div id="parentsTableContainer" class="data-table"></div>
                </div>
                <div id="activitiesPage" class="page-content">
                    <div class="section-header"><h2>All Activities</h2><button class="btn-primary" id="newActivityBtn2">+ New Activity</button></div>
                    <div id="activitiesTableContainer" class="data-table"></div>
                </div>
                <div id="settingsPage" class="page-content">
                    <div class="section-header"><h2>Settings</h2></div>
                    <div class="data-table">
                        <div class="settings-item"><div><strong>School Year</strong><div style="font-size:13px;">Current academic year</div></div><div><button class="btn-small" id="schoolYearBtn">2024-2025</button></div></div>
                        <div class="settings-item"><div><strong>System Theme</strong><div style="font-size:13px;">Light / Dark mode</div></div><div><button class="btn-small" id="themeBtn">Light</button></div></div>
                        <div class="settings-item"><div><strong>Notifications</strong><div style="font-size:13px;">Email notifications</div></div><div><button class="btn-small" id="notificationsBtn">Enabled</button></div></div>
                        <div class="settings-item"><div><strong>Data Export</strong><div style="font-size:13px;">Export all data as JSON</div></div><div><button class="btn-small" id="exportBtn">Export</button></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sectionsModal" class="modal"><div class="modal-content"><div class="modal-header"><h3 id="modalClassName">Class Sections</h3><button class="modal-close" onclick="closeSectionsModal()">&times;</button></div><div class="modal-body" id="sectionsModalBody"></div></div></div>
    <div id="addSectionModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Add New Section</h3><button class="modal-close" onclick="closeAddSectionModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Section Name</label><input type="text" id="newSectionName" placeholder="e.g., A, B, Morning" class="form-input"></div><div class="form-group"><label>Teacher</label><select id="newSectionTeacher" class="form-select"></select></div><div class="form-group"><label>Max Students</label><input type="number" id="newSectionMaxStudents" value="25" class="form-input"></div><div class="form-group"><label>Schedule</label><input type="text" id="newSectionSchedule" placeholder="e.g., 9:00-10:30 AM" class="form-input"></div><div class="form-actions"><button class="btn-secondary" onclick="closeAddSectionModal()">Cancel</button><button class="btn-primary" onclick="saveNewSection()">Add Section</button></div></div></div></div>
    <div id="addStudentModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Add Student</h3><button class="modal-close" onclick="closeAddStudentModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Student Name</label><input type="text" id="newStudentName" class="form-input" placeholder="Full name"></div><div class="form-group"><label>Parent Name (optional)</label><input type="text" id="newStudentParent" class="form-input" placeholder="Parent name"></div><div class="form-actions"><button class="btn-secondary" onclick="closeAddStudentModal()">Cancel</button><button class="btn-primary" onclick="saveNewStudent()">Add Student</button></div></div></div></div>
    <div id="editClassModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Class</h3><button class="modal-close" onclick="closeEditClassModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Class Name</label><input type="text" id="editClassName" class="form-input"></div><div class="form-group"><label>Age Range</label><input type="text" id="editClassGrade" class="form-input"></div><div class="form-group"><label>Teacher</label><input type="text" id="editClassTeacher" class="form-input"></div><div class="form-actions"><button class="btn-secondary" onclick="closeEditClassModal()">Cancel</button><button class="btn-primary" onclick="saveEditedClass()">Save</button></div></div></div></div>
    <div id="editTeacherModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Teacher</h3><button class="modal-close" onclick="closeEditTeacherModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Name</label><input type="text" id="editTeacherName" class="form-input"></div><div class="form-group"><label>Email</label><input type="email" id="editTeacherEmail" class="form-input"></div><div class="form-group"><label>Phone</label><input type="text" id="editTeacherPhone" class="form-input"></div><div class="form-group"><label>Class</label><input type="text" id="editTeacherClass" class="form-input"></div><div class="form-actions"><button class="btn-secondary" onclick="closeEditTeacherModal()">Cancel</button><button class="btn-primary" onclick="saveEditedTeacher()">Save</button></div></div></div></div>
    <div id="editParentModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Parent</h3><button class="modal-close" onclick="closeEditParentModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Name</label><input type="text" id="editParentName" class="form-input"></div><div class="form-group"><label>Email</label><input type="email" id="editParentEmail" class="form-input"></div><div class="form-group"><label>Phone</label><input type="text" id="editParentPhone" class="form-input"></div><div class="form-group"><label>Child</label><input type="text" id="editParentChild" class="form-input"></div><div class="form-actions"><button class="btn-secondary" onclick="closeEditParentModal()">Cancel</button><button class="btn-primary" onclick="saveEditedParent()">Save</button></div></div></div></div>
    <div id="editActivityModal" class="modal"><div class="modal-content"><div class="modal-header"><h3>Edit Activity</h3><button class="modal-close" onclick="closeEditActivityModal()">&times;</button></div><div class="modal-body"><div class="form-group"><label>Title</label><input type="text" id="editActivityTitle" class="form-input"></div><div class="form-group"><label>Class</label><select id="editActivityClass" class="form-select"><option>KG1</option><option>KG2</option><option>KG3</option></select></div><div class="form-group"><label>Duration (min)</label><input type="number" id="editActivityDuration" class="form-input"></div><div class="form-group"><label>Difficulty</label><select id="editActivityDifficulty" class="form-select"><option>Easy</option><option>Medium</option><option>Hard</option></select></div><div class="form-group"><label>Status</label><select id="editActivityStatus" class="form-select"><option value="published">Published</option><option value="draft">Draft</option></select></div><div class="form-actions"><button class="btn-secondary" onclick="closeEditActivityModal()">Cancel</button><button class="btn-primary" onclick="saveEditedActivity()">Save</button></div></div></div></div>

    <script>
    // Get data from Laravel backend
    const classesData = @json($classes);
    const activitiesData = @json($activities);
    const teacherLogData = @json($teacherLog);

    // Global variables for sections
    let currentClassId = null;
    let currentSectionId = null;

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

    // Display classes from database with real student counts
function displayClassesDashboard() {
    const container = document.getElementById('classCardsContainer');
    if (!container) return;

    if (classesData.length === 0) {
        container.innerHTML = '<p style="text-align: center; padding: 40px;">No classes yet. Click "+ New Class" to create one.</p>';
        return;
    }

    // Fetch student counts for each class
    Promise.all(classesData.map(async (c) => {
        try {
            const response = await fetch(`/api/sections/${c.id}`);
            const sections = await response.json();

            // Count total students across all sections
            let totalStudents = 0;
            if (Array.isArray(sections)) {
                sections.forEach(section => {
                    totalStudents += section.students ? section.students.length : 0;
                });
            }
            return { ...c, totalStudents };
        } catch (error) {
            console.error('Error fetching sections for class:', c.id, error);
            return { ...c, totalStudents: 0 };
        }
    })).then(classesWithCounts => {
        container.innerHTML = classesWithCounts.map(c => `
            <div class="card">
                <h3>${c.name}</h3>
                <div class="grade">${c.grade_level == 0 ? 'Ages 3-4' : c.grade_level == 1 ? 'Ages 4-5' : 'Ages 5-6'}</div>
                <div class="stats">👥 ${c.totalStudents} students</div>
                <div class="teacher">👩‍🏫 ${c.teacher || 'Not assigned'}</div>
                <button class="btn-outline" onclick="viewSections(${c.id})" style="margin-top:12px;">View Sections</button>
            </div>
        `).join('');
    }).catch(error => {
        console.error('Error loading class data:', error);
        container.innerHTML = '<p style="text-align: center; padding: 40px;">Error loading classes. Please refresh the page.</p>';
    });
}

    // Display activities from database
    function displayAllActivities() {
        const container = document.getElementById('allActivitiesContainer');
        if (!container) return;

        if (activitiesData.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px;">No activities yet. Click "+ New Activity" to create one.</p>';
            return;
        }

        let html = '<table class="data-table">';
        html += '<thead> <tr><th>Activity</th><th>Class</th><th>Duration</th><th>Status</th></tr> </thead>';
        html += '<tbody>';
        activitiesData.forEach(a => {
            html += '<tr>';
            html += `<td><strong>${a.title}</strong></td>`;
            html += `<td>KG${a.class_id}</td>`;
            html += `<td>${a.estimated_duration || 30} min</td>`;
            html += `<td><span class="badge ${a.is_published ? 'published' : 'draft'}">${a.is_published ? 'Published' : 'Draft'}</span></td>`;
            html += '</tr>';
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // Display teacher log from database
    function displayTeacherLog() {
        const container = document.getElementById('recentActivitiesContainer');
        if (!container) return;

        if (teacherLogData.length === 0) {
            container.innerHTML = '<p style="text-align: center; padding: 40px;">No activities taught yet.</p>';
            return;
        }

        let html = '<table class="data-table">';
        html += '<thead> <tr><th>Activity</th><th>Class</th><th>Teacher</th><th>Duration</th><th>Time</th><tr> </thead>';
        html += '<tbody>';
        teacherLogData.forEach(l => {
            const r = getRelativeTime(l.timestamp);
            html += '<tr>';
            html += `<td><strong>${l.activity}</strong></td>`;
            html += `<td>${l.class}</td>`;
            html += `<td>👩‍🏫 ${l.teacher}</td>`;
            html += `<td>${l.duration}</td>`;
            html += `<td><span class="time-badge ${r.class}">${r.text}</span></td>`;
            html += '</tr>';
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // View Sections Modal
    function viewSections(classId) {
        currentClassId = classId;
        const classItem = classesData.find(c => c.id == classId);
        const className = classItem ? classItem.name : 'Class';
        document.getElementById('modalClassName').innerHTML = `${className} - Sections`;
        loadSectionsForClass(classId);
        document.getElementById('sectionsModal').style.display = 'flex';
    }

    function loadSectionsForClass(classId) {
        fetch(`/api/sections/${classId}/details`)
            .then(response => response.json())
            .then(data => {
                const body = document.getElementById('sectionsModalBody');
                if (data.length === 0) {
                    body.innerHTML = `<div class="empty-state"><p>No sections found.</p><button class="btn-primary" onclick="openAddSectionModal()">+ Add First Section</button></div>`;
                    return;
                }
                let html = '';
                data.forEach(section => {
                    html += `<div class="section-card">
                        <div style="display: flex; justify-content: space-between;">
                            <h4>Section ${section.section_name}</h4>
                            <div><button class="btn-small" onclick="editSection(${section.id})">✏️ Edit</button><button class="btn-small btn-danger" onclick="deleteSection(${section.id})">🗑️ Delete</button></div>
                        </div>
                        <div><strong>👩‍🏫 Teacher:</strong> ${section.teacher_name || 'Not assigned'}</div>
                        <div><strong>👥 Students:</strong> ${section.students.length} / ${section.max_students}</div>
                        <div><strong>Student List:</strong></div>
                        ${section.students.map(s => `<div style="display: flex; justify-content: space-between; padding: 5px;"><span>👦 ${s.full_name}</span><button class="btn-small btn-danger" onclick="removeStudent(${section.id}, ${s.id})">Remove</button></div>`).join('')}
                        <button class="add-student-btn" onclick="openAddStudentModal(${section.id})">+ Add Student</button>
                    </div>`;
                });
                html += `<button class="btn-primary" onclick="openAddSectionModal()" style="width:100%; margin-top:16px;">+ Add New Section</button>`;
                body.innerHTML = html;
            });
    }

    function openAddSectionModal() {
        fetch('/api/teachers/list')
            .then(response => response.json())
            .then(teachers => {
                let teacherOptions = '<option value="">Select Teacher</option>';
                teachers.forEach(t => { teacherOptions += `<option value="${t.id}">${t.full_name}</option>`; });
                document.getElementById('addSectionModal').querySelector('.modal-body').innerHTML = `
                    <div><label>Section Name</label><input type="text" id="newSectionName" class="form-input"></div>
                    <div><label>Teacher</label><select id="newSectionTeacher" class="form-select">${teacherOptions}</select></div>
                    <div><label>Max Students</label><input type="number" id="newSectionMaxStudents" value="25" class="form-input"></div>
                    <div class="form-actions"><button class="btn-secondary" onclick="closeAddSectionModal()">Cancel</button><button class="btn-primary" onclick="saveNewSection()">Add Section</button></div>
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
        }).then(response => response.json()).then(data => {
            if (data.success) { alert('✅ Section added!'); closeAddSectionModal(); loadSectionsForClass(currentClassId); }
            else { alert('❌ Error: ' + data.message); }
        });
    }

    function editSection(sectionId) {
        fetch(`/api/sections/${sectionId}`).then(r => r.json()).then(section => {
            const newName = prompt('New section name:', section.section_name);
            if (!newName) return;
            const newMax = prompt('Max students:', section.max_students);
            fetch('/api/teachers/list').then(r => r.json()).then(teachers => {
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
            fetch(`/api/sections/${sectionId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(r => r.json()).then(data => { if (data.success) { alert('✅ Section deleted!'); loadSectionsForClass(currentClassId); } });
        }
    }

    function openAddStudentModal(sectionId) {
        currentSectionId = sectionId;
        document.getElementById('addStudentModal').querySelector('.modal-body').innerHTML = `
            <div><label>Student Name</label><input type="text" id="newStudentName" class="form-input"></div>
            <div><label>Parent Name</label><input type="text" id="newStudentParent" class="form-input"></div>
            <div class="form-actions"><button class="btn-secondary" onclick="closeAddStudentModal()">Cancel</button><button class="btn-primary" onclick="saveNewStudent()">Add Student</button></div>
        `;
        document.getElementById('sectionsModal').style.display = 'none';
        document.getElementById('addStudentModal').style.display = 'flex';
    }

    function closeAddStudentModal() {
        document.getElementById('addStudentModal').style.display = 'none';
        if (currentClassId) { document.getElementById('sectionsModal').style.display = 'flex'; loadSectionsForClass(currentClassId); }
    }

    function saveNewStudent() {
    const studentName = document.getElementById('newStudentName').value;
    const parentName = document.getElementById('newStudentParent').value;

    if (!studentName) {
        alert('Please enter student name');
        return;
    }

    fetch('/api/sections/add-student', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            section_id: currentSectionId,
            student_name: studentName,
            parent_name: parentName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ Student "${studentName}" added successfully!`);
            closeAddStudentModal();
            // Reload sections to update the student count
            loadSectionsForClass(currentClassId);
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error adding student. Please try again.');
    });
}

    function removeStudent(sectionId, studentId) {
        if (confirm('Remove student?')) {
            fetch(`/api/sections/${sectionId}/student/${studentId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(r => r.json()).then(data => { if (data.success) { alert('✅ Student removed'); loadSectionsForClass(currentClassId); } });
        }
    }

    function closeSectionsModal() { document.getElementById('sectionsModal').style.display = 'none'; }

    // Sidebar navigation
    const sidebarItems = document.querySelectorAll('.sidebar nav li');
    const pages = {
        dashboard: document.getElementById('dashboardPage'),
        classes: document.getElementById('classesPage'),
        teachers: document.getElementById('teachersPage'),
        parents: document.getElementById('parentsPage'),
        activities: document.getElementById('activitiesPage'),
        settings: document.getElementById('settingsPage')
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
            if (pageName === 'activities') loadActivitiesTable();
        });
    });

    function loadClassesTable() {
        fetch('/api/classes').then(r => r.json()).then(data => {
            const container = document.getElementById('classesTableContainer');
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:40px;">No classes yet.</p>'; return; }
            let html = '<table class="data-table"><thead><tr><th>Name</th><th>Grade Level</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(c => {
                html += `<tr><td><strong>${c.name}</strong></td><td>${parseInt(c.grade_level)}</td><td>${c.is_active ? 'Active' : 'Inactive'}</td><td><button class="btn-small" onclick="editClass(${c.id}, '${c.name}', ${parseInt(c.grade_level)})">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteClass(${c.id}, '${c.name}')">🗑️ Delete</button></td></tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }

    function loadActivitiesTable() {
        fetch('/api/activities').then(r => r.json()).then(data => {
            const container = document.getElementById('activitiesTableContainer');
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:40px;">No activities yet.</p>'; return; }
            let html = '<table class="data-table"><thead><tr><th>Title</th><th>Class</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(a => {
                html += `<tr><td><strong>${a.title}</strong></td><td>${a.class_id}</td><td>${a.estimated_duration || 30} min</td><td>${a.is_published ? 'Published' : 'Draft'}</td><td><button class="btn-small" onclick="editActivity(${a.id})">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteActivity(${a.id}, '${a.title}')">🗑️ Delete</button></td></tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }

    function loadTeachersTable() {
        fetch('/api/teachers').then(r => r.json()).then(data => {
            const container = document.getElementById('teachersTableContainer');
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:40px;">No teachers yet.</p>'; return; }
            let html = '<table class="data-table"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(t => {
                html += `<tr><td>${t.full_name}</td><td>${t.email || '-'}</td><td>${t.phone || '-'}</td><td><button class="btn-small" onclick="editTeacher(${t.id}, '${t.full_name}', '${t.email || ''}', '${t.phone || ''}')">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteTeacher(${t.id}, '${t.full_name}')">🗑️ Delete</button></td></tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }

    function loadParentsTable() {
        fetch('/api/parents').then(r => r.json()).then(data => {
            const container = document.getElementById('parentsTableContainer');
            if (data.length === 0) { container.innerHTML = '<p style="text-align:center;padding:40px;">No parents yet.</p>'; return; }
            let html = '<table class="data-table"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead><tbody>';
            data.forEach(p => {
                html += `<tr><td>${p.full_name}</td><td>${p.email || '-'}</td><td>${p.phone || '-'}</td><td><button class="btn-small" onclick="editParent(${p.id}, '${p.full_name}', '${p.email || ''}', '${p.phone || ''}')">✏️ Edit</button> <button class="btn-small btn-danger" onclick="deleteParent(${p.id}, '${p.full_name}')">🗑️ Delete</button></td></tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        });
    }

    function deleteClass(id, name) { if (confirm(`Delete "${name}"?`)) { fetch(`/api/classes/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }
    function editClass(id, currentName, currentGradeLevel) { const newName = prompt('Class name:', currentName); if (!newName) return; const newGradeLevel = prompt('Grade level (0=KG1,1=KG2,2=KG3,3=Grade1):', currentGradeLevel); if (newGradeLevel === null) return; fetch(`/api/classes/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ name: newName, grade_level: parseInt(newGradeLevel) }) }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Updated'); location.reload(); } }); }
    function deleteActivity(id, title) { if (confirm(`Delete "${title}"?`)) { fetch(`/api/activities/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }
    function editActivity(id) { alert('Edit activity coming soon!'); }
    function deleteTeacher(id, name) { if (confirm(`Delete "${name}"?`)) { fetch(`/api/teachers/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }
    function editTeacher(id, currentName, currentEmail, currentPhone) { const newName = prompt('Name:', currentName); if (!newName) return; const newEmail = prompt('Email:', currentEmail); const newPhone = prompt('Phone:', currentPhone); fetch(`/api/teachers/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ full_name: newName, email: newEmail || '', phone: newPhone || '' }) }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Updated'); location.reload(); } }); }
    function deleteParent(id, name) { if (confirm(`Delete "${name}"?`)) { fetch(`/api/parents/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Deleted'); location.reload(); } }); } }
    function editParent(id, currentName, currentEmail, currentPhone) { const newName = prompt('Name:', currentName); if (!newName) return; const newEmail = prompt('Email:', currentEmail); const newPhone = prompt('Phone:', currentPhone); fetch(`/api/parents/${id}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ full_name: newName, email: newEmail || '', phone: newPhone || '' }) }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Updated'); location.reload(); } }); }

    function addNewClass() {
        const className = prompt('Class name:');
        if (!className) return;
        let gradeLevel = prompt('Grade level (0=KG1,1=KG2,2=KG3,3=Grade1):');
        if (gradeLevel === null) return;
        fetch('{{ route("classes.store") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ name: className, grade_level: parseInt(gradeLevel) }) }).then(r => r.json()).then(data => { if (data.success) { alert('✅ Class added!'); location.reload(); } });
    }

    document.getElementById('newClassBtn')?.addEventListener('click', addNewClass);
    document.getElementById('newClassBtn2')?.addEventListener('click', addNewClass);
    document.getElementById('newTeacherBtn')?.addEventListener('click', () => alert('Add teacher coming soon'));
    document.getElementById('newParentBtn')?.addEventListener('click', () => alert('Add parent coming soon'));
    document.getElementById('newActivityBtn')?.addEventListener('click', () => window.location.href = '{{ route("create-activity") }}');
    document.getElementById('newActivityBtn2')?.addEventListener('click', () => window.location.href = '{{ route("create-activity") }}');

    document.getElementById('schoolYearBtn')?.addEventListener('click', () => { const newYear = prompt('School year (YYYY-YYYY):', '2024-2025'); if (newYear) document.getElementById('schoolYearBtn').innerText = newYear; });
    document.getElementById('themeBtn')?.addEventListener('click', () => { document.body.classList.toggle('dark-mode'); document.getElementById('themeBtn').innerHTML = document.body.classList.contains('dark-mode') ? 'Dark Mode 🌙' : 'Light Mode ☀️'; localStorage.setItem('coordinator_theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light'); });
    document.getElementById('notificationsBtn')?.addEventListener('click', () => { const btn = document.getElementById('notificationsBtn'); btn.innerHTML = btn.innerHTML === 'Enabled ✓' ? 'Disabled ✗' : 'Enabled ✓'; alert('Notifications ' + (btn.innerHTML === 'Enabled ✓' ? 'enabled' : 'disabled')); });
    document.getElementById('exportBtn')?.addEventListener('click', () => { const data = { classes: classesData, activities: activitiesData, teacherLog: teacherLogData, exportDate: new Date().toISOString() }; const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' }); const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'kinderbot_export.json'; a.click(); alert('Data exported!'); });

    const savedTheme = localStorage.getItem('coordinator_theme');
    if (savedTheme === 'dark') { document.body.classList.add('dark-mode'); document.getElementById('themeBtn').innerHTML = 'Dark Mode 🌙'; }

    // Initialize dashboard
    displayClassesDashboard();
    displayAllActivities();
    displayTeacherLog();
</script>

</body>
</html>
