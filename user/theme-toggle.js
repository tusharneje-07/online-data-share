
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



function toggleNavBar() {
    const btn = document.getElementById('toggleBtn')
    const navbar = document.getElementById('navbar')
    const navtoggleicon = document.getElementById('navtoggleicon')

    btn.classList.toggle('-translate-y-20')
    btn.classList.toggle('translate-y-2')

    if (btn.classList.contains('-translate-y-20')) {
        navbar.classList.remove('hidden')
        navtoggleicon.style.transform = 'rotate(0deg)';
    }
    else {
        navbar.classList.add('hidden')
        navtoggleicon.style.transform = 'rotate(180deg)';
    }
}




// ----------------------------------------------- IMAGE UPLOAD START
document.addEventListener("DOMContentLoaded", () => {
    const selectImageBtn = document.getElementById("selectImage");
    const fileInput = document.getElementById("fileInput");
    const cropModal = document.getElementById("cropModal");
    const image = document.getElementById("image");
    const cancelCropBtn = document.getElementById("cancelCrop");
    const cropImageBtn = document.getElementById("cropImage");
    const uid_of_user = document.getElementById("uid_prf").value;

    let cropper;

    // Open file select dialog
    selectImageBtn.addEventListener("click", () => {
        fileInput.click();
    });

    // Handle file selection
    fileInput.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file && (file.type === "image/png" || file.type === "image/jpeg")) {
            const reader = new FileReader();
            reader.onload = () => {
                image.src = reader.result;
                cropModal.classList.remove("hidden");
                cropper = new Cropper(image, {
                    aspectRatio: 1, // 1x1 ratio
                    viewMode: 2,
                    autoCropArea: 1,
                    scalable: true,
                    zoomable: true,
                    movable: true,
                    rotatable: true,
                });
            };
            reader.readAsDataURL(file);
        } else {
            alert("Please select a valid .png or .jpg image.");
        }
    });

    // Cancel cropping
    cancelCropBtn.addEventListener("click", () => {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        cropModal.classList.add("hidden");
    });

    // Save cropped image and send to backend
    cropImageBtn.addEventListener("click", () => {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas();
        canvas.toBlob((blob) => {
            const formData = new FormData();
            formData.append("cropped_image", blob, "cropped-image.png");
            formData.append("uid", uid_of_user);
            // Send to backend
            fetch("upload.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show success message
                cropper.destroy();
                cropper = null;
                cropModal.classList.add("hidden");
            })
            .catch(error => console.error("Error uploading image:", error));
        }, "image/png");
    });
});

// ----------------------------------------------- IMAGE UPLOAD END


// ----------------------------------------------- TOGGLE POPUP START

// ----------------------------------------------- TOGGLE POPUP END


