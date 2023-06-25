{{-- navbar start --}}
<nav class="bg-white border-gray-200 dark:bg-gray-900 z-50 transition-all duration-500" id="navbar">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="" class="flex items-center">
            <img src="{{ asset('images/logo/logo.png') }}" class="h-8 mr-3" alt="Intechfest Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Intechfest</span>
        </a>
        <div class="flex md:order-2">
            @auth
            <div class="flex items-center ml-3">
                <div>
                    <button type="button"
                        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="{{ asset('images/maskot/dedek maskot.jpg') }}"
                            alt="user photo">
                    </button>
                </div>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                    id="dropdown-user">
                    <div class="px-4 py-3" role="none">
                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                            {{auth()->user()->name}}
                        </p>
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                            {{auth()->user()->email}}
                        </p>
                    </div>
                    <ul class="py-1" role="none">
                        <li>
                            <a
                                {{-- buat link berdasarkan level peserta --}}
                                @if (auth()->user()->level == "peserta") {{ 'href=/peserta' }}
                                @elseif (auth()->user()->level == "panitia") {{ 'href=/panitia' }}
                                @elseif (auth()->user()->level == "admin") {{ 'href=/admin' }}
                                @endif
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                role="menuitem">Dashboard</a>
                        </li>
                        <li>
                            <a href="/logout"
                                class="block px-4 py-2 text-sm text-red-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                role="menuitem">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
            @else
            <a href="/login"
                class="text-white bg-primary-lightblue hover:bg-primary-blue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</a>
            @endauth
            <button data-collapse-toggle="navbar-cta" type="button"
                class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-cta" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
            <ul id="list-menu"
                class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <li>
                    <a href="/"
                        class="block py-2 pl-3 pr-4 rounded md:bg-transparent text-gray-900 md:hover:text-primary-lightblue  md:p-0 md:dark:text-blue-500"
                        aria-current="page">Home</a>
                </li>
                <li>
                    <a href="#timeline" id="menu1"
                        class="block py-2 pl-3 pr-4 rounded md:bg-transparent text-gray-900 md:hover:text-primary-lightblue  md:p-0 md:dark:text-blue-500"
                        aria-current="page">Timeline</a>
                </li>
                <li>
                    <a href="#gabung" id="menu2"
                        class="block py-2 pl-3 pr-4 rounded md:bg-transparent text-gray-900 md:hover:text-primary-lightblue  md:p-0 md:dark:text-blue-500"
                        aria-current="page">Gabung Lomba</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
{{-- animasi --}}
<script>
    window.onscroll = function () {
    scrollFunction();
};
function scrollFunction() {
    var navbar = document.querySelector("#navbar");
    var menuHome = document.querySelector("#menuHome");
    var menuFaq = document.querySelector("#menuFaq");
    // console.log(document.documentElement.scrollTop)
    if (
        document.body.scrollTop > 100 ||
        document.documentElement.scrollTop > 100
    ) {
        navbar.classList.add("sticky");
        navbar.classList.add("-top-10");
        navbar.classList.add("translate-y-10");
        navbar.classList.add("border-b-2");
    } else {
        navbar.classList.remove("sticky");
        navbar.classList.remove("-top-10");
        navbar.classList.remove("translate-y-10");
        navbar.classList.remove("border-b-2");
    }
}
// Mendapatkan elemen navbar dan menghitung tingginya
var navbar = document.querySelector('nav');
var navbarHeight = navbar.offsetHeight;

// Mendapatkan semua tautan di navbar
var navLinks = navbar.querySelectorAll('#menu1,#menu2');
// Menambahkan event listener untuk setiap tautan navbar
navLinks.forEach(function(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault(); // Mencegah aksi default dari tautan
    
    var targetId = link.getAttribute('href'); // Mendapatkan id target dari atribut href
    var targetElement = document.querySelector(targetId); // Mendapatkan elemen target
    
    if (targetElement) {
      var targetPosition = targetElement.offsetTop - navbarHeight; // Menghitung posisi target scroll dengan mempertimbangkan ketinggian navbar
      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth' // Efek scroll yang halus
      });
    }
  });
});

</script>