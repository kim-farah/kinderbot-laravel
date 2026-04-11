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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="top-bar">
            <div class="logo">👨‍👩‍👧 Kinderbot Parent</div>
            <div class="user-info">
                <span>👤 Parent Name</span>
                <button class="logout-btn" onclick="location.href='{{ route('login') }}'">Logout</button>
            </div>
        </div>
        <div class="main-layout">
            <div class="sidebar">
                <nav>
                    <ul>
                        <li class="active" data-page="dashboard">📊 Dashboard</li>
                        <li data-page="mychild">👦 My Child</li>
                        <li data-page="activities">📝 Activities</li>
                        <li data-page="messages">💬 Messages</li>
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
                        <h2 id="childName">Elie Nassour</h2>
                        <div id="childClass">KG1 - Section A</div>
                        <div class="progress-section">
                            <div class="progress-label">
                                <span>📊 Overall Progress</span>
                                <span id="progressPercent">65%</span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" id="progressFill" style="width:65%"></div>
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
                        <div class="stat-card">
                            <div class="stat-number" id="notesCount">0</div>
                            <div class="stat-label">Notes from Teacher</div>
                        </div>
                    </div>
                    <div class="section-header">
                        <h2>Recent Activities</h2>
                    </div>
                    <div id="activitiesTable" class="data-table"></div>
                    <div class="section-header">
                        <h2>Notes from Teacher</h2>
                    </div>
                    <div id="notesList" class="notes-list"></div>
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
                        <button class="btn-primary" id="newMessageBtn">+ New Message</button>
                    </div>
                    <div id="messagesContainer" class="data-table"></div>
                </div>

                <div id="settingsPage" class="page-content">
                    <div class="section-header"><h2>Settings</h2></div>
                    <div class="data-table">
                        <div class="settings-item">
                            <div><strong>🔔 Notification Preferences</strong><div style="font-size:13px;color:gray;">Email notifications for updates</div></div>
                            <div><button class="btn-small" id="notificationsBtn">Enabled</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>🌙 Dark Mode</strong><div style="font-size:13px;color:gray;">Switch theme preference</div></div>
                            <div><button class="btn-small" id="darkModeBtn">Light Mode</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>🌐 Language</strong><div style="font-size:13px;color:gray;">Choose your preferred language</div></div>
                            <div><button class="btn-small" id="languageBtn">English</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>🔒 Privacy Settings</strong><div style="font-size:13px;color:gray;">Manage data sharing preferences</div></div>
                            <div><button class="btn-small" id="privacyBtn">Manage</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>📧 Email Notifications</strong><div style="font-size:13px;color:gray;">Receive activity reports via email</div></div>
                            <div><button class="btn-small" id="emailNotifBtn">Weekly Digest</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>🔑 Change Password</strong><div style="font-size:13px;color:gray;">Update your account password</div></div>
                            <div><button class="btn-small" id="changePasswordBtn">Change</button></div>
                        </div>
                        <div class="settings-item">
                            <div><strong>🗑️ Delete Account</strong><div style="font-size:13px;color:gray;">Permanently delete your account</div></div>
                            <div><button class="btn-small" id="deleteAccountBtn" style="background:#dc3545;color:white;">Delete</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <h3>Send Message</h3>
            <div class="form-group">
                <label>To:</label>
                <input type="text" id="messageTo" value="Teacher" class="form-input" readonly>
            </div>
            <div class="form-group">
                <label>Subject:</label>
                <input type="text" id="messageSubject" class="form-input">
            </div>
            <div class="form-group">
                <label>Message:</label>
                <textarea id="messageBody" rows="4" class="form-input"></textarea>
            </div>
            <div class="modal-buttons">
                <button class="btn-small" onclick="closeMessageModal()">Cancel</button>
                <button class="btn-primary" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>Change Password</h3>
            <div class="form-group">
                <label>Current Password:</label>
                <input type="password" id="currentPassword" class="form-input">
            </div>
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" id="newPassword" class="form-input">
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" id="confirmPassword" class="form-input">
            </div>
            <div class="modal-buttons">
                <button class="btn-small" onclick="closePasswordModal()">Cancel</button>
                <button class="btn-primary" onclick="changePassword()">Update</button>
            </div>
        </div>
    </div>

    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <h3>Privacy Settings</h3>
            <div class="form-group">
                <label><input type="checkbox" id="shareData"> Share data for analytics</label>
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="shareProgress"> Share progress with school</label>
            </div>
            <div class="modal-buttons">
                <button class="btn-small" onclick="closePrivacyModal()">Cancel</button>
                <button class="btn-primary" onclick="savePrivacySettings()">Save</button>
            </div>
        </div>
    </div>

    <script>
        let activities = [
            { id: 1, activity: "See Saw", class: "KG1", date: "Mar 25, 2026", status: "in_progress" },
            { id: 2, activity: "Direction Car", class: "KG1", date: "Mar 5, 2026", status: "completed" },
            { id: 3, activity: "Spinning Top", class: "KG1", date: "Feb 27, 2026", status: "completed" }
        ];

        let notes = [
            { id: 1, teacher: "Ms. Sara", date: "Mar 5, 2026", message: "Elie did great with building and directing the car today! He identified all directions possible." },
            { id: 2, teacher: "Ms. Sara", date: "Feb 27, 2026", message: "Please practice building the spinner at home this weekend." }
        ];

        let messages = [
            { id: 1, from: "Ms. Sara", message: "Your child did great with the robot activity today!", date: "Mar 22, 2026" },
            { id: 2, from: "Coordinator", message: "Parent-teacher meeting scheduled for April 5th.", date: "Mar 20, 2026" }
        ];

        let childData = { name: "Elie Nassour", class: "KG1 - Section A", progress: 65 };

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
        function displayStats() {
            const completed = activities.filter(a => a.status === 'completed').length;
            document.getElementById('completedCount').innerHTML = completed;
            document.getElementById('avgRating').innerHTML = '4.2';
            document.getElementById('notesCount').innerHTML = notes.length;
        }

        function displayActivitiesTable() {
            const container = document.getElementById('activitiesTable');
            if (!container) return;

            if (activities.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px;">No activities yet.</p>';
                return;
            }

            let html = '<table class="data-table">';
            html += '<thead>\
                        <tr>\
                            <th>Activity</th>\
                            <th>Class</th>\
                            <th>Date</th>\
                            <th>Status</th>\
                        </tr>\
                    </thead>';
            html += '<tbody>';

            activities.forEach(a => {
                const statusClass = a.status === 'completed' ? 'status-completed' : 'status-progress';
                const statusText = a.status === 'completed' ? '✅ Completed' : '🔄 In Progress';
                html += '<tr>';
                html += `<td><strong>${a.activity}</strong></td>`;
                html += `<td>${a.class}</td>`;
                html += `<td>${a.date}</td>`;
                html += `<td class="${statusClass}">${statusText}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function displayNotes() {
            const container = document.getElementById('notesList');
            if (!container) return;

            if (notes.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px;">No notes from teacher yet.</p>';
                return;
            }

            container.innerHTML = notes.map(n => `
                <div class="note-card">
                    <div class="note-header">
                        <span class="note-teacher">👩‍🏫 ${n.teacher}</span>
                        <span class="note-date">${n.date}</span>
                    </div>
                    <div class="note-message">"${n.message}"</div>
                </div>
            `).join('');
        }

        function displayChildProfileFull() {
            const container = document.getElementById('childProfileFull');
            const completed = activities.filter(a => a.status === 'completed').length;

            let html = '<table class="data-table">';
            html += '<tbody>';
            html += `<tr><td style="width:150px"><strong>Name</strong></td><td>${childData.name}</td></tr>`;
            html += `<tr><td><strong>Class</strong></td><td>${childData.class}</td></tr>`;
            html += `<tr><td><strong>Overall Progress</strong></td><td>${childData.progress}% <div style="background:#e0e0e0;border-radius:10px;height:6px;margin-top:5px;"><div style="background:var(--orange);width:${childData.progress}%;height:6px;border-radius:10px;"></div></div></td></tr>`;
            html += `<tr><td><strong>Completed Activities</strong></td><td>${completed}</td></tr>`;
            html += `<tr><td><strong>Teacher</strong></td><td>Ms. Sara</td></tr>`;
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function displayAllActivities() {
            const container = document.getElementById('allActivitiesTable');
            if (!container) return;

            if (activities.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px;">No activities yet.</p>';
                return;
            }

            let html = '<table class="data-table">';
            html += '<thead><tr><th>Activity</th><th>Date</th><th>Status</th></tr></thead>';
            html += '<tbody>';

            activities.forEach(a => {
                const statusClass = a.status === 'completed' ? 'status-completed' : 'status-progress';
                const statusText = a.status === 'completed' ? '✅ Completed' : '🔄 In Progress';
                html += '<tr>';
                html += `<td><strong>${a.activity}</strong></td>`;
                html += `<td>${a.date}</td>`;
                html += `<td class="${statusClass}">${statusText}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        function displayMessages() {
            const container = document.getElementById('messagesContainer');
            if (!container) return;

            if (messages.length === 0) {
                container.innerHTML = '<p style="text-align: center; padding: 40px;">No messages yet.</p>';
                return;
            }

            let html = '<table class="data-table">';
            html += '<thead><tr><th>From</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>';
            html += '<tbody>';

            messages.forEach(m => {
                html += '<tr>';
                html += `<td><strong>${m.from}</strong></td>`;
                html += `<td>${m.message}</td>`;
                html += `<td>${m.date}</td>`;
                html += `<td><button class="btn-small" onclick="openMessageModal('${m.from}')">Reply</button></td>`;
                html += '</tr>';
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }
        let currentReplyTo = '';

        function openMessageModal(to) {
            currentReplyTo = to;
            document.getElementById('messageTo').value = to;
            document.getElementById('messageSubject').value = '';
            document.getElementById('messageBody').value = '';
            document.getElementById('messageModal').style.display = 'flex';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        function sendMessage() {
            const subject = document.getElementById('messageSubject').value;
            const body = document.getElementById('messageBody').value;
            if (!body) {
                alert('Please enter a message');
                return;
            }
            messages.unshift({
                id: messages.length + 1,
                from: "You",
                message: body,
                date: new Date().toLocaleDateString()
            });
            displayMessages();
            closeMessageModal();
            alert(`✅ Message sent to ${currentReplyTo}!\n\nSubject: ${subject || 'No subject'}\nMessage: ${body}`);
        }

        let darkMode = localStorage.getItem('parent_theme') === 'dark';

        function applyDarkMode() {
            document.body.classList.toggle('dark-mode', darkMode);
            document.getElementById('darkModeBtn').innerHTML = darkMode ? 'Dark Mode 🌙' : 'Light Mode ☀️';
        }
        applyDarkMode();

        document.getElementById('notificationsBtn')?.addEventListener('click', () => {
            alert('🔔 Notification preferences saved!');
        });

        document.getElementById('darkModeBtn')?.addEventListener('click', () => {
            darkMode = !darkMode;
            localStorage.setItem('parent_theme', darkMode ? 'dark' : 'light');
            applyDarkMode();
            alert(`Theme changed to ${darkMode ? 'Dark' : 'Light'} mode.`);
        });

        let langIdx = 0;
        const languages = ['English', 'العربية', 'Français'];
        document.getElementById('languageBtn')?.addEventListener('click', () => {
            langIdx = (langIdx + 1) % languages.length;
            document.getElementById('languageBtn').innerHTML = languages[langIdx];
            let message = '';
            if (languages[langIdx] === 'English') message = 'Language set to English';
            else if (languages[langIdx] === 'العربية') message = 'تم تغيير اللغة إلى العربية';
            else message = 'Langue changée en Français';
            alert(message);
        });

        document.getElementById('privacyBtn')?.addEventListener('click', () => {
            document.getElementById('privacyModal').style.display = 'flex';
        });

        function closePrivacyModal() {
            document.getElementById('privacyModal').style.display = 'none';
        }

        function savePrivacySettings() {
            const shareData = document.getElementById('shareData').checked;
            const shareProgress = document.getElementById('shareProgress').checked;
            alert(`🔒 Privacy settings saved!\n\nShare analytics: ${shareData ? 'Yes' : 'No'}\nShare progress: ${shareProgress ? 'Yes' : 'No'}`);
            closePrivacyModal();
        }

        let emailIdx = 0;
        const emailOptions = ['Weekly Digest', 'Daily Updates', 'Real-time', 'Never'];
        document.getElementById('emailNotifBtn')?.addEventListener('click', () => {
            emailIdx = (emailIdx + 1) % emailOptions.length;
            document.getElementById('emailNotifBtn').innerHTML = emailOptions[emailIdx];
            alert(`📧 Email notification frequency set to: ${emailOptions[emailIdx]}`);
        });

        document.getElementById('changePasswordBtn')?.addEventListener('click', () => {
            document.getElementById('passwordModal').style.display = 'flex';
        });

        function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
        }

        function changePassword() {
            const current = document.getElementById('currentPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (!current || !newPass || !confirm) {
                alert('Please fill all fields');
                return;
            }
            if (newPass !== confirm) {
                alert('New passwords do not match');
                return;
            }
            if (newPass.length < 6) {
                alert('Password must be at least 6 characters');
                return;
            }
            alert('✅ Password changed successfully!');
            closePasswordModal();
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        }

        document.getElementById('deleteAccountBtn')?.addEventListener('click', () => {
            const confirm1 = confirm('⚠️ WARNING: This will permanently delete your account and all data.\n\nAre you sure you want to continue?');
            if (!confirm1) return;
            const confirm2 = confirm('❗ LAST CHANCE: This action cannot be undone.\n\nClick OK to confirm deletion.');
            if (!confirm2) return;
            const confirmText = prompt('Type "DELETE" to permanently delete your account:');
            if (confirmText === 'DELETE') {
                alert('🗑️ Your account has been scheduled for deletion. You will be logged out.');
                localStorage.clear();
                window.location.href = 'login.html';
            } else {
                alert('Account deletion cancelled.');
            }
        });

        document.getElementById('newMessageBtn')?.addEventListener('click', () => {
            openMessageModal('Teacher');
        });
        displayStats();
        displayActivitiesTable();
        displayNotes();
    </script>
</body>
</html>
