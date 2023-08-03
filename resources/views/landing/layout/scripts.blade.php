@vite('resources/js/app.js')
<script src="https://kit.fontawesome.com/7eaa0f0932.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script
  src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-auto-scroll@0.5.3/dist/js/splide-extension-auto-scroll.min.js">
</script>
{{-- <script>
  AOS.init({
        'once': true
    });
</script> --}}
<script>
  document.addEventListener( 'DOMContentLoaded', function () {
  new Splide('.splide', {
    type: 'loop',
    perPage: 3,
    focus: 'center',
    autoplay: true,
    interval: 3000,
    updateOnMove: true,
    pagination: false,
    breakpoints: {
      640: {
        perPage: 1
      },
      768: {
        perPage: 1
      }
    }
  }).mount();
});
document.addEventListener( 'DOMContentLoaded', function () {
  new Splide('.normal', {
  type: 'loop',
  drag: 'free',
  focus: 'center',
  pagination: false,
  arrows: false,
  perPage: 5,
  autoScroll: {
    speed: 2,
  },
  breakpoints: {
      768: {
        perPage: 3
      },
      640: {
        perPage: 2
      }
    }
  }).mount(window.splide.Extensions);
});
document.addEventListener( 'DOMContentLoaded', function () {
  new Splide('.reverse', {
  type: 'loop',
  drag: 'free',
  focus: 'center',
  pagination: false,
  arrows: false,
  perPage: 5,
  autoScroll: {
    speed: -2,
  },
  breakpoints: {
      768: {
        perPage: 3
      },
      640: {
        perPage: 2
      }
    }
  }).mount(window.splide.Extensions);
});
</script>