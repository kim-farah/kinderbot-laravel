<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>{{ $activity->title }}</title>
<link rel="stylesheet" href="{{ asset('css/activity.css') }}">
</head>
<body>

<div class="activity-shell">

    <!-- TOP TOOLBAR -->
    <header class="top-toolbar">

        <a href="{{ url('/') }}" class="toolbar-btn ghost-btn">
            ← Main Page
        </a>
        <div class="activity-badge">
            Activity {{ $activity->id }}
        </div>

        <a href="{{ route('activities.assessment',$activity->id) }}" class="toolbar-btn primary-btn">
            Assessment →
        </a>

    </header>
    <!-- HERO HEADER -->
    <section class="hero-card">

        <div class="hero-text">

            <span class="eyebrow">
                Guided Learning Activity
            </span>

            <h1>
                {{ $activity->title }}
            </h1>

            <p>
                {{ $activity->objective ?? 'Activity introduction text from database.' }}
            </p>

        </div>

    </section>
    <!-- FEATURE GRID -->
    <section class="feature-grid">

        @php
            $feature1 = $activity->resources->get(0);
            $feature2 = $activity->resources->get(1);
        @endphp


        <!-- IMAGE + OVERLAY TEXT -->
        <div class="feature-image tall-card">

            @if($feature1)
                <img
                    src="{{ asset('storage/' .$feature1->file_path) }}"
                    alt="{{ $feature1->title }}"
                >
            @endif
            <div class="image-overlay">
                <h2>
                    {{ $activity->instructions ?? 'Concept Highlight' }}
                </h2>
            </div>

        </div>


        <!-- SECOND IMAGE -->
        <div class="feature-image tall-card secondary-image">

            @if($feature2)
                <img
                    src="{{ asset('storage/' . $feature2->file_path) }}"
                    alt="{{ $feature2->title }}"
                >
            @endif

        </div>

        </section>


    <!-- EXPANDABLE LEARNING PANELS -->
    <section class="accordion-section">

        <!-- OVERVIEW -->
        <article class="lesson-card open">

            <button class="accordion-trigger">
                <span>Overview</span>
                <span class="chevron">⌄</span>
            </button>

            <div class="accordion-content">
                {!! $activity->overview !!}
            </div>

        </article>

        <!-- SKILLS -->
        <article class="lesson-card">

            <button class="accordion-trigger">
                <span>Skills & Competencies</span>
                <span class="chevron">⌄</span>
            </button>

            <div class="accordion-content">
                {!! $activity->skills_competencies !!}
            </div>

        </article>


        <!-- MATERIALS -->
        <article class="lesson-card materials-card">

            <button class="accordion-trigger">
                <span>Materials Needed</span>
                <span class="chevron">⌄</span>
            </button>

            <div class="accordion-content">

                <div class="materials-cloud">

                    @foreach(explode(',', $activity->materials) as $material)

                        <div class="material-pill">
                            {{ trim($material) }}
                        </div>

                    @endforeach

                </div>

            </div>

        </article>


    </section>


</div>

@php
    $conceptImage = $activity->resources->get(2);
@endphp

<section class="explore-section">

    <div class="explore-hero">

        <div class="explore-copy">
            <span class="section-label">
                Explore and Observe
            </span>

            <h2>
                {{ $activity->instructions ?? 'Observe the visual model below.' }}
            </h2>

            <p>
                {{ $activity->objective ?? 'Short guiding text from activity table.' }}
            </p>
        </div>

         <div class="explore-image-card">
            @if($conceptImage)
                <img
                    src="{{ asset('storage/' . $conceptImage->file_path) }}"
                    alt="{{ $conceptImage->title }}"
                >
            @endif
        </div>

    </div>


    <div class="step-card-grid">

        @foreach($activity->steps as $step)

            <article class="step-card">

                <div class="step-image-wrap">
                    <img
                        src="{{ asset('storage/' . $step->image_path) }}"
                        alt="{{ $step->title }}"
                    >
                </div>

                <div class="step-content">
                    <h3>
                        {{ $step->title }}
                    </h3>

                    <p>
                        {{ $step->description }}
                    </p>
                </div>

            </article>

        @endforeach

    </div>

</section>

@php
$instructionImage = $activity->resources->get(3);
$printA = $activity->resources->get(4);
$printB = $activity->resources->get(5) ?? $activity->resources->get(4);
@endphp

<section class="activity-lab">

    <!-- IMAGE + TEXT -->
    <div class="lab-intro-card">

        <h2>
            {{ $activity->instructions ?? 'Analyze the visual and compare observations.' }}
        </h2>

        @if($instructionImage)
            <img
                src="{{ asset('storage/' . $instructionImage->file_path) }}"
                alt="{{ $instructionImage->title }}"
                class="instruction-image"
            >
        @endif

    </div>

     <!-- PRINTABLE INTERACTIVE IMAGE -->
    <div class="print-panel">

        <div class="print-header">

            <h2>Interactive Visual</h2>

            <div class="print-controls">
                <button id="swapImageBtn">
                    Change Image
                </button>

                <button onclick="window.print()">
                    Print
                </button>
            </div>

        </div>

        @if($printA)
            <img
                id="mainPrintable"
                class="main-print-image"
                src="{{ asset('storage/' . $printA->file_path) }}"
                data-original="{{ asset('storage/' . $printA->file_path) }}"
                data-alt="{{ asset('storage/' . $printB->file_path) }}"
            >
        @endif

    </div>



    <!-- RODIN FEEDBACK -->
    <div class="feedback-panel">

        <h2>
            Feedback Rodin says the activity was...
        </h2>

        <div class="rodin-zone-wrap">

            <div id="dropZone" class="rodin-drop-zone">
                Drop Here
            </div>

            <div class="rodin-choices">

                @foreach($activity->resources->slice(6,3) as $emoji)
                    <img
                        src="{{ asset('storage/' . $emoji->file_path) }}"
                        class="drag-token"
                        draggable="true"
                    >
                @endforeach

            </div>

        </div>

    </div>

</section>



<script src="{{ asset('js/activity.js') }}"></script>

</body>
</html>