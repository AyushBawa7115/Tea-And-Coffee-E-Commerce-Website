<style>
    /* --- Infinite Scrolling Headline Styles --- */
    .headline-ticker-container {
        background-color: #f0f8ea; /* Very light green background for visibility */
        color: #2c6b48;
        padding: 10px 0;
        overflow: hidden; /* Hide text that goes beyond the container */
        white-space: nowrap; /* Prevent text from wrapping */
        position: relative; /* Position within the header */
        bottom: 0;
        left: 0;
        height: 50px;
        width: 100%;
        box-sizing: border-box; /* Include padding in width */
        z-index: 10; /* Ensure it's above other header elements if needed */
    }

    .headline-ticker-text {
        display: inline-block; /* Allows for scrolling */
        padding-left: 100%; /* Start off-screen to the right */
        animation: scroll-headline 30s linear infinite; /* Adjust speed as needed */
        transition: animation-play-state 0.3s ease; /* Smooth transition for pause/resume */
    }

    @keyframes scroll-headline {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-100%); }
    }

    .headline-link {
        color: #2c6b48; /* Inherit or set a specific link color */
        text-decoration: none; /* Remove underline if you prefer */
        font-weight: bold; /* Make the links stand out */
    }

    .headline-link:hover {
        text-decoration: underline; /* Add underline on hover for better interaction */
    }
</style>

<div class="headline-ticker-container">
    <div class="headline-ticker-text" id="headlineTickerText">
        Sponsored by <a href="https://www.ciis.ac.in" target="_blank" class="headline-link">Continental Institute For International Studies </a> - Check out our amazing programs! || Visit Our Esteemed <a href="https://www.ciis.ac.in" target="_blank" class="headline-link">College</a> - Apply Now for the Fall Semester! || More exciting announcements will scroll here...
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headlineTickerText = document.getElementById('headlineTickerText');

        headlineTickerText.addEventListener('mouseover', function() {
            this.style.animationPlayState = 'paused';
        });

        headlineTickerText.addEventListener('mouseout', function() {
            this.style.animationPlayState = 'running';
        });
    });
</script>