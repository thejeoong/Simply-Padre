<video 
    id="first-container-video-bg" 
    class="first-container-video-background"
    autoplay 
    muted 
    loop 
    playsinline
    preload="auto"
    data-video-url="https://simplypadre.github.io/background/SimplyPadre-TX.mp4"
>
</video>

<!-- Scroll down arrow -->
<div class="scroll-down-arrow" id="scroll-down-arrow">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</div>

<style>
#first_container {
    position: relative !important;
    overflow: hidden;
    background-color: rgb(24, 46, 69);
    min-height: calc(100vh - 197px);
}

.first-container-video-background {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
    /* Remove min-width/min-height to prevent over-scaling */
}

#first_container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* No overlay - video shows in original colors */
    background: transparent;
    z-index: 1;
    pointer-events: none;
}

#first_container > .container,
#first_container > *:not(.first-container-video-background):not(style):not(script):not(.scroll-down-arrow) {
    position: relative;
    z-index: 2;
}

/* Ensure content containers don't block clicks on the scroll arrow */
.homepage_settings {
    pointer-events: auto;
}

/* Create a click-through zone at the bottom for the arrow */
.homepage_settings::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    pointer-events: none;
    z-index: 1;
}

.scroll-down-arrow {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999 !important;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
    animation: bounce 2s infinite;
    pointer-events: auto !important;
    /* Larger clickable area */
    padding: 15px;
    margin: -15px;
}

.scroll-down-arrow:hover {
    color: rgba(255, 255, 255, 1);
    transform: translateX(-50%) translateY(-5px);
    animation: none;
}

.scroll-down-arrow svg {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    pointer-events: none;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateX(-50%) translateY(0);
    }
    40% {
        transform: translateX(-50%) translateY(-10px);
    }
    60% {
        transform: translateX(-50%) translateY(-5px);
    }
}

@media only screen and (max-width: 768px) {
    #first_container {
        min-height: auto!important;
    }
    
    .homepage_settings {
        padding: 70px 0!important;
    }

    .first-container-video-background {
        /* Better positioning for mobile - zoom out slightly */
        object-fit: cover;
        object-position: center center;
        /* Scale down slightly on mobile to prevent "too big" issue */
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    #first_container::before {
        /* No overlay on mobile - video shows in original colors */
        background: transparent;
    }
    
    .scroll-down-arrow {
        display: none !important;
    }
}
</style>

<script>
(function() {
    function initVideoBackground() {
        const video = document.getElementById('first-container-video-bg');
        
        if (!video) {
            return;
        }
        
        const videoUrl = video.getAttribute('data-video-url');
        
        if (videoUrl) {
            const source = document.createElement('source');
            source.src = videoUrl;
            source.type = 'video/mp4';
            video.appendChild(source);
            
            video.play().catch(function(error) {
                // Video autoplay failed (user interaction may be required)
            });
        }
        
        function resizeVideo() {
            // Let CSS handle the sizing to prevent blurriness from forced scaling
            // The object-fit: cover will handle proper scaling
            const container = document.getElementById('first_container');
            if (container && video) {
                // Ensure video maintains aspect ratio - CSS handles the rest
                video.style.width = '100%';
                video.style.height = '100%';
            }
        }
        
        resizeVideo();
        
        if (window.ResizeObserver) {
            const container = document.getElementById('first_container');
            if (container) {
                const resizeObserver = new ResizeObserver(resizeVideo);
                resizeObserver.observe(container);
            }
        } else {
            window.addEventListener('resize', resizeVideo);
        }
        
        video.addEventListener('loadedmetadata', resizeVideo);
    }
    
    function initScrollArrow() {
        const scrollArrow = document.getElementById('scroll-down-arrow');
        
        function scrollToNextSection(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Find the next section with class 'homepage-sections' (plural)
            const firstContainer = document.getElementById('first_container');
            if (!firstContainer) {
                return;
            }
            
            // Try to find homepage-sections (plural) first
            let nextSection = document.querySelector('.homepage-sections');
            
            if (!nextSection) {
                // Try singular version as fallback
                nextSection = document.querySelector('.homepage-section');
            }
            
            if (!nextSection) {
                // Try searching through siblings
                let current = firstContainer.nextElementSibling;
                while (current) {
                    if (current.classList) {
                        if (current.classList.contains('homepage-sections') || 
                            current.classList.contains('homepage-section')) {
                            nextSection = current;
                            break;
                        }
                    }
                    current = current.nextElementSibling;
                }
            }
            
            if (nextSection) {
                // Calculate position minus 50px to account for sticky menu
                const sectionTop = nextSection.getBoundingClientRect().top + window.pageYOffset;
                const offsetPosition = sectionTop - 50;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }
        
        if (scrollArrow) {
            // Use capture phase to catch clicks before other elements
            scrollArrow.addEventListener('click', scrollToNextSection, true);
            scrollArrow.addEventListener('touchstart', scrollToNextSection, true);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initVideoBackground();
            initScrollArrow();
        });
    } else {
        initVideoBackground();
        initScrollArrow();
    }
})();
</script>

//CSS SECTION 
#first_container {
    background-color: <?php echo $wa['custom_79']?>;
}
body{
    z-index: 0;
}
.vegas-slide-inner {background-position:center top!important;}
.previous {
    left: 10px;
    right: auto;
    background-image: url('/directory/cdn/assets/bootstrap/vegas/img/icon-previous.svg') !important;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
.vegas-wrapper .previous, .vegas-wrapper .next {
    opacity: .8;
    visibility: hidden;
    display: block;
    position: absolute;
    width: 32px;
    height: 32px;
    margin: 0;
    padding: 0;
    background: center center no-repeat;
    background-size: cover;
    top: 50%;
}
.vegas-wrapper .next {
    left: auto;
    right: 10px;
    background-image: url('/directory/cdn/assets/bootstrap/vegas/img/icon-next.svg') !important;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}

.vegas-transition-zoomIn2-out,
.vegas-transition-zoomOut,
.vegas-transition-zoomOut2{
    webkit-transform: scale(1) !important;
    transform: scale(1) !important;
    opacity: 0;
}
.homepage_settings {
    z-index: 9999;
}
.slider-container {
    position: absolute
}
<?php if($page['seo_type'] == "home" && $wa['hide_hero_on_mobile'] == "1"){ ?>
    @media only screen and (max-width: 767px) {
        body .vegas-slide {display: none !important}
		body #first_container {height: 0 !important;overflow: hidden!important;
}
    }
<?php }?>