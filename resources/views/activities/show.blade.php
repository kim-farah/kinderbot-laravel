<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Activity</title>

<style>
:root {
    --primary: #05BAB2;
    --primary-dark: #039b94;
    --accent: #FEC243;
    --bg: #f4f7fb;
    --text: #1f2937;
    --card: #ffffff;
}

/* BASE */
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: var(--bg);
    color: var(--text);
    padding: 20px;
}

/* CONTAINER */
.page {
    max-width: 1100px;
    margin: auto;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 22px;
}

/* STICKY TOOLBAR */
.toolbar {
    position: sticky;
    top: 0;
    z-index: 1000;

    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 14px 20px;

    background: linear-gradient(135deg, #05BAB2, #039b94);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);

    backdrop-filter: blur(10px);

    animation: slideDown 0.5s ease;
}

/* BUTTONS */
.btn {
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 12px;
    font-weight: 500;
    transition: 0.3s ease;
}

/* LEFT BUTTON */
.back-btn {
    background: white;
    color: #1f2937;
}

.back-btn:hover {
    transform: translateX(-3px);
}

/* RIGHT BUTTON */
.assessment-btn {
    background: #FEC243;
    color: #1f2937;
}

.assessment-btn:hover {
    transform: translateX(3px);
}

/* ANIMATION */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}



/* MAIN HERO */
.hero-strip {
    background: linear-gradient(
        135deg,
        #1f2937 0%,      /* teal */
        #04a39c 35%,     /* darker teal */
        #1f2937 100%     /* soft blue */
    );

    padding: 24px 28px;
    border-radius: 18px;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;

    position: relative;
    overflow: hidden;

    box-shadow: 0 14px 35px rgba(0,0,0,0.18);

    animation: heroPop 0.6s ease;
}

/* MULTI COLOR GLOW ORBS */
.hero-strip::before {
    content: "";
    position: absolute;

    width: 240px;
    height: 240px;
    top: -90px;
    right: -90px;

    background: #FEC243; /* yellow */
    opacity: 0.25;
    border-radius: 50%;
    filter: blur(10px);
}

.hero-strip::after {
    content: "";
    position: absolute;

    width: 180px;
    height: 180px;
    bottom: -60px;
    left: -60px;

    background: #ffffff;
    opacity: 0.12;
    border-radius: 50%;
    filter: blur(12px);
}

/* LEFT */
.hero-left {
    flex: 1;
    z-index: 2;
}

/* RIGHT */
.hero-right {
    flex: 2;
    z-index: 2;
}

/* TITLE (STRONG + SHARP) */
.hero-title {
    margin: 0;
    font-size: 32px;
    font-weight: 900;
    color: #ffffff;

    letter-spacing: 0.5px;

    text-shadow: 0 6px 18px rgba(0,0,0,0.25);
}

/* OBJECTIVE (COLORFUL CARD STYLE) */
.hero-objective {
    margin: 0;

    font-size: 18px;
    font-weight: 500;
    line-height: 1.6;

    color: #1f2937; /* dark text */

    background: linear-gradient(
        135deg,
        rgba(254, 194, 67, 0.95),  /* yellow */
        rgba(255, 255, 255, 0.9)   /* white */
    );

    padding: 14px 16px;
    border-radius: 14px;

    box-shadow: 0 10px 25px rgba(0,0,0,0.15);

    backdrop-filter: blur(6px);
}

/* ANIMATION */
@keyframes heroPop {
    from {
        opacity: 0;
        transform: translateY(-14px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* HOVER EFFECT */
.hero-strip:hover {
    transform: translateY(-3px);
    transition: 0.3s ease;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero-strip {
        flex-direction: column;
        align-items: flex-start;
    }

    .hero-title {
        font-size: 24px;
    }

    .hero-objective {
        font-size: 16px;
        width: 100%;
    }
}

/* BASE */
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #f4f9fb;
    color: #1f2937;
}

/* LAYOUT */
.layout {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 60px;
    padding: 20px;
    max-width: 1200px;
    height: 100%;
    margin: auto;
}

/* LEFT SIDE */
.left-column {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* ACCORDION CARD (SOFT + FRIENDLY) */
.accordion-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;

    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
    transition: all 0.3s ease;

    border: 2px solid rgba(5,186,178,0.15);
}

/* HEADER (KIDS STYLE) */
.accordion-header {
    padding: 16px;
    background: linear-gradient(135deg, #05BAB2, #04a39c);
    color: white;
    font-weight: 700;
    font-size: 16px;

    cursor: pointer;
    user-select: none;

    display: flex;
    justify-content: space-between;
    align-items: center;

    transition: 0.3s ease;
}

.accordion-header:hover {
    background: linear-gradient(135deg, #04a39c, #3b82f6);
    transform: scale(1.01);
}

/* BODY (SMOOTH EXPAND) */
.accordion-body {
    max-height: 0;
    overflow: hidden;

    padding: 0 16px;
    background: #f9fcfd;

    transition: max-height 0.5s ease, padding 0.3s ease;
}

/* OPEN STATE */
.accordion-body.active {
    max-height: 400px;
    padding: 16px;
}

/* TEXT */
.accordion-body p {
    line-height: 1.6;
    font-size: 15px;
}

/* SKILLS LIST */
#skillsList {
    margin: 0;
    padding-left: 18px;
}

#skillsList li {
    margin-bottom: 8px;
    font-size: 15px;
    position: relative;
}

/* fun bullet style */
#skillsList li::marker {
    color: #FEC243;
}

/* RIGHT COLUMN (CENTER EVERYTHING) */
.right-column {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* WRAPPER (TEXT + IMAGE TOGETHER) */
/* WRAPPER (make it narrower) */
.image-card-wrapper {
    width: 100%;
    max-width: 320px;   /* thinner */
    margin: 0 auto;

    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

/* IMAGE */
.image-card img {
    width: 100%;
    height: 440px;      /* taller */
    object-fit: cover;
    display: block;
}

/* RODIN COMMENT */
.rodin-text {
    width: 100%;
    text-align: left;

    background: linear-gradient(135deg, #FEC243, #fff3c4);
    padding: 14px 16px;
    border-radius: 14px;

    font-size: 15px;
    font-weight: 500;
    line-height: 1.5;

    color: #1f2937;

    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    border-left: 5px solid #05BAB2;

    animation: fadeIn 0.5s ease;
}

/* IMAGE CARD (ONLY ONE DEFINITION) */
.image-card {
    width: 100%;
    border-radius: 20px;
    overflow: hidden;

    background: white;
    box-shadow: 0 12px 28px rgba(0,0,0,0.10);

    border: 3px solid rgba(5,186,178,0.15);

    transition: transform 0.3s ease;
}

.image-card:hover {
    transform: translateY(-4px);
}

/* IMAGE */
/*.image-card img {
    width: 100%;
    height: 240px; /* balanced height */
   /* object-fit: cover;
    display: block;
}*/

/* ANIMATION */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .right-column {
        justify-content: center;
    }

    .image-card-wrapper {
        max-width: 100%;
    }

    .image-card img {
        height: 200px;
    }
}

/* SMALL ANIMATION TOUCH */
.accordion-card:hover {
    transform: translateY(-2px);
}

/* OPTIONAL ICON FEEL */
.accordion-header::after {
    content: "▼";
    font-size: 12px;
    transition: transform 0.3s ease;
}

.accordion-body.active + .accordion-header::after {
    transform: rotate(180deg);
}

/* MAIN LAYOUT */
.two-column-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    max-width: 100%;
    margin: auto;
    padding: 20px;
    align-items: center;
    
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .two-column-layout {
        grid-template-columns: 1fr;
    }
}

/* ================= LEFT COLUMN ================= */

/* MATERIALS CARD */
.materials-card {
    width: 100%;
    max-width: 400px;

    background: white;
    border-radius: 18px;
    padding: 18px;

    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    border-left: 5px solid #05BAB2;

    cursor: pointer;
    display: flex;
    flex-direction: column;
}
.left-col {
    display: flex;
    justify-content: center;  /* 👈 horizontal center */
    align-items: center;
    width: 80%;
    margin: auto;
}

/* TITLE */
.materials-card h3 {
    margin: 0;
    font-size: 30px;
    font-weight: 700;
    min-width: 400px; /* prevents overflow */
}

/* COLLAPSED STATE */
.materials-list {
    max-height: 0;
    overflow: hidden;
    opacity: 0;

    transition: all 0.4s ease;
    margin-top: 0;
}

/* OPEN STATE */
.materials-card.open .materials-list {
    max-height: 1000px;
    opacity: 1;
    margin-top: 12px;
     font-size: 25px;

}

/* LIST STYLE */
.materials-list ul {
    padding-left: 25px;
    margin: 0;
}

.materials-list li {
    margin-bottom: 6px;
}

/* ================= RIGHT COLUMN ================= */

.right-col {
    display: flex;
    flex-direction: column;
    align-items: center;   /* 👈 centers content */
    gap: 14px;
    width: 300%;
    height: 100%;
}

/* RODIN TEXT (already styled but kept clean) */
.rodin-text {
    background: linear-gradient(135deg, #FEC243, #fff3c4);
    padding: 14px 16px;
    border-radius: 14px;

    font-size: 15px;
    line-height: 1.5;
    max-width: 400px;

    color: #1f2937;
    text-align: center;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    border-left: 5px solid #05BAB2;
}

/* IMAGE CARD */
.rodin-image-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    padding: 0;
    box-shadow: 0 12px 28px rgba(0,0,0,0.10);
    border: 3px solid rgba(5,186,178,0.15);
    width: 80%;
    max-width: 380px;   /* keeps it balanced */
    transition: 0.3s ease;
    min-height: 500px;
    align-content: center;
    min-width: 300px;
    align-content: left;
}

.rodin-image-card:hover {
    transform: translateY(-4px);
}

/* IMAGE */
.rodin-image-card img {
    width: 100%;
    height: 500px;      /* taller for better presence */
    object-fit: cover;
    display: block;
}
/* MAIN PAGE */
.activity-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
    gap: 20px;
    padding: 20px;
    max-width: 1200px;
    margin: auto;
}

/* ================= UPPER SECTION ================= */

.upper-section {
    flex: 0 0 30vh; /* 30% screen height */
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 20px;

    align-items: center;
}

/* COMMENT BOX */
.activity-comment {
    background: linear-gradient(135deg, #FEC243, #fff3c4);
    padding: 18px;
    border-radius: 16px;

    font-size: 16px;
    line-height: 1.6;
    color: #1f2937;

    box-shadow: 0 10px 25px rgba(0,0,0,0.1);

    animation: fadeInUp 0.5s ease;
}

/* IMAGE */
.upper-image {
    display: flex;
    justify-content: center;
    align-items: center;
}

.upper-image img {
    height: 100%;
    max-height: 28vh;
    width: auto;

    border-radius: 16px;
    object-fit: cover;

    box-shadow: 0 12px 28px rgba(0,0,0,0.15);

    animation: fadeInUp 0.6s ease;
}

/* ================= LOWER SECTION ================= */

/* STEPS GRID */
.steps-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 18px;
}

/* STEP CARD */
.step-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 20%;
    width: 200px;              /* controls card width */
    padding: 12px;

    background: white;
    border-radius: 16px;

    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(5,186,178,0.15);
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.step-card:hover {
     transform: scale(1.1) translateY(-6px);
    box-shadow: 0 18px 35px rgba(0,0,0,0.18);
    z-index: 10; /* prevents overlap issues */
}

/* IMAGE (controlled but flexible) */
.step-image img {
    max-width: 100%;
    max-height: 300px;   /* limit height */
    width: auto;
    height: auto;
    width: 100%;
    object-fit: contain; /* keeps full image visible */

    border-radius: 10px;
}

/* TEXT */
.step-text {
    margin-top: 10px;
    font-size: 14px;
    text-align: center;
    color: #1f2937;

    word-wrap: break-word;
}

/* MAIN LAYOUT */
.layout-2col {
    display: grid;
    grid-template-columns: 40% 60%;
    gap: 24px;
    min-height: 80%;
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .layout-2col {
        grid-template-columns: 1fr;
    }
}

/* LEFT PANEL */
.left-panel {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    justify-content: center;
}

/* COMMENT */
.comment-box {
    background: linear-gradient(135deg, #FEC243, #fff3c4);
    padding: 16px;
    border-radius: 14px;

    font-size: 15px;
    text-align: center;

    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* RODIN IMAGE */
.rodin-image img {
    max-width: 260px;
    height: auto;

    border-radius: 16px;
    object-fit: cover;

    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* RIGHT PANEL */
.right-panel {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* IMAGE CONTAINER */
.image-container {
    width: 100%;
    max-width: 600px;

    background: white;
    padding: 16px;
    border-radius: 18px;

    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    text-align: center;
}

/* BIG IMAGE */
.image-container img {
    width: 100%;
    max-height: 500px;
    object-fit: contain;

    border-radius: 12px;
}

/* BUTTONS */
.image-actions {
    margin-top: 12px;
    display: flex;
    justify-content: center;
    gap: 12px;
}

.image-actions button {
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
    cursor: pointer;

    background: #05BAB2;
    color: white;

    transition: 0.3s ease;
}

.image-actions button:hover {
    background: #039b94;
}

/* MAIN CONTAINER */
.feedback-layout {
    max-width: 40%;
    margin: auto;
    padding: 30px;
    min-height: 80%;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #039b94;
    gap: 18px;
    border-radius: 20px;
    margin-top: 40px;
    margin-bottom: 40px;
}

/* TITLE */
.feedback-title {
    font-size: 30px;
    font-weight: 5000;
    color: #1f2937;
}

/* TEXT */
.rodin-says {
    font-size: 30px;
    color: #FEC243;
    font-style: italic;
}

/* DROP ZONE (BIG CIRCLE) */
.drop-zone {
    width: 140px;
    height: 140px;

    border-radius: 50%;
    border: 3px dashed #05BAB2;

    display: flex;
    justify-content: center;
    align-items: center;

    background: #f9fcfd;

    transition: 0.3s ease;
}

.drop-zone.hover {
    background: rgba(5,186,178,0.1);
    transform: scale(1.05);
}

/* DRAG ROW */
.draggable-row {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

/* DRAG ITEMS (CIRCLES) */
.draggable-item {
    width: 100px;
    height: 100px;

    border-radius: 50%;
    object-fit: cover;
    background: white;
    box-shadow: 0 6px 18px rgba(0,0,0,0.9);
    cursor: grab;
    transition: transform 0.2s ease;
    border: 3px solid rgba(5,186,178,0.3);
}

.draggable-item:hover {
    transform: scale(1.1);
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .draggable-row {
        flex-wrap: wrap;
        justify-content: center;
    }
}
/* STICKY THIN FOOTER */
.sticky-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;

    background: white;
    color: #05BAB2;

    text-align: left;

    font-size: 11px;      /* VERY thin text */
    padding: 4px 0;       /* makes it slim */

    letter-spacing: 1px;

    z-index: 9999;

    box-shadow: 0 -2px 8px rgba(0,0,0,0.08);
}
</style>

</head>

<body>
<header class="toolbar">

@if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'teacher')
    <a class="btn back-btn" id="backBtn">
        ← Main Page
    </a>

    <a id="assessmentBtn" class="btn assessment-btn">
        Go To Assessment →
    </a>
@endif

@if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'coordinator')
    <a href="{{ route('coordinator') }}" class="btn back-btn">
        ← Back to Coordinator Page
    </a>
@endif

</header>
<section class="hero-strip">

<div class="hero-left">
<h1 id="title" class="hero-title"></h1>
</div>

<div class="hero-right">
<p id="objective" class="hero-objective"></p>
</div>

</section>

<main class="layout">

<!-- LEFT COLUMN -->
<div class="left-column">

<!-- OVERVIEW -->
<div class="accordion-card">
<div class="accordion-header" onclick="toggleCard('overviewCard')">
Overview
</div>

<div id="overviewCard" class="accordion-body">
<p id="overviewText"></p>
</div>
</div>

<!-- SKILLS -->
<div class="accordion-card">
<div class="accordion-header" onclick="toggleCard('skillsCard')">
Skills & Competencies
</div>

<div id="skillsCard" class="accordion-body">
<ul id="skillsList"></ul>
</div>
</div>

</div>

<!-- RIGHT COLUMN -->
<div class="right-column">

<div class="image-card-wrapper">



<!-- IMAGE -->
<div class="image-card">
    <img id="mainImage">
</div>

</div>

</div>

<div class="two-column-layout">

    <!-- LEFT COLUMN -->
    <div class="left-col">

        <div class="materials-card" onclick="toggleMaterials(this)">

    <h3>Materials needed</h3>

    <div class="materials-list">
        <ul>
           @foreach(preg_split('/\r\n|\r|\n/', $activity->materials) as $material)
                @if(trim($material) != '')
                    <li>{{ trim($material) }}</li>
                @endif
            @endforeach
        </ul>
    </div>

</div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="right-col">

        <!-- RODIN COMMENT -->
        <p id="rodinComment" class="rodin-text"></p>

        <!-- IMAGE CARD -->
        <div class="rodin-image-card">
            <img src="{{ asset('images/staticRodin1.png') }}" alt="Rodin Image">
        </div>

    </div>

</div>


</main>
<section class="activity-page">

    <!-- UPPER SECTION (30%) -->
    <div class="upper-section">

        <!-- LEFT: ACTIVITY COMMENT -->
        <div class="activity-comment">
            {{ $activity->activity_comment }}
        </div>

        <!-- RIGHT: IMAGE -->
        <div class="upper-image">
            <img src="{{ asset('images/staticRodin2.png') }}" alt="Rodin Image">
        </div>

    </div>

    <!-- LOWER SECTION (DYNAMIC CARDS) -->
    <div class="steps-section">

        <div class="steps-section"></div>

    </div>

</section>

<section class="layout-2col">

    <!-- LEFT COLUMN -->
    <div class="left-panel">

        <div class="comment-box" id="feedbackComment"></div>

        <div class="rodin-image">
            <img src="{{ asset('images/staticRodin3.png') }}" alt="">
        </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="right-panel">

        <div class="image-container">
            <img id="mainDynamicImage" src="" alt="Activity Image">

            <div class="image-actions">
                <button onclick="downloadImage()">Download</button>
                <button onclick="toggleSolution()">Show Solution</button>
            </div>
        </div>

    </div>

</section>

<section class="feedback-layout">

    <h2 class="feedback-title">Feedback</h2>

    <p class="rodin-says">
        Rodin says: this activity was
    </p>

    <!-- DROP ZONE -->
    <div class="drop-zone" id="dropZone"></div>

    <!-- DRAG ITEMS -->
    <div class="draggable-row">

        

    <img src="{{ asset('images/fb1.png') }}" class="draggable-item" draggable="true" data-value="1">

    <img src="{{ asset('images/fb2.png') }}" class="draggable-item" draggable="true" data-value="2">

    <img src="{{ asset('images/fb3.png') }}" class="draggable-item" draggable="true" data-value="3">



    </div>

</section>
<footer class="sticky-footer">
    © MINDSCAPE LITTLE INNOVATORS
</footer>
<script>
    function toggleCard(id) {
    const el = document.getElementById(id);
    el.classList.toggle("active");
}


function toggleMaterials(card) {
    card.classList.toggle("open");
}



document.addEventListener("DOMContentLoaded", () => {

    const id = window.location.pathname.split("/").pop();
   
    /* NEW VARIABLES */
    let mainImage = "";
    let solutionImage = "";
    let showingSolution = false;

    fetch(`/api/activities/${id}`)
        .then(r => r.json())
        .then(data => {

            /* MAIN INFO */
            document.getElementById("title").innerText = data.title || "";
            document.getElementById("objective").innerText = data.objective || "";
            document.getElementById("overviewText").innerText = data.overview || "";
            document.getElementById("rodinComment").innerText = data.rodin_comment || "";

            /* NEW: ACTIVITY COMMENT (LEFT COLUMN) */
            if (document.getElementById("activityComment")) {
                document.getElementById("activityComment").innerText =
                    data.activity_comment || "";
            }
            if (document.getElementById("feedbackComment")) {
                document.getElementById("feedbackComment").innerText =
                    data.feedback_comment || "";
            }

            /* SKILLS */
            document.getElementById("skillsList").innerHTML =
                (data.skills_competencies || "")
                .split(/\r\n|\r|\n/)
                .map(skill => skill.trim())
                .filter(skill => skill !== "")
                .map(skill => `<li>${skill}</li>`)
                .join("");

            /* MAIN IMAGE (FIRST RESOURCE) */
            document.getElementById("mainImage").src =
                data.resources?.[0]?.file_path
                ? `/storage/${data.resources[0].file_path}`
                : "";

            /* NEW: BIG IMAGE + SOLUTION LOGIC */
            if (data.resources && data.resources.length >= 3) {
                mainImage = "/storage/" + data.resources[1].file_path;      // 2nd
                solutionImage = "/storage/" + data.resources[2].file_path;  // 3rd
            }

            if (document.getElementById("mainDynamicImage")) {
                document.getElementById("mainDynamicImage").src = mainImage;
            }

            /* STEPS */
            const container = document.querySelector(".steps-section");
            if (container) {
                container.innerHTML = "";

                data.steps.forEach(step => {
                    container.innerHTML += `
                        <div class="step-card">
                            <div class="step-image">
                                <img src="/storage/${step.image_path}" alt="">
                            </div>
                            <div class="step-text">
                                ${step.description}
                            </div>
                        </div>
                    `;
                });
            }

            /* BUTTON FUNCTIONS (attached AFTER data is loaded) */

            window.downloadImage = function () {
                const link = document.createElement("a");
                link.href = showingSolution ? solutionImage : mainImage;
                link.download = "activity-image.png";
                link.click();
            };

            window.toggleSolution = function () {
                showingSolution = !showingSolution;

                document.getElementById("mainDynamicImage").src =
                    showingSolution ? solutionImage : mainImage;
            };

        })
        .catch(err => console.log(err));
//const activityId = new URLSearchParams(window.location.search).get('activity');
 const sectionId = new URLSearchParams(window.location.search).get('section');
 const activityId = window.location.pathname.split("/").pop();
   document.getElementById("assessmentBtn").href =
    `/activities/${activityId}/sections/${sectionId}/assessment`;

    document.getElementById("backBtn").href=`/activities?section=${sectionId}`;

   console.log("activityId:", activityId);
   console.log("sectionId:", sectionId);

});


       
/* DOWNLOAD FUNCTION */
    window.downloadImage = function () {
        const link = document.createElement("a");
        link.href = showingSolution ? solutionImage : mainImage;
        link.download = "activity-image.png";
        link.click();
    };

    /* TOGGLE SOLUTION */
    window.toggleSolution = function () {
        showingSolution = !showingSolution;

        document.getElementById("mainDynamicImage").src =
            showingSolution ? solutionImage : mainImage;
    };

  
document.addEventListener("DOMContentLoaded", () => {

    const dropZone = document.getElementById("dropZone");
    const items = document.querySelectorAll(".draggable-item");

    let draggedElement = null;

    /* DRAG START */
    items.forEach(item => {
        item.addEventListener("dragstart", (e) => {
            draggedElement = item;
        });
    });

    /* DRAG OVER */
    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("hover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("hover");
    });

    /* DROP */
    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("hover");

        if (draggedElement) {

            // IMPORTANT: clone so original stays
            const clone = draggedElement.cloneNode(true);

            clone.style.width = "100%";
            clone.style.height = "100%";
            clone.style.border = "none";

            dropZone.innerHTML = "";
            dropZone.appendChild(clone);
        }
    });

});


</script>


</body>
</html>
