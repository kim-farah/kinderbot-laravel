<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Activity Assessment</title>

<link rel="stylesheet"
href="{{ asset('css/assessment.css') }}">

</head>
<body>

<div class="assessment-shell">

    <!-- TOPBAR -->
    <header class="assessment-topbar">

        <a
        href="{{ route('activities.show',$activity->id) }}"
        class="nav-btn ghost">
            ← Back to Activity
        </a>

        <h1>
            Assessment — {{ $activity->title }}
        </h1>

        <button
        class="nav-btn primary"
        id="saveRatingsBtn">
            Save Ratings
        </button>

    </header>


    <!-- LEGEND -->
    <div class="legend-panel">

        <span class="legend-chip emerging">
            Emerging
        </span>

        <span class="legend-chip developing">
            Developing
        </span>

        <span class="legend-chip proficient">
            Proficient
        </span>

        <span class="legend-chip advanced">
            Advanced
        </span>

    </div>



    <!-- STUDENTS -->
    @foreach($students as $student)

    <section class="student-card">

        <div class="student-head">

            <div class="avatar-circle">
                {{ strtoupper(substr($student->user->name,0,1)) }}
            </div>

            <div>
                <h2>
                    {{ $student->user->name }}
                </h2>

                <p>
                    Competency Evaluation
                </p>
            </div>

        </div>



        <div class="competency-grid">

            @foreach($activity->competencies as $competency)

            <div class="competency-box">

                <h3>
                    {{ $competency->name }}
                </h3>

                <div
                    class="rating-row"
                    data-student="{{ $student->id }}"
                    data-competency="{{ $competency->id }}"
                >

                    <button
                        class="rate-pill emerging"
                        data-score="1"
                        type="button">
                        1
                    </button>

                    <button
                        class="rate-pill developing"
                        data-score="2"
                        type="button">
                        2
                    </button>

                    <button
                        class="rate-pill proficient"
                        data-score="3"
                        type="button">
                        3
                    </button>

                    <button
                        class="rate-pill advanced"
                        data-score="4"
                        type="button">
                        4
                    </button>


                    <input
                    type="hidden"
                    name="ratings[{{ $student->id }}][{{ $competency->id }}]"
                    value=""
                    >

                </div>

            </div>

            @endforeach

        </div>

    </section>

    @endforeach


</div>


<script src="{{ asset('js/assessment.js') }}"></script>

</body>
</html>