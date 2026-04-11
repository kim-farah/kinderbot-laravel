<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Activity - Kinderbot CMS</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: var(--light-gray);
            height: 100vh;
            overflow: hidden;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

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

        .logo {
            font-size: 20px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

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

        .logout-btn:hover {
            background: var(--orange);
            border-color: var(--orange);
        }

        .main-layout {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 260px;
            background: var(--white);
            border-right: 1px solid var(--border-gray);
            padding: 24px 0;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav li {
            padding: 12px 24px;
            cursor: pointer;
            color: var(--gray);
            font-size: 15px;
            transition: all 0.2s;
        }

        .sidebar nav li:hover {
            background: var(--light-gray);
            color: var(--dark-blue);
        }

        .sidebar nav li.active {
            background: var(--orange);
            color: var(--white);
        }

        .content {
            flex: 1;
            padding: 24px 32px;
            overflow-y: auto;
        }

        .form-container {
            max-width: 800px;
            background: var(--white);
            border-radius: 16px;
            padding: 32px;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .form-container h1 {
            font-size: 28px;
            color: var(--dark-blue);
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: var(--gray);
            margin-bottom: 32px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--dark-blue);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-gray);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--orange);
        }

        .form-textarea {
            resize: vertical;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-group.half {
            flex: 1;
        }

        .radio-group {
            display: flex;
            gap: 24px;
            padding: 8px 0;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            cursor: pointer;
            margin-bottom: 0;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            cursor: pointer;
            margin-bottom: 0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-gray);
        }

        .btn-primary {
            background: var(--orange);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background: var(--white);
            color: var(--gray);
            border: 1px solid var(--border-gray);
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: var(--light-gray);
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .form-container {
                padding: 20px;
            }
            .form-row {
                flex-direction: column;
            }
            .content {
                padding: 20px;
            }
        }

        body.dark-mode {
            background: #1a1a2e;
        }
        body.dark-mode .sidebar {
            background: #16213e;
            border-right-color: #2a2a4a;
        }
        body.dark-mode .sidebar nav li {
            color: #aaa;
        }
        body.dark-mode .sidebar nav li:hover {
            background: #1f2a4a;
            color: white;
        }
        body.dark-mode .content {
            background: #1a1a2e;
        }
        body.dark-mode .form-container {
            background: #16213e;
            border-color: #2a2a4a;
        }
        body.dark-mode .form-container h1 {
            color: var(--orange);
        }
        body.dark-mode .form-group label {
            color: #ddd;
        }
        body.dark-mode .form-input,
        body.dark-mode .form-select,
        body.dark-mode .form-textarea {
            background: #0f0f2a;
            border-color: #2a2a4a;
            color: white;
        }
        body.dark-mode .btn-secondary {
            background: #0f0f2a;
            border-color: #2a2a4a;
            color: #ddd;
        }
        body.dark-mode .btn-secondary:hover {
            background: #1f2a4a;
        }
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
                        <li onclick="window.location.href='{{ route('coordinator') }}'">📊 Dashboard</li>
                        <li onclick="window.location.href='{{ route('coordinator') }}'">📚 Classes</li>
                        <li>👥 Teachers</li>
                        <li>👨‍👩‍👧 Parents</li>
                        <li class="active" onclick="window.location.href='{{ route('coordinator') }}'">📝 Activities</li>
                        <li>⚙️ Settings</li>
                    </ul>
                </nav>
            </div>

            <div class="content">
                <div class="form-container">
                    <h1>Create New Activity</h1>
                    <p class="form-subtitle">Fill in the details below to create a new activity <span style="color: red;">* = required</span></p>

                    @if(session('success'))
                        <div class="success-message" style="display: block;">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('activities.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Activity Title <span style="color: red;">*</span></label>
                            <input type="text" id="activityTitle" name="title" placeholder="e.g., Build a Robot" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label>Class <span style="color: red;">*</span></label>
                            <select id="activityClass" name="class" class="form-select" required>
                                <option value="">-- Select a class --</option>
                                <option value="KG1">KG1</option>
                                <option value="KG2">KG2</option>
                                <option value="KG3">KG3</option>
                                <option value="Grade 1">Grade 1</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Overview <span style="color: red;">*</span></label>
                            <textarea id="activityOverview" name="overview" rows="4" class="form-textarea" placeholder="e.g., Ro-Din will build his first robot using LEGO parts..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Skills and competencies <span style="color: red;">*</span></label>
                            <textarea id="activitySkills" name="skills" rows="4" class="form-textarea" placeholder="e.g., Fine motor skills&#10;Hand-eye coordination&#10;Teamwork&#10;Creative thinking" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Materials Needed <span style="color: red;">*</span></label>
                            <textarea id="activityMaterials" name="materials" rows="2" class="form-textarea" placeholder="e.g., LEGO kit • Coloring pencils • Split pins • Scissors" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Step-by-Step Instructions <span style="color: red;">*</span></label>
                            <textarea id="activityInstructions" name="instructions" rows="5" class="form-textarea" placeholder="Step 1: Build the robot&#10;Step 2: Hold the handle in the back&#10;Step 3: Turn the handle&#10;Step 4: Manipulate and interpret" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label>Estimated Duration (minutes) <span style="color: red;">*</span></label>
                                <input type="number" id="activityDuration" name="duration" placeholder="e.g., 30" class="form-input" required>
                            </div>
                            <div class="form-group half">
                                <label>Difficulty Level <span style="color: red;">*</span></label>
                                <div class="radio-group">
                                    <label><input type="radio" name="difficulty" value="Easy" required> Easy</label>
                                    <label><input type="radio" name="difficulty" value="Medium"> Medium</label>
                                    <label><input type="radio" name="difficulty" value="Hard"> Hard</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group checkbox-group">
                            <label>
                                <input type="checkbox" name="publish" id="publishImmediately" checked> Publish immediately
                            </label>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('coordinator') }}'">Cancel</button>
                            <button type="submit" class="btn-primary">Create Activity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const savedTheme = localStorage.getItem('coordinator_theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>
