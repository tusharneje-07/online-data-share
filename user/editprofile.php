<?php
session_start();
require_once './db.php';

if (!isset($_SESSION['userInfo'])) {
    header("Location: login.php");
    exit();
}

$userInfo = $_SESSION['userInfo']; // This is now an associative array
$stmt = $pdo->prepare("SELECT * FROM user_information WHERE uid = ?");
$stmt->execute([$userInfo['uid']]);
$row = $stmt->fetch();
if (!$row) {
    echo "Error While Featching Query" . var_dump($stmt->errorInfo()) . var_dump($row);
}
$indian_zodiac = [
    "Mesha (मेष)",
    "Vrishabha (वृषभ)",
    "Mithuna (मिथुन)",
    "Karka (कर्क)",
    "Simha (सिंह)",
    "Kanya (कन्या)",
    "Tula (तूळ)",
    "Vrishchika (वृश्चिक)",
    "Dhanu (धनु)",
    "Makara (मकर)",
    "Kumbha (कुंभ)",
    "Meena (मीन)"
];
$nakshatras = [
    "Ashwini (अश्विनी)",
    "Bharani (भरणी)",
    "Krittika (कृत्तिका)",
    "Rohini (रोहिणी)",
    "Mrigashira (मृगशिरा)",
    "Ardra (आर्द्रा)",
    "Punarvasu (पुनर्वसु)",
    "Pushya (पुष्य)",
    "Ashlesha (आश्लेषा)",
    "Magha (मघा)",
    "Purva Phalguni (पूर्व फाल्गुनी)",
    "Uttara Phalguni (उत्तर फाल्गुनी)",
    "Hasta (हस्त)",
    "Chitra (चित्रा)",
    "Swati (स्वाति)",
    "Vishakha (विशाखा)",
    "Anuradha (अनुराधा)",
    "Jyeshtha (ज्येष्ठा)",
    "Moola (मूला)",
    "Purva Ashadha (पूर्वाषाढ़ा)",
    "Uttara Ashadha (उत्तराषाढ़ा)",
    "Shravana (श्रवण)",
    "Dhanishta (धनिष्ठा)",
    "Shatabhisha (शतभिषा)",
    "Purva Bhadrapada (पूर्व भाद्रपदा)",
    "Uttara Bhadrapada (उत्तर भाद्रपदा)",
    "Revati (रेवती)"
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link href="./output.css" rel="stylesheet">

    <!-- Croper.js -->
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.js"></script>

</head>

<body
    class="bg-theme-light text-dark-text dark:bg-theme-dark dark:text-light-text transition-colors duration-300 font-Ubuntu">

    <!-- Right Menue [DARK MODE TOGGLE]  -->


    <main class="m-4 md:m-8 z-0">
        <!-- Upper Title and HamMenue -->
        <div class="flex p-0 justify-start items-center text-center">

            <div class="pl-2 justify-start items-center text-center">
                <svg onclick="window.location.href='profile'" xmlns="http://www.w3.org/2000/svg" height="24px"
                    viewBox="0 -960 960 960" width="24px"
                    class="cursor-pointer p-0 fill-dark-text dark:fill-light-text">
                    <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z" />
                </svg>

            </div>

            <div class="inline-flex w-full flex-row p-5 pl-3 justify-between text-center">
                <p style="font-size: 1.5rem;" class="font-bold">Edit Account Details</p>

                <div class="mt-[0.6rem]">
                    <div class="group relative cursor-pointer z-10">
                        <div class="flex absolute right-0 top-0 cursor-pointer">
                            <!-- The Checkbox input (hidden) -->
                            <input type="checkbox" id="ham-menu" class="peer hidden cursor-pointer">

                            <!-- Label for the checkbox -->
                            <label for="ham-menu" class="cursor-pointer peer-checked:hidden">
                                <!-- Menu icon that is visible when checkbox is unchecked -->
                                <svg class="dark:fill-light-text fill-dark-text" xmlns="http://www.w3.org/2000/svg"
                                    height="24px" viewBox="0 -960 960 960" width="24px">
                                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                                </svg>
                            </label>

                            <!-- Close icon that is visible when checkbox is checked -->


                            <div
                                class="hidden flex-col bg-container-light dark:bg-container-dark h-auto w-max pt-4 rounded gap-2 peer-checked:flex">

                                <svg class="ml-4 mb-2 block peer-checked:block dark:fill-light-text fill-dark-text"
                                    onclick="document.getElementById('ham-menu').checked = false"
                                    xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px">
                                    <path
                                        d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                                </svg>

                                <div
                                    class="rounded p-4 flex gap-2 flex-row justify-center items-start dark:bg-odd-line-dark bg-odd-line-light">
                                    <p class="mt-0">Dark Mode</p>
                                    <div>
                                        <div id="theme-toggle" class="relative items-center justify-center">
                                            <!-- Hidden Checkbox (peer) -->
                                            <input type="checkbox" id="checkboxInput2" class="peer hidden">

                                            <!-- Toggle Switch -->
                                            <label for="checkboxInput2"
                                                class="relative inline-block w-12 h-6 bg-toggle-btn-grad-off rounded-full cursor-pointer transition-all duration-500 ease-in-out peer-checked:bg-toggle-btn-grad-on">
                                            </label>

                                            <!-- Dot -->
                                            <span
                                                class="absolute top-1 left-1 w-4 h-4 p-1 bg-white rounded-full shadow-md transition-all duration-200 transform peer-checked:translate-x-8 flex justify-center peer-checked:opacity-0 items-center">

                                                <svg class="" xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="black">
                                                    <path
                                                        d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm-.23 72Q400-288 344-344.23q-56-56.22-56-136Q288-560 344.23-616q56.22-56 136-56Q560-672 616-615.77q56 56.22 56 136Q672-400 615.77-344q-56.22 56-136 56ZM216-444H48v-72h168v72Zm696 0H744v-72h168v72ZM444-744v-168h72v168h-72Zm0 696v-168h72v168h-72ZM269-642 166-742l51-55 102 104-50 51Zm474 475L642-268l49-51 103 101-51 51ZM640-691l102-101 51 49-100 103-53-51ZM163-217l105-99 49 47-98 104-56-52Zm317-263Z">
                                                    </path>
                                                </svg>

                                            </span>

                                            <span
                                                class="absolute top-1 left-1 w-4 h-4 p-1 bg-white hidden rounded-full shadow-md transition-all duration-100 transform translate-x-6 justify-center text-xs peer-checked:flex items-center">


                                                <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                    viewBox="0 -960 960 960" width="20px" fill="black">
                                                    <path
                                                        d="M479.96-144Q340-144 242-242t-98-238q0-140 97.93-238t237.83-98q13.06 0 25.65 1 12.59 1 25.59 3-39 29-62 72t-23 92q0 85 58.5 143.5T648-446q49 0 92-23t72-62q2 13 3 25.59t1 25.65q0 139.9-98.04 237.83t-238 97.93Zm.04-72q82 0 148.78-47.07Q695.55-310.15 727-386q-20 5-39.67 8.5Q667.67-374 648-374q-113.86 0-193.93-80.07Q374-534.14 374-648q0-19.67 3.5-39.33Q381-707 386-727q-75.85 31.45-122.93 98.22Q216-562 216-480q0 110 77 187t187 77Zm-14-250Z">
                                                    </path>
                                                </svg>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="mt-4 flex p-3 flex-col gap-10">

            <!-- GENERAL INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">General Info</p>

                <div class="mb-5 flex flex-col w-full md:w-auto justify-center items-center text-center">

                    <!-- Profile -->
                    <div class="flex flex-col gap-5 items-center">

                        <div class="relative">
                            <!-- INPUT-IMG -->
                            <input type="file" id="fileInput" accept=".png, .jpg" class="hidden">
                            <!-- Image Upload Functionality -->
                            <button type="button" id="selectImage">
                                <div
                                    class="absolute z-20 bottom-1 right-1 rounded-full p-3 dark:bg-theme-light bg-theme-dark cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                        width="18px" class="dark:fill-dark-text fill-light-text">
                                        <path
                                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z" />
                                    </svg>
                                </div>
                            </button>
                            <?php
                            echo '<input type="text" id="uid_prf" name="uid_prf" value="' . $row['uid'] . '" hidden>';
                            ?>
                            <div
                                class="relative mt-8 rounded-full overflow-hidden w-40 h-40 border-4 border-theme-dark dark:border-theme-light">
                                <?php
                                $imagePath = "./imgs/" . $row['uid'] . ".png";
                                $defaultImage = "./imgs/def_user.jpg";

                                if (file_exists($imagePath)) {
                                    echo '<img src="' . $imagePath . '" alt="Profile Photo">';
                                } else {
                                    echo '<img src="' . $defaultImage . '" alt="Default Profile Photo">';
                                }
                                ?>

                            </div>
                        </div>


                        <!-- IMAGE UPLOAD CROP FUNCTIONALITY DIV -------------------- START -->
                        <div id="cropModal"
                            class="z-30 fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">

                            <div class="dark:bg-odd-line-dark bg-theme-light rounded-md shadow-lg p-6 w-full max-w-lg">
                                <!-- Crop Area -->
                                <div class="block w-full h-full text-end mb-3">
                                    <button type="button" id="cancelCrop"
                                        class="bg-container-light dark:text-dark-text text-black p-3 rounded-full">

                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                            width="20px" class="dark:fill-dark-text fill-black">
                                            <path
                                                d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                                        </svg>

                                    </button>
                                </div>

                                <div class="relative w-full h-64">
                                    <img id="image" src="" alt="Selected Image" class="max-h-64 object-contain">
                                </div>
                                <!-- Buttons -->
                                <div class="mt-4 flex flex-col gap-2 justify-end space-x-4">

                                    <button id="cropImage"
                                        class="dark:bg-theme-light dark:text-dark-text bg-theme-dark text-light-text py-2 px-4 rounded-md">
                                        Upload
                                    </button>
                                    <span class="text-xs text-important-red">Note: Your Unsaved Data will be
                                        Lost!</span>
                                </div>
                            </div>
                        </div>
                        <!-- IMAGE UPLOAD CROP FUNCTIONALITY DIV -------------------- END -->


                        <!-- Name -->
                        <div class="flex flex-row">
                            <div
                                class="flex justify-center items-center rounded-s-10px dark:bg-theme-light bg-theme-dark">
                                <p class="text-lg font-semibold px-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="dark:fill-dark-text fill-light-text"
                                        height="30px" viewBox="0 -960 960 960" width="24px">
                                        <path
                                            d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                    </svg>
                                </p>
                            </div>
                            <div class="flex flex-row p-4 rounded-e-10px dark:bg-odd-line-dark bg-odd-line-light">
                                <p class="text-lg font-semibold">
                                <form class="w-auto" action="editprofile.php" method="post">


                                    <div class="relative w-full">
                                        <!-- INPUT-1 -->
                                        <?php
                                        echo '<input class="bg-transparent outline-none" type="text" id="inp1" name="inp1" value="' . htmlspecialchars($row['fullname']) . '">'; ?>


                                    </div>
                                    </p>
                            </div>
                        </div>

                    </div>
                    <!-- General Info -->
                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Date of Birth</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                <!-- INPUT-2 -->
                                <p class="text-sm w-full">

                                <div class="relative w-full"
                                    onfocus="showPopup('In Dark Mode Some Icons Might Not Seen Properly!')">
                                    <!-- Input Field -->
                                    <?php echo '<input onfocus="showPopup(\'In Dark Mode Some Icons Might Not Seen Properly!\', 3)" type="date" id="inp2" name="inp2" value="' . date('Y-m-d', strtotime($row['dob'])) . '" class="text-sm w-full p-0 bg-transparent outline-none pr-15 filter-custom-calendar-dark" id="date-picker" />'; ?>


                                </div>
                                </p>


                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Height</p>

                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-s-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-3 -->
                                        <?php echo '<input type="number" id="inp3" name="inp3" value="' . htmlspecialchars($row['height']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp3" id="inp3" step="0.1">'; ?>
                                    </div>
                                    </p>
                                </div>

                                <div
                                    class="bg-theme-dark dark:bg-theme-light flex justify-center items-center text-sm rounded-e-10px">
                                    <p class="font-bold px-3 text-light-text dark:text-dark-text">ft.</p>
                                </div>
                            </div>

                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Weight</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-s-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-4 -->
                                        <?php echo '<input type="number" value="' . htmlspecialchars($row['weight']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp4" id="inp4" step="0.1">'; ?>
                                    </div>
                                    </p>
                                </div>

                                <div
                                    class="bg-theme-dark dark:bg-theme-light flex justify-center items-center text-sm rounded-e-10px">
                                    <p class="font-bold px-3 text-light-text dark:text-dark-text">kg.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- BIRTH INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Birth Info</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">
                    <!-- Birth Info -->
                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Location of Birth</p>
                            <!-- INPUT-5 -->
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark w-full">
                                        <?php echo '<input type="text" value="' . htmlspecialchars($row['location']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp5" id="inp5" oninput="handleInput(event)">'; ?>
                                        <div id="city-suggestions"
                                            class="suggestions flex flex-col gap-1 justify-center dark:bg-theme-light bg-theme-dark dark:text-dark-text text-light-text absolute left-0 top-12 z-20 w-full cursor-pointer max-h-64 overflow-y-auto"> dsjdklj
                                        </div>

                                        <script>
                                            let timeout = null;
                                            let allCities = []; // Stores all fetched city names

                                            async function fetchCities(query) {
                                                if (query.length < 2) return; // Search only after 2+ characters

                                                let apiUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=IN&limit=50`;

                                                try {
                                                    let response = await fetch(apiUrl);
                                                    let data = await response.json();
                                                    allCities = data.map(city => city.display_name); // Store city names as strings

                                                    let refinedCities = fuzzySearch(query, allCities); // Apply typo correction
                                                    showSuggestions(refinedCities);
                                                } catch (error) {
                                                    console.error("Error fetching cities:", error);
                                                }
                                            }

                                            function fuzzySearch(query, cities) {
                                                const options = {
                                                    includeScore: true,
                                                    threshold: 0.4, // Adjust sensitivity (lower = stricter match)
                                                    keys: ["name"]
                                                };
                                                const fuse = new Fuse(cities.map(name => ({ name })), options);
                                                return fuse.search(query).map(result => result.item.name); // Extract city names
                                            }

                                            function showSuggestions(suggestions) {
                                                let dropdown = document.getElementById("city-suggestions");
                                                dropdown.innerHTML = ""; // Clear previous suggestions

                                                if (suggestions.length === 0) {
                                                    dropdown.innerHTML = "<div class='dark:bg-odd-line-light bg-odd-line-dark rounded-lg p-2'>No matching cities found</div>";
                                                    return;
                                                }

                                                suggestions.forEach(city => {
                                                    let option = document.createElement("div");
                                                    let hrline = document.createElement("hr");

                                                    option.classList.add("suggestion");
                                                    option.classList.add("dark:bg-theme-light");
                                                    option.classList.add("bg-theme-dark");
                                                    option.classList.add("border-b");
                                                    // option.classList.add("rounded-lg");
                                                    option.classList.add("p-1");
                                                    option.classList.add("m-1");
                                                    option.textContent = city;
                                                    option.onclick = () => {
                                                        document.getElementById("inp5").value = city;
                                                        dropdown.innerHTML = ""; // Hide suggestions after selection
                                                    };
                                                    dropdown.appendChild(option);
                                                });
                                            }

                                            function handleInput(event) {
                                                clearTimeout(timeout);
                                                let query = event.target.value.trim();
                                                timeout = setTimeout(() => fetchCities(query), 500); // Delay to reduce API calls
                                            }
                                        </script>
                                        <style>
                                        </style>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Time of Birth</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px"
                                onfocus="showPopup('In Dark Mode Some Icons Might Not Seen Properly!')">

                                <!-- INPUT-6 -->
                                <div class="relative w-full"
                                    onfocus="showPopup('In Dark Mode Some Icons Might Not Seen Properly!')">

                                    <?php echo '<input onfocus="showPopup(\'In Dark Mode Some Icons Might Not Be Seen Properly!\')" type="time" id="inp6" name="inp6" value="' . date('H:i', strtotime($row['timeofbirth'])) . '" class="text-sm w-full p-0 bg-transparent outline-none pr-15 filter-custom-calendar-dark" id="time-picker" />'; ?>

                                </div>
                                </p>


                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- JOB/BUSINESS INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Job/Business Info
                </p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">
                    <!-- Birth Info -->
                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Job/Business</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-7 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp7" id="inp7" rows="4">' . htmlspecialchars($row['work']) . '</textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Income</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-s-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-8 -->

                                        <?php echo '<input type="number" id="inp8" name="inp8" value="' . htmlspecialchars($row['income']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full">'; ?>
                                    </div>
                                    </p>
                                </div>

                                <div
                                    class="bg-theme-dark dark:bg-theme-light flex justify-center items-center text-sm rounded-e-10px">
                                    <p class="font-bold px-3 text-light-text dark:text-dark-text">LPA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- EDUCATION INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Education</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">
                    <!-- Birth Info -->
                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Education</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-9 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp9" id="inp9" rows="4">' . htmlspecialchars($row['education']) . '</textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- RELIGION CAST INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Religion & Cast
                    Info</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div
                        class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap md:justify-evenly md:flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Religion</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-10 -->
                                        <?php echo '<input type="text" value="' . htmlspecialchars($row['religion']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp10" id="inp10">'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Caste</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-11 -->
                                        <?php echo '<input type="text" value="' . htmlspecialchars($row['caste']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp11" id="inp11">'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Sub Caste</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-12 -->

                                        <?php echo '<input type="text" value="' . htmlspecialchars($row['subcast']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp12" id="inp12">'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <!-- LAGAN INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Lagan Info</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-start text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Rashi</p>
                            <div class="flex">

                                <div class="relative w-full bg-odd-line-light dark:bg-odd-line-dark rounded-10px"
                                    style="padding: 0.1rem;">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-13 -->

                                        <div class="relative inline-block basic-input w-full rounded-10px">
                                            <!-- Trigger -->
                                            <button type="button"
                                                class="w-full text-left px-4 py-2 bg-transparent rounded-10px focus:outline-none"
                                                onclick="toggleDropdown()">
                                                <span id="dropdownLabel" id="inp13"
                                                    class="text-sm basic-input-placeholder"><?php echo htmlspecialchars($row['rashi']) ?></span>

                                                <?php echo '<input type="text" id="inp13" name="inp13" value="' . htmlspecialchars($row['rashi']) . '" hidden>'; ?>

                                                <svg xmlns="http://www.w3.org/2000/svg" id="basic-dropdown-icon"
                                                    class="h-5 w-5 float-right basic-input-placeholder transition-all duration-150 ease-out"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <ul id="dropdownMenu"
                                                class="absolute left-0 z-10 w-full basic-input-placeholder rounded-10px shadow-md hidden">

                                                <?php

                                                foreach ($indian_zodiac as $rashi) {
                                                    echo '<li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption(\'' . $rashi . '\')">
                                                    ' . $rashi . '
                                                </li>';
                                                }
                                                ?>
                                                <!-- <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption('Data 1')">
                                                    Data 1
                                                </li>
                                                <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption('Data 2')">
                                                    Data 2
                                                </li>
                                                <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption('Data 3')">
                                                    Data 3
                                                </li> -->
                                            </ul>
                                        </div>

                                        <script>
                                            function toggleDropdown() {
                                                const menu = document.getElementById('dropdownMenu');
                                                const icon = document.getElementById('basic-dropdown-icon');
                                                icon.style.transform = menu.classList.contains('hidden') ? 'rotate(180deg)' : 'rotate(0deg)';
                                                menu.classList.toggle('hidden');

                                            }

                                            function selectOption(option) {
                                                const label = document.getElementById('dropdownLabel');
                                                const input = document.getElementById('inp13');
                                                input.value = option;
                                                label.textContent = option;
                                                toggleDropdown();
                                            }
                                        </script>

                                        <!-- <input type="text" id="inp3" name="inp3" value="Libra"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp4" id="inp4"> -->
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Nakshatra</p>
                            <div class="flex">

                                <div class="relative w-full bg-odd-line-light dark:bg-odd-line-dark rounded-10px"
                                    style="padding: 0.1rem;">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-14 -->


                                        <div class="relative inline-block basic-input w-full rounded-10px">
                                            <!-- Trigger -->
                                            <button type="button"
                                                class="w-full text-left px-4 py-2 bg-transparent rounded-10px focus:outline-none flex"
                                                onclick="toggleDropdown2()">
                                                <span id="dropdownLabel2" id="inp14"
                                                    class="text-sm basic-input-placeholder w-full"><?php echo htmlspecialchars($row['nakshatra']) ?></span>

                                                <?php echo '<input type="text" id="inp14" name="inp14" value="' . htmlspecialchars($row['nakshatra']) . '" hidden>'; ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" id="basic-dropdown-icon2"
                                                    class="h-5 w-5 float-right basic-input-placeholder transition-all duration-150 ease-out"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <ul id="dropdownMenu2"
                                                class="absolute left-0 z-10 w-full basic-input-placeholder rounded-10px shadow-md hidden">

                                                <?php
                                                foreach ($nakshatras as $nakshatra) {
                                                    echo '<li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                                onclick="selectOption2(\'' . $nakshatra . '\')">' . $nakshatra . '</li>';
                                                }
                                                ?>


                                                <!-- <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption2('Data 2')">
                                                    Data 2
                                                </li>
                                                <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption2('Data 3')">
                                                    Data 3
                                                </li> -->
                                            </ul>
                                        </div>

                                        <script>
                                            function toggleDropdown2() {
                                                const menu = document.getElementById('dropdownMenu2');
                                                const icon = document.getElementById('basic-dropdown-icon2');
                                                icon.style.transform = menu.classList.contains('hidden') ? 'rotate(180deg)' : 'rotate(0deg)';
                                                menu.classList.toggle('hidden');

                                            }

                                            function selectOption2(option) {
                                                const label = document.getElementById('dropdownLabel2');
                                                const input = document.getElementById('inp14');
                                                input.value = option;
                                                label.textContent = option;
                                                toggleDropdown2();
                                            }
                                        </script>

                                        <!-- <input type="text" id="inp3" name="inp3" value="Swati"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp4" id="inp4"> -->
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="inline-flex justify-evenly gap-4 md:mt-5 mt-3 w-full px-4 flex-nowrap">
                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Birth Name (Navras Nav)</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-15 -->
                                        <!-- birthname -->

                                        <?php echo '<input type="text" value="' . htmlspecialchars($row['birthname']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp15" id="inp15">'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <!-- FAMILY INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Family Info</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Father's Name & Occupation</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-16 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp16" id="inp16" rows="4">' . htmlspecialchars($row['father']) . '</textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Mother's Name & Occupation</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-17 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp17" id="inp17" rows="4">' . htmlspecialchars($row['mother']) . '</textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Brother's Name & Occupation</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-18 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp18" id="inp18" rows="4">' . htmlspecialchars($row['brother']) . '</textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Sister's Name & Occupation</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-19 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp19" id="inp19" rows="4">' . htmlspecialchars($row['sister']) . ' </textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>


            </div>

            <!-- ADDRESS AND CONTACT INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Address & Contact
                    Info</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Address</p>
                            <div class="flex">
                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-16 -->
                                        <?php echo '<textarea
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full resize-none"
                                            name="inp20" id="inp20" rows="4">' . htmlspecialchars($row['address']) . '</textarea>'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Contact Number</p>
                            <div class="flex">

                                <div
                                    class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-10px">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-21 -->
                                        <?php echo '<input type="text" value="' . htmlspecialchars($row['contactno']) . '"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp21" id="inp21">'; ?>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <!-- PRIVACY AND SHAREING -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Privacy & Sharing
                    Info</p>

                <div
                    class="flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Link Sharing</p>
                            <div class="flex w-full">

                                <div
                                    class="bg-theme-dark dark:bg-theme-light p-2 rounded-s-10px flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                        width="18px" class="fill-light-text dark:fill-dark-text">
                                        <path
                                            d="M680-80q-50 0-85-35t-35-85q0-6 3-28L282-392q-16 15-37 23.5t-45 8.5q-50 0-85-35t-35-85q0-50 35-85t85-35q24 0 45 8.5t37 23.5l281-164q-2-7-2.5-13.5T560-760q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35q-24 0-45-8.5T598-672L317-508q2 7 2.5 13.5t.5 14.5q0 8-.5 14.5T317-452l281 164q16-15 37-23.5t45-8.5q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-200q0-17-11.5-28.5T680-240q-17 0-28.5 11.5T640-200q0 17 11.5 28.5T680-160ZM200-440q17 0 28.5-11.5T240-480q0-17-11.5-28.5T200-520q-17 0-28.5 11.5T160-480q0 17 11.5 28.5T200-440Zm480-280q17 0 28.5-11.5T720-760q0-17-11.5-28.5T680-800q-17 0-28.5 11.5T640-760q0 17 11.5 28.5T680-720Zm0 520ZM200-480Zm480-280Z" />
                                    </svg>
                                </div>

                                <div class="w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-e-10px">

                                    <p class="p-1 mb-2 text-xs">Enable this feature to share your profile with ease!
                                        Generate a unique URL for your profile, allowing others to view your information
                                        anytime, anywhere.</p>

                                    <div
                                        class="border-t border-container-dark dark:border-container-light flex gap-2.5 justify-start items-center">

                                        <!-- INPUT-22 -->

                                        <style>
                                            /* From Uiverse.io by catraco */
                                            /* Hide the default checkbox */
                                            .container input {
                                                position: absolute;
                                                opacity: 0;
                                                cursor: pointer;
                                            }

                                            .container {
                                                display: flex;
                                                justify-content: center;
                                                align-items: center;
                                                cursor: pointer;
                                                user-select: none;
                                                border-radius: 50%;
                                            }

                                            /* Create a custom checkbox */
                                            .checkmark {
                                                height: 1em;
                                                width: 1em;
                                                transition: .3s;
                                                transform: scale(0);
                                                border-radius: 50%;
                                            }

                                            /* When the checkbox is checked, add a blue background */
                                            .container input:checked~.checkmark {
                                                background-color: #20c580;
                                                transform: scale(1);
                                            }

                                            /* Create the checkmark/indicator (hidden when not checked) */
                                            .checkmark:after {
                                                content: "";
                                                position: absolute;
                                                display: none;
                                            }

                                            /* Show the checkmark when checked */
                                            .container input:checked~.checkmark:after {
                                                display: flex;
                                            }

                                            .container input:checked~.celebrate {
                                                display: flex;
                                            }

                                            /* Style the checkmark/indicator */
                                            .container .checkmark:after {
                                                left: 0.35em;
                                                top: 0.23em;
                                                width: 0.30em;
                                                height: 0.5em;
                                                border: solid white;
                                                border-width: 0 0.15em 0.15em 0;
                                                transform: rotate(45deg);
                                            }

                                            .container .celebrate {
                                                position: absolute;
                                                transform-origin: center;
                                                animation: kfr-celebrate .4s;
                                                animation-fill-mode: forwards;
                                                display: none;
                                                stroke: #20c580;
                                            }

                                            @keyframes kfr-celebrate {
                                                0% {
                                                    transform: scale(0);
                                                }

                                                50% {
                                                    opacity: 1;
                                                }

                                                100% {
                                                    transform: scale(1.2);
                                                    opacity: 0;
                                                    display: none;
                                                }
                                            }
                                        </style>

                                        <!-- From Uiverse.io by catraco -->
                                        <label
                                            class="container relative w-min flex justify-center items-center mt-2 dark:bg-theme-light bg-slate-400">

                                            <?php
                                            if ($row['linkshare']) {
                                                echo '<input name="inp22" id="inp22" type="checkbox" checked>';
                                            } else {
                                                echo '<input name="inp22" id="inp22" type="checkbox">';
                                            }
                                            ?>
                                            <div class="checkmark"></div>
                                            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg"
                                                class="celebrate">
                                                <polygon points="0,0 10,10"></polygon>
                                                <polygon points="0,25 10,25"></polygon>
                                                <polygon points="0,50 10,40"></polygon>
                                                <polygon points="50,0 40,10"></polygon>
                                                <polygon points="50,25 40,25"></polygon>
                                                <polygon points="50,50 40,40"></polygon>
                                            </svg>
                                        </label>

                                        <!-- <input class="size-4 mt-2 rounded-lg p-4" type="checkbox" id="" name=""> -->
                                        <p class="mt-2 text-sm">Link Sharing</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="mb-5 w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Global Search</p>
                            <div class="flex">

                                <div class="bg-important-red p-2 rounded-s-10px flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                        width="18px" class="fill-light-text">
                                        <path
                                            d="M440-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T520-640q0-33-23.5-56.5T440-720q-33 0-56.5 23.5T360-640q0 33 23.5 56.5T440-560ZM884-20 756-148q-21 12-45 20t-51 8q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 27-8 51t-20 45L940-76l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29Zm-540 40v-111q0-34 17-63t47-44q51-26 115-44t142-18q-12 18-20.5 38.5T407-359q-60 5-107 20.5T221-306q-10 5-15.5 14.5T200-271v31h207q5 22 13.5 42t20.5 38H120Zm320-480Zm-33 400Z" />
                                    </svg>
                                </div>

                                <div class="w-full bg-odd-line-light dark:bg-odd-line-dark p-halfp rounded-e-10px">

                                    <p class="p-1 mb-2 text-xs">By enabling this feature, your profile will become
                                        searchable by your name, making it easier for others to find your details.</p>

                                    <div
                                        class="border-t border-container-dark dark:border-container-light flex gap-2.5 justify-start items-center">

                                        <!-- INPUT-22 -->
                                        <label
                                            class="container relative w-min flex justify-center items-center mt-2 dark:bg-theme-light bg-slate-400">
                                            <?php
                                            if ($row['globalsearch']) {
                                                echo '<input name="inp23" id="inp23" type="checkbox" checked>';
                                            } else {
                                                echo '<input name="inp23" id="inp23" type="checkbox">';
                                            }
                                            ?>
                                            <div class="checkmark"></div>
                                            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg"
                                                class="celebrate">
                                                <polygon points="0,0 10,10"></polygon>
                                                <polygon points="0,25 10,25"></polygon>
                                                <polygon points="0,50 10,40"></polygon>
                                                <polygon points="50,0 40,10"></polygon>
                                                <polygon points="50,25 40,25"></polygon>
                                                <polygon points="50,50 40,40"></polygon>
                                            </svg>
                                        </label>
                                        <!-- <input class="mt-2 size-4 rounded-lg p-4" type="checkbox" id="" name=""> -->
                                        <p class="mt-2 text-sm">Global Search</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <!-- MARITAL STATUS INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Marital Status</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-start text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Marital Status</p>
                            <p class="text-xs ml-2 font-bold text-important-red">
                                If You Set Marital Status to 'MARRIED' Your Inforamtion Will Not Be Sharable
                                Anymore.
                            </p>
                            <div class="flex">

                                <div class="relative w-full bg-odd-line-light dark:bg-odd-line-dark rounded-10px"
                                    style="padding: 0.1rem;">

                                    <p class="text-sm w-full">
                                    <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                        <!-- INPUT-13 -->

                                        <div class="relative inline-block basic-input w-full rounded-10px">
                                            <!-- Trigger -->
                                            <button type="button"
                                                class="w-full text-left px-4 py-2 bg-transparent rounded-10px focus:outline-none"
                                                onclick="toggleDropdown3()">
                                                <span id="dropdownLabel3" class="text-sm basic-input-placeholder"
                                                    id="inp24">
                                                    <?php echo $row['maritalstatus'] ?>
                                                </span>
                                                <!-- INPUT-23 -->
                                                <?php echo '<input type="text" id="inp24" name="inp24" value="' . $row['maritalstatus'] . '" hidden>'; ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" id="basic-dropdown-icon3"
                                                    class="h-5 w-5 float-right basic-input-placeholder transition-all duration-150 ease-out"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <ul id="dropdownMenu3"
                                                class="absolute left-0 z-10 w-full basic-input-placeholder rounded-10px shadow-md hidden">
                                                <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption3('UNMARRIED')">
                                                    UNMARRID
                                                </li>
                                                <li class="px-4 py-2 bg-container-light hover:bg-odd-line-light dark:hover:bg-odd-line-dark dark:bg-theme-dark cursor-pointer text-sm"
                                                    onclick="selectOption3('MARRIED')">
                                                    MARRIED
                                                </li>

                                            </ul>
                                        </div>

                                        <script>
                                            function toggleDropdown3() {
                                                const menu = document.getElementById('dropdownMenu3');
                                                const icon = document.getElementById('basic-dropdown-icon3');
                                                icon.style.transform = menu.classList.contains('hidden') ? 'rotate(180deg)' : 'rotate(0deg)';
                                                menu.classList.toggle('hidden');

                                            }

                                            function selectOption3(option) {
                                                const label = document.getElementById('dropdownLabel3');
                                                const input = document.getElementById('inp24');
                                                input.value = option;
                                                label.textContent = option;
                                                toggleDropdown3();
                                            }
                                        </script>

                                        <!-- <input type="text" id="inp3" name="inp3" value="Libra"
                                            class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                            name="inp4" id="inp4"> -->
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- SAVE OR EXPORT INFORMATOPN -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Save or Export
                    Information
                </p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-start gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="flex flex-col text-start gap-1 w-full">
                            <div class="flex gap-3 w-full">

                                <Button name="save-information"
                                    class="flex flex-row gap-2 bg-odd-line-light dark:bg-odd-line-dark rounded-10px h-full w-full ">
                                    <div
                                        class="flex items-center bg-theme-dark dark:bg-theme-light p-halfp rounded-s-10px h-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960"
                                            width="22px" class="fill-light-text dark:fill-dark-text ">
                                            <path
                                                d="M820-671.54v459.23Q820-182 799-161q-21 21-51.31 21H212.31Q182-140 161-161q-21-21-21-51.31v-535.38Q140-778 161-799q21-21 51.31-21h459.23L820-671.54ZM760-646 646-760H212.31q-5.39 0-8.85 3.46t-3.46 8.85v535.38q0 5.39 3.46 8.85t8.85 3.46h535.38q5.39 0 8.85-3.46t3.46-8.85V-646ZM480-269.23q41.54 0 70.77-29.23Q580-327.69 580-369.23q0-41.54-29.23-70.77-29.23-29.23-70.77-29.23-41.54 0-70.77 29.23Q380-410.77 380-369.23q0 41.54 29.23 70.77 29.23 29.23 70.77 29.23ZM255.39-564.62h328.45v-139.99H255.39v139.99ZM200-646v446-560 114Z" />
                                        </svg>
                                    </div>

                                    <span class="text-sm p-3">
                                        Save Information
                                    </span>
                                </Button>


                                <Button
                                    class="flex flex-row gap-2 bg-odd-line-light dark:bg-odd-line-dark rounded-10px h-full  w-full items-center">
                                    <div
                                        class="flex items-center bg-theme-dark dark:bg-theme-light p-halfp rounded-s-10px h-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960"
                                            width="22px" class="fill-light-text dark:fill-dark-text ">
                                            <path
                                                d="M480-480ZM210.08-79.23 168.31-122l128-128H191.69v-60h206.39v206.38h-60v-103.61l-128 128ZM485.77-100v-60h221.92q5.39 0 8.85-3.46t3.46-8.85V-620H540v-180H252.31q-5.39 0-8.85 3.46t-3.46 8.85v390h-60v-390q0-29.92 21.19-51.12Q222.39-860 252.31-860H570l210 210v477.69q0 29.92-21.19 51.12Q737.61-100 707.69-100H485.77Z" />
                                        </svg>
                                    </div>

                                    <span class="text-sm p-3">
                                        Export in PDF
                                    </span>
                                </Button>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
            </form>




        </div>

    </main>

    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Popup -->
    <div id="popup"
        class="hidden z-30 fixed top-10 left-1/2 transform -translate-x-1/2 dark:bg-theme-light bg-theme-dark rounded-md w-96">
        <!-- Popup Content -->
        <div class="flex justify-between items-center px-4 py-3">
            <p id="popupMessage" class="text-light-text dark:text-dark-text font-medium"></p>
            <button id="closeBtn" class="text-light-text dark:text-dark-text hover:text-red-500">
                &times;
            </button>
        </div>
        <!-- Animated Border -->
        <div id="border-loader" class="h-1 rounded-md dark:bg-gray-400 bg-odd-line-dark animate-[grow_2s_linear]"></div>
    </div>



    <script>
        const popup = document.getElementById("popup");
        const popupMessage = document.getElementById("popupMessage");
        const closeBtn = document.getElementById("closeBtn");
        const borderloader = document.getElementById('border-loader');

        let timeout;

        // Function to show popup
        function showPopup(message, seconds) {
            popupMessage.textContent = message; // Set the message dynamically
            popup.classList.remove("hidden");

            // Close after 2 seconds
            timeout = setTimeout(() => {
                closePopup();
            }, seconds * 1000);
        }

        // Function to close popup
        function closePopup() {
            popup.classList.add("hidden");
            clearTimeout(timeout);
        }

        // Event Listener for close button
        closeBtn.addEventListener("click", closePopup);
    </script>


    <!-- MOBILE NAV -->
    <div onclick="toggleNavBar()" id="toggleBtn"
        class="cursor-pointer rounded-md flex justify-center items-center fixed bottom-0 right-10 w-12 h-10 -translate-y-20 border bg-theme-light dark:bg-theme-dark border-theme-dark dark:border-theme-light">
        <svg id="navtoggleicon" class="dark:fill-theme-light fill-theme-dark transition-all duration-500"
            xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
            <path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z" />
        </svg>
    </div>





    <div id="navbar" class="block fixed bg-odd-line-light dark:bg-odd-line-dark bottom-0 w-full p-4">
        <div class="flex flex-row justify-around">
            <!-- Wishlist -->
            <div class="inline-flex flex-col justify-center text-center items-center gap-2 cursor-pointer">

                <div
                    class="p-2 dark:hover:shadow-dark-theme-shadow hover:shadow-light-theme-shadow dark:hover:border hover:border bg-container-light dark:bg-container-dark  border-dark-text dark:border-light-text h-8 w-8 rounded-full flex items-center justify-center transition-all duration-300 ease-in-out">
                    <svg class="fill-dark-text dark:fill-light-text" xmlns="http://www.w3.org/2000/svg" height="20px"
                        viewBox="0 -960 960 960" width="24px">
                        <path
                            d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z" />
                    </svg>
                </div>

                <p class="text-xs">Wishlist</p>
            </div>

            <!-- Account -->
            <div class="inline-flex flex-col justify-center text-center items-center gap-2 cursor-pointer">

                <div
                    class="overflow-hidden border dark:hover:shadow-dark-theme-shadow hover:shadow-light-theme-shadow dark:hover:border hover:border bg-container-light dark:bg-container-dark  border-dark-text dark:border-light-text h-8 w-8 rounded-full flex items-center justify-center transition-all duration-300 ease-in-out">
                    <?php
                    echo "<img src='" . $userInfo['picture'] . "' alt=''
                            onerror=\"this.style.display='none'; document.getElementById('profile-svg').style.display='block'\"
                            onload=\"this.style.display='block'; document.getElementById('profile-svg').style.display='none';\">";
                    ?>



                    <svg id="profile-svg" class="fill-dark-text dark:fill-light-text" xmlns="http://www.w3.org/2000/svg"
                        height="20px" viewBox="0 -960 960 960" width="24px">
                        <path
                            d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                    </svg>
                </div>

                <p class="text-xs">Account</p>
            </div>

        </div>
    </div>


    <script src="./theme-toggle.js"></script>
    <!-- <script src="./cityFetch.js"></script> -->

</body>
<?php
if (isset($_POST['save-information'])) {
    $data = json_decode(json_encode($_POST), true);

    $keys = ['inp22', 'inp23'];

    foreach ($keys as $key) {
        $data[$key] = array_key_exists($key, $data) ? "1" : "0";
    }

    $stmt = $pdo->prepare("UPDATE user_information SET `fullname`=?,`dob`=?,`height`=?,`weight`=?,`location`=?,`timeofbirth`=?,`work`=?,`income`=?,`education`=?,`religion`=?,`caste`=?,`subcast`=?,`rashi`=?,`nakshatra`=?,`birthname`=?,`father`=?,`mother`=?,`brother`=?,`sister`=?,`address`=?,`contactno`=?,`linkshare`=?,`globalsearch`=?,`maritalstatus`=? WHERE uid = ?");




    if (
        $stmt->execute([
            $data['inp1'],
            $data['inp2'],
            $data['inp3'],
            $data['inp4'],
            $data['inp5'],
            $data['inp6'],
            $data['inp7'],
            $data['inp8'],
            $data['inp9'],
            $data['inp10'],
            $data['inp11'],
            $data['inp12'],
            $data['inp13'],
            $data['inp14'],
            $data['inp15'],
            $data['inp16'],
            $data['inp17'],
            $data['inp18'],
            $data['inp19'],
            $data['inp20'],
            $data['inp21'],
            $data['inp22'],
            $data['inp23'],
            $data['inp24'],
            $userInfo['uid'],
        ])
    ) {
        echo '<script>alert("User Data is Updated"); window.location.href = "profile";</script>';
    } else {
        echo '<script>alert("Error While Updating Information");</script>';
    }
    unset($_POST['save-information']);
    // header('Location: profile');

}
?>

</html>