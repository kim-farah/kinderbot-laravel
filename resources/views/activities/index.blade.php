<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Activities</title>

</head>

<body>

    <style>
        /* COLORS */
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

/* HERO SECTION */
.hero-intro {
    background: white;
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    text-align: center;
}

.hero-img {
    width: 100%;
    border-radius: 14px;
    margin-bottom: 12px;
}

.hero-intro h1 {
    margin: 10px 0;
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

/* ACTIVITY CARD */
.card {
    display: block;
    background: white;
    padding: 16px;
    margin-bottom: 12px;
    border-radius: 16px;
    text-decoration: none;
    color: black;
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    border-left: 5px solid var(--primary);
    transition: all 0.25s ease;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeIn 0.5s forwards;
}

/* hover effect (Duolingo feel) */
.card:hover {
    transform: translateY(-4px) scale(1.01);
    border-left-color: var(--accent);
    box-shadow: 0 10px 22px rgba(0,0,0,0.1);
}

/* TITLE INSIDE CARD */
.card h3 {
    margin: 0;
    color: var(--primary-dark);
}

.card p {
    margin: 6px 0 0;
    color: #666;
}

/* ANIMATION */
@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* STAGGER EFFECT (for JS added delay) */
.card:nth-child(1) { animation-delay: 0.05s; }
.card:nth-child(2) { animation-delay: 0.1s; }
.card:nth-child(3) { animation-delay: 0.15s; }
.card:nth-child(4) { animation-delay: 0.2s; }

/* RESPONSIVE */
@media (max-width: 768px) {
    .container {
        padding: 14px;
    }

    .hero-intro {
        padding: 14px;
    }

    .card {
        padding: 14px;
    }
}
    </style>
<header class="toolbar">
    <a href="/classes" class="btn back">← Back to Classes</a>
</header>

<main class="container">

    <!-- HERO INTRO (static image + text) -->
    <section class="hero-intro section-animate">

        <img src="{{ asset('images/staticRodin.jpg') }}" alt="rodin">

        <h1>Let's meet Rodin</h1>

        <p>
            Ro-Din the Dinosaur Who Dreams of Being a Robot!  In a land full of colorful trees, shiny rocks, and giant  ferns, Ro-Din stood out from the other dinosaurs.  While they roared and played, Ro-Din gazed at the  sky, dreaming of being a ROBOT.

It loved the whirring and buzzing of robots, their  precise movements, and how they build things.

Ro-Din imagined himself with shiny metal parts,  blinking lights, and tools instead of claws. One day,  it had an idea: what if it became the first Robot-  Dinosaur, blending the best of the past and future?

To achieve this, it needed to learn new skills, work  hard, and think creatively. Ro-Din practiced daily,  discovering that becoming a Robot-Dinosaur was  more about heart, curiosity, and imagination than  gears and metal.

        </p>

    </section>

    <!-- ACTIVITIES LIST -->
    <h2 class="section-title">Activities</h2>

    <div id="activitiesContainer"></div>

</main>

</body>

<script>
    document.addEventListener("DOMContentLoaded", () => {

    // get classId from URL
    const classId = window.location.pathname.split("/")[2];

    fetch(`/api/classes/${classId}/activities`)
        .then(res => res.json())
        .then(data => {

            const container = document.getElementById("activitiesContainer");

            container.innerHTML = data.map(activity => `
                <a href="/activities/${activity.id}" class="card">
                    <h3>${activity.title}</h3>
                    <p>${activity.objective ?? ''}</p>
                </a>
            `).join('');

        });

});

container.innerHTML = data.map((activity, index) => `
    <a href="/activities/${activity.id}" class="card" style="animation-delay:${index * 0.08}s">
        <h3>${activity.title}</h3>
        <p>${activity.objective ?? ''}</p>
    </a>
`).join('');

</script>
</html>