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
            /*overflow: hidden;*/
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
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 8px; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .btn-primary { background: #FF6B35; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
        .btn-secondary { background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
        .step-item { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        h3 { margin: 20px 0 15px 0; color: #1E3A5F; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>{{ isset($is_edit) ? 'Edit Activity' : 'Create New Activity' }}</h1>
        <p class="form-subtitle">Fill in the details below to create a new activity <span style="color: red;">* = required</span></p>

<form method="POST" action="{{ isset($is_edit) ? route('activities.update', $activity->id) : route('activities.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($is_edit))
        @method('PUT')
    @endif

            <!-- ========== BASIC INFORMATION ========== -->
            <h3>Basic Information</h3>

            <div class="form-group">
                <label>Activity Title <span style="color: red;">*</span></label>
                <input type="text" name="title" class="form-input" value="{{ old('title', $activity->title ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>Class <span style="color: red;">*</span></label>
                <select name="class" class="form-select" required>
    <option value="">-- Select a class --</option>
    @foreach($classes as $class)
        <option value="{{ $class->name }}" {{ (isset($activity) && $activity->class_id == $class->id) ? 'selected' : '' }}>
            {{ $class->name }}
        </option>
    @endforeach
</select>
            </div>

            <div class="form-group">
                <label>Objective <span style="color: red;">*</span></label>
                <textarea name="objective" rows="3" class="form-textarea" required>{{ old('objective', $activity->objective ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Overview <span style="color: red;">*</span></label>
                <textarea name="overview" rows="4" class="form-textarea" required>{{ old('overview', $activity->overview ?? '') }}</textarea>

            </div>

            <div class="form-group">
    <label>Skills & Competencies <span style="color: red;">*</span></label>
    <textarea name="skills" rows="3" class="form-textarea" placeholder="Fine motor skills&#10;Hand-eye coordination&#10;Teamwork&#10;Creative thinking" required>{{ old('skills', isset($activity) ? str_replace(',', "\n", $activity->skills_competencies) : '') }}</textarea>
    <small>Enter one competency per line</small>
</div>

<div class="form-group">
    <label>Materials Needed <span style="color: red;">*</span></label>
    <textarea name="materials" rows="3" class="form-textarea" placeholder="LEGO kit&#10;Coloring pencils&#10;Scissors" required>{{ old('materials', isset($activity) ? str_replace(',', "\n", $activity->materials) : '') }}</textarea>
    <small>Enter one material per line</small>
</div>

            <!-- ========== COMMENTS ========== -->
            <h3>Comments</h3>

            <div class="form-group">
                <label>Rodin Comment</label>
                <textarea name="rodin_comment" rows="2" class="form-textarea">{{ old('rodin_comment', $activity->rodin_comment ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Activity Comment</label>
                <textarea name="activity_comment" rows="2" class="form-textarea">{{ old('activity_comment', $activity->activity_comment ?? '') }}</textarea>

            </div>

            <div class="form-group">
                <label>Feedback Comment</label>
                <textarea name="feedback_comment" rows="2" class="form-textarea">{{ old('feedback_comment', $activity->feedback_comment ?? '') }}</textarea>
            </div>

<!-- ========== RESOURCES (IMAGES) ========== -->
<h3>Activity Images</h3>
<p><small>Current images will be kept. Only upload new images if you want to replace them.</small></p>

<div class="form-group">
    <label>Hero Image</label>
    <input type="file" name="resources[]" class="form-input" accept="image/*" {{ isset($is_edit) ? '' : 'required' }}>
    <small>Main activity image</small>
</div>

<div class="form-group">
    <label>Switch Image 1</label>
    <input type="file" name="resources[]" class="form-input" accept="image/*">
</div>

<div class="form-group">
    <label>Switch Image 2</label>
    <input type="file" name="resources[]" class="form-input" accept="image/*">
</div>


<!-- ========== STEPS WITH IMAGES ========== -->
<h3>Activity Steps</h3>
<div id="steps-container">
    @if(isset($steps) && count($steps) > 0)
        @foreach($steps as $index => $step)
            <div class="step-item">
                <div class="form-group">
                    <label>Step {{ $index + 1 }} Description <span style="color: red;">*</span></label>
                    <textarea name="step_description[]" class="form-textarea" rows="2" required>{{ $step->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Step {{ $index + 1 }} Image</label>
                    @if($step->image_path)
                        <div style="margin-bottom: 10px;">
                            <img src="/storage/{{ $step->image_path }}" style="max-width: 150px; border-radius: 8px; border: 1px solid #ddd;">
                            <br>
                        </div>
                    @endif
                    <input type="file" name="step_images[]" class="form-input" accept="image/*">
                </div>
            </div>
        @endforeach
    @else
        <div class="step-item">
            <div class="form-group">
                <label>Step 1 Description <span style="color: red;">*</span></label>
                <textarea name="step_description[]" class="form-textarea" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label>Step 1 Image</label>
                <input type="file" name="step_images[]" class="form-input" accept="image/*">
            </div>
        </div>
    @endif
</div>

<button type="button" id="add-step-btn" class="btn-secondary">+ Add Another Step</button>
            <!-- ========== PUBLISH ========== -->
            <div class="form-group" style="margin-top: 20px;">
                <label>
                    <input type="checkbox" name="is_published" checked> Publish immediately
                </label>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('coordinator') }}'">Cancel</button>
                <button type="submit" class="btn-primary">{{ isset($is_edit) ? 'Update Activity' : 'Create Activity' }}</button>
            </div>
        </form>
    </div>

    <script>
        // Add more steps dynamically
        document.getElementById('add-step-btn').addEventListener('click', function() {
            const container = document.getElementById('steps-container');
            const stepCount = container.children.length + 1;
            const newStep = document.createElement('div');
            newStep.className = 'step-item';
            newStep.innerHTML = `
                <div class="form-group">
                    <label>Step ${stepCount} Description</label>
                    <textarea name="step_description[]" class="form-textarea" rows="2" required></textarea>
                </div>
                <div class="form-group">
                    <label>Step ${stepCount} Image</label>
                    <input type="file" name="step_images[]" class="form-input" accept="image/*">
                </div>
            `;
            container.appendChild(newStep);
        });
    </script>
</body>
</html>
