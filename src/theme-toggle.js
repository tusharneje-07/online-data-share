
// Select required elements
const toggleCheckbox = document.getElementById('checkboxInput2');
const rootElement = document.documentElement;

// Function to apply a theme
function applyTheme(theme) {
    if (theme === 'dark') {
        rootElement.classList.add('dark'); // Add the 'dark' class
        toggleCheckbox.checked = true; // Set the checkbox to checked
    } else {
        rootElement.classList.remove('dark'); // Remove the 'dark' class
        toggleCheckbox.checked = false; // Set the checkbox to unchecked
    }
}

// Function to toggle the theme
function toggleTheme() {
    let currentTheme = localStorage.getItem('theme'); // Get the stored theme
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark'; // Toggle the theme
    localStorage.setItem('theme', newTheme); // Store the new theme in localStorage
    applyTheme(newTheme); // Apply the new theme
}

// Function to initialize the theme
function initializeTheme() {
    const storedTheme = localStorage.getItem('theme'); // Get the stored theme
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches; // Check system preference

    // Apply the stored theme or fallback to system preference
    if (storedTheme) {
        applyTheme(storedTheme);
    } else {
        applyTheme(prefersDark ? 'dark' : 'light');
    }
}

// Initialize theme on page load
initializeTheme();

// Add event listener to toggle switch
toggleCheckbox.addEventListener('change', toggleTheme);



function toggleNavBar(){
    const btn = document.getElementById('toggleBtn')
    const navbar = document.getElementById('navbar')
    const navtoggleicon = document.getElementById('navtoggleicon')

    btn.classList.toggle('-translate-y-20')
    btn.classList.toggle('translate-y-2')

    if(btn.classList.contains('-translate-y-20')){
        navbar.classList.remove('hidden')
        navtoggleicon.style.transform = 'rotate(0deg)';
    }
    else{
        navbar.classList.add('hidden')
        navtoggleicon.style.transform = 'rotate(180deg)';
    }
}