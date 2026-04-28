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

/* CONTAINER */
.container{
    max-width:1100px;
    margin:auto;
    padding:20px;
}

/* STUDENT CARD */
.student-card{
    background:var(--white);
    border:1px solid var(--border-gray);
    border-radius:16px;
    padding:18px;
    margin-bottom:20px;
    box-shadow:0 6px 16px rgba(0,0,0,0.04);
    transition:0.3s;
}

.student-card:hover{
    transform:translateY(-3px);
}

/* STUDENT HEADER */
.student-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

/* COMPLETED CHECKBOX */
.complete-box{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:14px;
}

/* COMPETENCY GRID */
.comp-grid{
    display:flex;
    flex-direction:column;
    gap:12px;
}

/* SINGLE COMPETENCY ROW */
.comp-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px;
    border-radius:12px;
    background:var(--light-gray);
}

/* NAME */
.comp-name{
    font-weight:600;
}

/* RATING SYSTEM (1–5 PILLS) */
.rating-group{
    display:flex;
    gap:6px;
}

.rating-pill{
    width:32px;
    height:32px;
    border-radius:50%;
    border:1px solid var(--border-gray);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    font-size:13px;
    transition:0.2s;
    background:white;
}

.rating-pill:hover{
    transform:scale(1.1);
}

/* SELECTED STATES */
.rating-pill.active-1{background:#ffdddd;border-color:var(--danger);}
.rating-pill.active-2{background:#ffe6cc;border-color:var(--orange);}
.rating-pill.active-3{background:#fff3cd;border-color:var(--warning);}
.rating-pill.active-4{background:#d4edda;border-color:var(--success);}
.rating-pill.active-5{background:#c3f7d1;border-color:var(--success);}

/* SAVE BUTTON */
.save-btn{
    width:100%;
    padding:14px;
    background:var(--orange);
    border:none;
    border-radius:12px;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    margin-top:20px;
    transition:0.3s;
}

.save-btn:hover{
    background:#e85a2c;
    transform:scale(1.02);
}
</style>

<title>Assessment</title>

<link rel="stylesheet" href="{{ asset('css/assessment.css') }}">
</head>

<body>

<header class="toolbar">
<a href="/activities/{{ $id }}" class="btn">
← Back to Activity
</a>

<h2>Assessment</h2>
</header>

<main class="container">

<div id="assessmentContainer"></div>

<button id="saveBtn" class="save-btn">
Save Assessment
</button>

</main>

<script>
    document.addEventListener("DOMContentLoaded", () => {

const activityId = window.location.pathname.split("/")[2];

let dataStore = {
    assessments: {},
    completions: {}
};

fetch(`/api/activities/${activityId}/assessment`)
.then(r => r.json())
.then(data => {

let html = "";

data.students.forEach(student => {

html += `
<div class="student-card">

<div class="student-header">
    <h3>${student.full_name}</h3>

    <label class="complete-box">
        <input type="checkbox"
        data-student="${student.id}">
        Completed
    </label>
</div>

<div class="comp-grid">
`;

data.competencies.forEach(comp => {

html += `
<div class="comp-row">

<div class="comp-name">${comp.name}</div>

<div class="rating-group">

${[1,2,3,4,5].map(n => `
<div class="rating-pill"
data-student="${student.id}"
data-comp="${comp.id}"
data-value="${n}">
${n}
</div>
`).join("")}

</div>

</div>
`;

});

html += `
</div>
</div>
`;

});

document.getElementById("assessmentContainer").innerHTML = html;


/* RATING CLICK SYSTEM */
document.querySelectorAll(".rating-pill").forEach(pill => {

pill.addEventListener("click", () => {

const student = pill.dataset.student;
const comp = pill.dataset.comp;
const value = pill.dataset.value;

/* store */
dataStore.assessments[`${student}-${comp}`] = value;

/* reset siblings */
document.querySelectorAll(
`.rating-pill[data-student="${student}"][data-comp="${comp}"]`
).forEach(p => {
p.classList.remove("active-1","active-2","active-3","active-4","active-5");
});

/* activate */
pill.classList.add(`active-${value}`);

});

});


/* SAVE */
document.getElementById("saveBtn").onclick = () => {

let assessments = [];
let completions = [];

/* convert stored ratings */
Object.keys(dataStore.assessments).forEach(key => {

const [student, comp] = key.split("-");

assessments.push({
student_id: student,
competency_id: comp,
score: dataStore.assessments[key]
});

});

/* completion */
document.querySelectorAll("input[type='checkbox']").forEach(cb => {

completions.push({
student_id: cb.dataset.student,
completed: cb.checked ? 1 : 0
});

});

fetch("/api/assessment/submit", {
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({
activity_id: activityId,
assessments,
completions
})
});

};

});
</script>

</body>
</html>