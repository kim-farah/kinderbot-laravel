<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Activity</title>

<style>
    :root {
    --primary:#05BAB2;
    --primary-dark:#049d96;
    --accent:#FEC243;
}

body {
    margin:0;
    font-family:Arial;
    background:#f6f8fa;
}

/* PAGE */
.page {
    max-width:1000px;
    margin:auto;
    padding:16px;
    display:flex;
    flex-direction:column;
    gap:16px;
}

/* TOOLBAR */
.toolbar {
    display:flex;
    justify-content:space-between;
    padding:14px;
    background:var(--primary);
    border-radius:16px;
}

.btn {
    background:white;
    padding:10px 14px;
    border-radius:12px;
    text-decoration:none;
    color:black;
}

.primary {
    background:var(--accent);
}

/* CARDS */
.card {
    background:white;
    border-radius:18px;
    padding:18px;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
}

/* HERO */
.hero-img {
    width:100%;
    border-radius:14px;
}

/* STEPS */
.steps-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:16px;
}

.step-card {
    border-radius:16px;
    overflow:hidden;
    transition:.3s;
}

.step-card:hover {
    transform:translateY(-8px) scale(1.05);
    box-shadow:0 18px 30px rgba(0,0,0,0.15);
}

/* SECTION 4 */
.section4-layout {
    display:grid;
    grid-template-columns:1.5fr 1fr;
    gap:20px;
}

.feature-large {
    width:100%;
    border-radius:18px;
}

/* SWITCH IMAGE */
.switch-img {
    width:100%;
    border-radius:14px;
    transition:opacity .4s ease;
}

/* BUTTONS */
.action-buttons {
    display:flex;
    gap:10px;
    margin:10px 0;
}

.action-btn {
    background:var(--primary);
    color:white;
    border:none;
    padding:10px;
    border-radius:12px;
}

/* DRAG DROP */
.drop-circle {
    width:160px;
    height:160px;
    border:3px dashed var(--primary);
    border-radius:50%;
    margin:20px auto;
    transition:.3s;
}

.drop-circle.active {
    background:rgba(5,186,178,0.1);
}

.drop-circle.success {
    background:rgba(254,194,67,0.2);
}

.drag-options {
    display:flex;
    justify-content:center;
    gap:12px;
}

.drag-item {
    width:80px;
    height:80px;
    border-radius:50%;
    cursor:grab;
}

/* ANIMATION */
.section-animate {
    opacity:0;
    transform:translateY(30px);
    transition:.6s ease;
}

.section-animate.visible {
    opacity:1;
    transform:translateY(0);
}

/* MOBILE */
@media(max-width:768px) {
    .section4-layout {
        grid-template-columns:1fr;
    }
}
</style>

</head>

<body>

<header class="toolbar section-animate">

<a href="/classes" class="btn">← Main Page</a>

<a href="/activities/{{ $activity->id }}/assessment" id="assessmentBtn" class="btn primary">Go To Assessment →</a>

</header>

<main class="page">

<!-- SECTION 1 -->
<section class="card section-animate">

<h1 id="title"></h1>
<p id="objective"></p>

<img id="heroImage" class="hero-img">

<button class="accordion-btn">Skills & Competences</button>
<div id="skillsBox" class="accordion-content"></div>

<button class="accordion-btn">Overview</button>
<div id="overviewBox" class="accordion-content"></div>

</section>

<!-- SECTION 2 -->
<section class="card section-animate">

<div class="rodin-wrapper">

<img id="rodinImage" class="hero-img">

<div class="overlay-text">
<p id="rodin_comment"></p>
</div>

</div>

<div id="materialsBox" class="materials"></div>

</section>

<!-- SECTION 3 -->
<section class="card section-animate">

<img id="stepsHeaderImage" class="hero-img">

<p id="activity_comment"></p>

<div id="stepsGrid" class="steps-grid"></div>

</section>

<!-- SECTION 4 -->
<section class="card section-animate">

<div class="section4-layout">

<div>

<img id="largeImage" class="feature-large">

<img id="switchImage" class="switch-img">

<div class="action-buttons">

<button id="downloadBtn" class="action-btn">Download</button>
<button id="switchBtn" class="action-btn">Switch Image</button>

</div>

<div id="dropZone" class="drop-circle"></div>

<div id="dragContainer" class="drag-options"></div>

</div>

<div>
<img id="feedbackImage" >
<p id="feedback_comment"></p>

</div>

</div>

</section>

</main>

<footer class="footer">
© MINDSCAPE LITTLE INNOVATORS
</footer>

<script>
    document.addEventListener("DOMContentLoaded", () => {

const id = window.location.pathname.split("/").pop();

fetch(`/api/activities/${id}/data`)
.then(r => r.json())
.then(data => {

/* SECTION 1 */
document.getElementById("title").innerText = data.title;
document.getElementById("objective").innerText = data.objective;

document.getElementById("heroImage").src =
`/storage/${data.resources[0].file_path}`;

document.getElementById("skillsBox").innerHTML =
(data.skills_competencies || "")
.split(",")
.map(i => `<li>${i}</li>`).join("");

document.getElementById("overviewBox").innerHTML =
data.overview;


/* SECTION 2 */
//document.getElementById("rodinImage").src =
//`/storage/${data.resources[1].file_path}`;
document.getElementById("rodinImage").src = `/storage/activities/Picture1.1.2.png`;

document.getElementById("rodin_comment").innerText =
data.rodin_comment;

document.getElementById("materialsBox").innerHTML =
(data.materials || "")
.split(",")
.map(m => `<span>${m}</span>`).join("");


/* SECTION 3 */
//document.getElementById("stepsHeaderImage").src =
//`/storage/${data.resources[2].file_path}`;
document.getElementById("rodinImage").src = `/storage/activities/Picture1.2.1.png`;

document.getElementById("activity_comment").innerText =
data.activity_comment;

document.getElementById("stepsGrid").innerHTML =
data.steps.map(step => `
<div class="step-card">
<img src="/storage/${step.image_path}">
<p>${step.description}</p>
</div>
`).join("");


/* SECTION 4 */
//document.getElementById("largeImage").src =
//`/storage/${data.resources[3].file_path}`;
document.getElementById("rodinImage").src = `/storage/activities/Picture1.3.1.png`;
let toggled = false;

document.getElementById("switchImage").src =
`/storage/${data.resources[1].file_path}`;

document.getElementById("switchBtn").onclick = () => {

const img = document.getElementById("switchImage");

img.style.opacity = 0;

setTimeout(() => {

img.src = toggled
? `/storage/${data.resources[1].file_path}`
: `/storage/${data.resources[2].file_path}`;

img.style.opacity = 1;

toggled = !toggled;

}, 200);

};

document.getElementById("downloadBtn").onclick = () => {
const a = document.createElement("a");
a.href = document.getElementById("switchImage").src;
a.download = "image";
a.click();
};


/* DRAG DROP */
const drop = document.getElementById("dropZone");

drop.addEventListener("dragover", e => {
e.preventDefault();
drop.classList.add("active");
});

drop.addEventListener("dragleave", () => {
drop.classList.remove("active");
});

drop.addEventListener("drop", e => {
e.preventDefault();

const src = e.dataTransfer.getData("text");

drop.innerHTML = `<img src="${src}" style="width:100%;height:100%;border-radius:50%;">`;

drop.classList.add("success");
});


document.getElementById("dragContainer").innerHTML =
data.steps.slice(0,3).map(s => `
<img class="drag-item" draggable="true"
src="/storage/${s.image_path}">
`).join("");

document.querySelectorAll(".drag-item").forEach(img => {
img.addEventListener("dragstart", e => {
e.dataTransfer.setData("text", e.target.src);
});
});


/* SCROLL ANIMATION */
const sections = document.querySelectorAll(".section-animate");


const observer = new IntersectionObserver(entries => {
entries.forEach(entry => {
if (entry.isIntersecting) {
entry.target.classList.add("visible");
}
});
}, { threshold: 0.15 });

sections.forEach(sec => observer.observe(sec));

});

});
</script>

</body>
</html>
