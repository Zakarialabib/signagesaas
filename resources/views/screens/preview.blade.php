<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Screen Preview: {{ $screen->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        .content-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        
        .content-slide.active {
            opacity: 1;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-black">
    <div class="screen-container relative w-full h-screen">
        @if($contents->count() > 0)
            @foreach($contents as $index => $content)
                <div class="content-slide {{ $index === 0 ? 'active' : '' }}" 
                     data-duration="{{ $content->duration ?? 10 }}"
                     data-id="{{ $content->id }}">
                    {!! $content->getRenderedHtml() !!}
                </div>
            @endforeach
        @else
            <div class="w-full h-full flex items-center justify-center text-white text-2xl">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p>No content available for this screen.</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.content-slide');
            if (slides.length <= 1) return;

            let currentSlide = 0;
            
            function showSlide(index) {
                // Hide all slides
                slides.forEach(slide => {
                    slide.classList.remove('active');
                });
                
                // Show the current slide
                slides[index].classList.add('active');
                
                // Get the duration from the current slide
                const duration = parseInt(slides[index].dataset.duration) * 1000 || 10000;
                
                // Schedule the next slide
                setTimeout(() => {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }, duration);
            }
            
            // Start the slideshow
            showSlide(currentSlide);
        });
    </script>
</body>
</html> 