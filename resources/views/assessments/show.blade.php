<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<style>
    :root {
    --dark-blue:#1E3A5F;
    --orange:#FF6B35;
    --white:#FFFFFF;
    --light-gray:#F8F9FA;
    --gray:#6C757D;
    --border-gray:#E9ECEF;
    --success:#28a745;
    --danger:#dc3545;
    --warning:#ffc107;
}

/* PAGE */
body{
    margin:0;
    font-family:Arial;
    background:var(--light-gray);
    color:var(--dark-blue);
}

/* HEADER */
.toolbar{
    background:var(--dark-blue);
    color:white;
    padding:16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.toolbar .btn{
    background:var(--orange);
    padding:10px 14px;
    border-radius:10px;
    color:white;
    text-decoration:none;
    font-weight:bold;
}

:root {
    --dark-blue: #1E3A5F;
    --orange: #FF6B35;
    --white: #FFFFFF;
    --light-gray: #F8F9FA;
    --gray: #6C757D;
    --border-gray: #E9ECEF;
    --success: #28a745;
}

/* PAGE */
body {
    background: var(--light-gray);
}

/* WRAPPER animation */
.assessment-wrapper {
    padding: 30px;
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* TITLE */
.assessment-title {
    font-size: 28px;
    font-weight: 600;
    color: var(--dark-blue);
    margin-bottom: 20px;
    animation: slideDown 0.5s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* TABLE CONTAINER (card look) */
.table-container {
    background: var(--white);
    border-radius: 16px;
    overflow-x: auto;
    box-shadow: 0 10px 25px rgba(30, 58, 95, 0.08);
    animation: fadeIn 0.8s ease;
}

/* TABLE */
.assessment-table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
.assessment-table th {
    background: var(--dark-blue);
    color: white;
    padding: 14px;
    font-size: 14px;
}

/* CELLS */
.assessment-table td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid var(--border-gray);
}

/* ROW HOVER */
.assessment-table tr {
    transition: 0.2s;
}

.assessment-table tr:hover {
    background: rgba(30, 58, 95, 0.04);
    transform: scale(1.01);
}

/* STUDENT NAME */
.student-name {
    font-weight: 600;
    color: var(--dark-blue);
}

/* =========================
   RATING SYSTEM (ANIMATED)
========================= */

.rating-group {
    display: flex;
    justify-content: center;
    gap: 6px;
}

.rating-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid var(--border-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    color: var(--gray);
    transition: all 0.25s ease;
    transform: scale(1);
}

/* hover bounce */
.rating-circle:hover {
    border-color: var(--orange);
    color: var(--orange);
    transform: scale(1.15);
}

/* active selection animation */
.rating-circle.active {
    background: var(--orange);
    color: white;
    border-color: var(--orange);
    animation: pop 0.25s ease;
}

@keyframes pop {
    0% { transform: scale(0.8); }
    100% { transform: scale(1); }
}

/* =========================
   TOGGLE SWITCH
========================= */

.toggle {
    position: relative;
    width: 44px;
    height: 24px;
    display: inline-block;
}

.toggle input {
    display: none;
}

.slider {
    position: absolute;
    cursor: pointer;
    background-color: #ccc;
    border-radius: 20px;
    top: 0; left: 0; right: 0; bottom: 0;
    transition: 0.3s;
}

.slider:before {
    content: "";
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s;
}

.toggle input:checked + .slider {
    background: var(--success);
}

.toggle input:checked + .slider:before {
    transform: translateX(20px);
}

/* =========================
   BUTTONS (MODERN)
========================= */

.submit-btn {
    margin-top: 20px;
    padding: 12px 26px;
    background: var(--orange);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.2);
}

/* hover lift */
.submit-btn:hover {
    transform: translateY(-3px);
    background: #e85c2f;
    box-shadow: 0 8px 18px rgba(255, 107, 53, 0.3);
}

/* click effect */
.submit-btn:active {
    transform: scale(0.96);
}
</style>

<title>Assessment</title>


</head>

<body>
<meta name="csrf-token" content="{{ csrf_token() }}">
<header class="toolbar">
<a class="btn">
← Back to Activity
</a>


</header>

<div class="assessment-container">


    <div id="assessmentTable"></div>

</div>

<script>

document.addEventListener("DOMContentLoaded", async () => {

    const container = document.getElementById("assessmentTable");

    const activityId = window.location.pathname.split("/")[2];
    const sectionId = window.location.pathname.split("/")[4];


const res = await fetch(`/api/assessment/${activityId}/${sectionId}`);
    const result = await res.json();
    const students = result.students;
     let ratings = {};
    let completions = {};

    
    const competencies = result.competencies;
    let html = `
    <div class="assessment-wrapper">
        <h2 class="assessment-title">Assessment</h2>

        <div class="table-container">
            <table class="assessment-table">
                <thead>
                    <tr>
                        <th class="sticky-col">Student</th>
    `;

    competencies.forEach(c => {
        html += `<th>${c.name}</th>`;
    });

    html += `
        <th>Completed</th>
        <th>Comment</th>
    </tr>
    </thead>
    <tbody>
    `;

    students.forEach(student => {

        html += `
        <tr>
            <td class="student-name sticky-col">${student.full_name}</td>
        `;

        competencies.forEach(c => {
            html += `
            <td>
                <div class="rating-group"
                     data-student="${student.id}"
                     data-competency="${c.id}">
                    ${[1,2,3,4].map(v => `
                        <span class="rating-circle" data-value="${v}">
                            ${v}
                        </span>
                    `).join('')}
                </div>
            </td>
            `;
        });

        html += `
            <td>
                <label class="toggle">
                    <input type="checkbox"
                           class="completion-toggle"
                           data-student="${student.id}">
                    <span class="slider"></span>
                </label>
            </td>

            <td>
                <textarea class="comment-box"
                          data-student="${student.id}"
                          placeholder="Comment..."></textarea>
            </td>
        </tr>
        `;
    });

    html += `</tbody></table></div>

    <button class="submit-btn" id="saveBtn">
        Save Assessment
    </button>
    </div>`;

    container.innerHTML = html;

    // =========================
    // RATING CLICK
    // =========================
    document.addEventListener("click", (e) => {

        if (e.target.classList.contains("rating-circle")) {

            const group = e.target.parentElement;
            const studentId = group.dataset.student;
            const competencyId = group.dataset.competency;
            const value = parseInt(e.target.dataset.value);

            if (!ratings[studentId]) ratings[studentId] = {};

            ratings[studentId][competencyId] = value;

            group.querySelectorAll(".rating-circle")
                .forEach(c => c.classList.remove("active"));

            e.target.classList.add("active");
        }
    });

    // =========================
    // COMPLETION + COMMENT
    // =========================
    document.addEventListener("change", (e) => {

        if (e.target.classList.contains("completion-toggle")) {

            const studentId = e.target.dataset.student;

            if (!completions[studentId]) {
                completions[studentId] = {};
            }

            completions[studentId].completed = e.target.checked;
            completions[studentId].activity_id = activityId;
        }
    });

    document.addEventListener("input", (e) => {

        if (e.target.classList.contains("comment-box")) {

            const studentId = e.target.dataset.student;

            if (!completions[studentId]) {
                completions[studentId] = {};
            }

            completions[studentId].comment = e.target.value;
            completions[studentId].activity_id = activityId;
        }
    });

    // =========================
    // SUBMIT
    // =========================
    document.addEventListener("click", async (e) => {

        if (e.target.id === "saveBtn") {

            const payload = {
                ratings,
                completions
            };
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const res = await fetch('/api/assessment/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : ''
                },
                body: JSON.stringify(payload)
            });

            const result = await res.json();

            alert(result.message);
        }
    });

});

</script>

</body>
</html>