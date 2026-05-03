<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Activities</title>

<style>
:root {
    --primary: #05BAB2;
    --primary-dark: #049d96;
    --accent: #FEC243;
}

/* BASE */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f6f8fa;
}

/* CONTAINER */
.container {
    max-width: 900px;
    margin: auto;
    padding: 20px;
}

/* HERO */
.hero-intro {
    background: white;
    border-radius: 18px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);

    /* KEY CHANGE */
    display: flex;
    align-items: center;
    gap: 20px;
}

/* IMAGE */
.hero-img {
    width: 40%;
    max-height: 320px;
    object-fit: cover;
    border-radius: 14px;
}

/* TEXT BLOCK */
.hero-text {
    flex: 1;
    text-align: left;
}

.hero-intro h1 {
    margin: 0 0 10px;
    color: var(--primary-dark);
}

.hero-intro p {
    color: #555;
    line-height: 1.5;
}

/* SECTION TITLE */
.section-title {
    margin: 20px 0 10px;
    color: var(--primary-dark);
}

/* GRID (NEW IMPROVEMENT) */
#activitiesGrid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 12px;
}

/* ACTIVITY CARD */
.activity-card {
    background: white;
    padding: 16px;
    border-radius: 16px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    border-left: 5px solid var(--primary);
    transition: 0.25s ease;
}

.activity-card:hover {
    transform: translateY(-4px);
    border-left-color: var(--accent);
}

/* BUTTON */
.activity-card button {
    margin-top: 10px;
    padding: 8px 12px;
    border: none;
    border-radius: 10px;
    background: var(--primary);
    color: white;
    cursor: pointer;
}

.activity-card button:hover {
    background: var(--primary-dark);
}

@media (max-width: 768px) {
    .hero-intro {
        flex-direction: column;
        text-align: center;
    }

    .hero-img {
        width: 100%;
    }

    .hero-text {
        text-align: center;
    }
}
</style>
</head>

<body>

<main class="container">

    <!-- HERO -->
    <section class="hero-intro">

    <img class="hero-img" src="{{ asset('images/staticRodin.jpg') }}" alt="rodin">

    <div class="hero-text">
        <h1>Let's meet Rodin</h1>

        <p>
            Ro-Din the Dinosaur Who Dreams of Being a Robot!  In a land full of colorful trees, shiny rocks, and giant  ferns, Ro-Din stood out from the other dinosaurs.  While they roared and played, Ro-Din gazed at the  sky, dreaming of being a ROBOT.

It loved the whirring and buzzing of robots, their  precise movements, and how they build things.

Ro-Din imagined himself with shiny metal parts,  blinking lights, and tools instead of claws. One day,  it had an idea: what if it became the first Robot-  Dinosaur, blending the best of the past and future?

To achieve this, it needed to learn new skills, work  hard, and think creatively. Ro-Din practiced daily,  discovering that becoming a Robot-Dinosaur was  more about heart, curiosity, and imagination than  gears and metal.

        </p>
    </div>

</section>
    <!-- ACTIVITIES -->
    <h2 class="section-title">Activities</h2>
    <div id="activitiesGrid">Loading Activities...</div>

</main>

<script>
    const id = new URLSearchParams(window.location.search).get('activity');
fetch(`/api/activities/${id}`)
const sectionId = new URLSearchParams(window.location.search).get('section');
console.log('Activity ID:', id);
console.log('Section ID:', sectionId);
async function loadActivities() {
    let response = await fetch(`/api/teacher/sections/${sectionId}/activities`);
    let activities = await response.json();
    
    let html = '';

    activities.forEach(activity => {
        html += `
        <div class="activity-card">
            <h3>${activity.title}</h3>
            <button onclick="openActivity(${activity.id})">Open Activity</button>
        </div>`;
    });

    document.getElementById('activitiesGrid').innerHTML = html;
}

function openActivity(activityId) {
     const sectionId = new URLSearchParams(window.location.search).get('section');
    window.location.href = `/activities/${activityId}?section=${sectionId}`;

}

loadActivities();

</script>

</body>
</html>