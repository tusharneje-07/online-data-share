<?php
session_start();
require_once __DIR__ . '/../google_config.php';
require_once './db.php';

// Redirect to login if access token is missing
if (isset($_SESSION['access_token'])) {
    try {
        // Set the access token to the client
        $client->setAccessToken($_SESSION['access_token']);

        // **Check if the token is expired**
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $_SESSION['access_token'] = $client->getAccessToken(); // Update session with new token
        }

        // Now you can safely make API calls
        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_accounts WHERE username = ?");
        $stmt->execute([$userInfo->email]);

        // IF NEW USER 
        while ($row = $stmt->fetch()) {
            if ($row['COUNT(*)'] <= 0) {
                $hased = hash('sha256', $userInfo->email);
                $password = hash('sha256', "$userInfo->email|$userInfo->name|PASSWORD");
                $stmt = $pdo->prepare("INSERT INTO `user_accounts`(`uid`, `username`, `password`, `acc_type`, `mobile_number`) VALUES (?,?,?,?,?)");
                $stmt->execute([$hased, $userInfo->email, $password, 'GACC', '12345']);

                // Setting User in user_info table
                $stmt = $pdo->prepare("INSERT INTO `user_information`(`uid`, `owner`, `fullname`, `dob`, `height`, `weight`, `location`, `timeofbirth`, `work`, `income`, `education`, `religion`, `caste`, `subcast`, `rashi`, `nakshatra`, `birthname`, `father`, `mother`, `brother`, `sister`, `address`, `contactno`, `linkshare`, `globalsearch`, `maritalstatus`, `template`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute([
                    $hased,
                    $userInfo->email,
                    'NA', // Full Name
                    'NA', // Date of Birth
                    0.0, // Height
                    0.0, // Weight
                    'NA', // Location
                    'NA', // Time of Birth
                    'NA', // Time of Birth
                    'NA', // Income
                    'NA', // Education
                    'NA', // Religion
                    'NA', // Caste
                    'NA', // Sub-cast
                    'NA', // Rashi
                    'NA', // Nakshatra
                    'NA', // Birth Name
                    'NA', // Father's Information
                    'NA', // Mother's Information
                    'NA', // Brother's Information
                    'NA', // Sister's Information
                    'NA', // Address Information
                    'NA', // Contact Information
                    0, // Link Share
                    0, // Global Search
                    'UNMARRIED', // Marital Status
                    'TEMP0', // Template
                ]);


                $_SESSION['userInfo'] = [
                    'uid' => $hased,
                    'auth' => true,
                    'name' => $userInfo->name,
                    'email' => $userInfo->email,
                    'picture' => $userInfo->picture
                ];
                header('Location: editprofile');

            }
        }

        $stmt = $pdo->prepare("SELECT `uid` FROM user_accounts WHERE username = ?");
        $stmt->execute([$userInfo->email]);
        $row = $stmt->fetch();

        $_SESSION['userInfo'] = [
            'uid' => $row['uid'],
            'auth' => true,
            'name' => $userInfo->name,
            'email' => $userInfo->email,
            'picture' => $userInfo->picture
        ];
        $userInfo = $_SESSION['userInfo'];

    } catch (Exception $e) {
        // Redirect to Error Page.
        echo $e;
        $_SESSION['userInfo'] = [
            'auth' => true,
            'name' => "User Name",
            'email' => "User Name",
            'picture' => ""
        ];
        $userInfo = $_SESSION['userInfo'];
    }
} else if (isset($_COOKIE['user_access'])) {
    $_SESSION['userInfo'] = [
        'uid' => base64_decode($_COOKIE['user_access']),
        'auth' => true,
        'name' => "User Name",
        'email' => "User Name",
        'picture' => ""
    ];
    $userInfo = $_SESSION['userInfo'];
} else {
    header("Location: login");
    exit();
}


$uid = $userInfo['uid'];
$stmt = $pdo->prepare("SELECT * FROM user_information WHERE uid = ?");
if (!$stmt->execute([$uid])) {
    echo "Error -<>";
}

$row = $stmt->fetch();
$data = array();
foreach ($row as $d) {
    array_push($data, $d);
}
if (count($data) <= 0) {
    // header("Location: profile");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link href="./output.css" rel="stylesheet">
</head>

<body
    class="bg-theme-light text-dark-text dark:bg-theme-dark dark:text-light-text transition-colors duration-300 font-Ubuntu">


    <main class="m-4 md:m-8 z-0">
        <!-- Upper Title and HamMenue -->
        <div class="inline-flex w-full flex-row justify-between p-5">
            <p style="font-size: 1.5rem;" class="font-bold">Account</p>

            <div class="mt-[0.5rem]">
                <div class="group relative cursor-pointer z-10 w-1/4">
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
                            class="hidden flex-col bg-container-light dark:bg-container-dark h-auto w-max pt-4 rounded peer-checked:flex">

                            <svg class="ml-4 mb-2 block peer-checked:block dark:fill-light-text fill-dark-text"
                                onclick="document.getElementById('ham-menu').checked = false"
                                xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
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


                            <div
                                class="rounded flex gap-2 flex-row justify-center items-start dark:bg-odd-line-dark bg-odd-line-light">
                                <form class="w-full" action="../deleteacc.php">

                                    <button
                                        class="flex bg-important-red px-3 w-full py-2 items-center gap-1 rounded-md text-white cursor-pointer hover:bg-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="h-5"
                                            fill="#fff">
                                            <path
                                                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>


        <div class="flex p-3 flex-col gap-5">

            <div class="relative border-theme-dark dark:border-theme-light box-border w-full">
                <div class="w-full flex flex-row text-start">
                    <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-s-10px w-full">
                        <input type="text" name="searchQ" id="searchQ" class="w-full bg-transparent outline-none"
                            placeholder="Search Here..." oninput="fetchUserData(this.value)">

                        <script>
                            async function fetchUserData(name) {
                                try {
                                    // Making the POST request to fetch_user.php
                                    const response = await fetch('fetch_user.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({ userId: 1, name: name }) // Example payload
                                    });

                                    // Check if the response is successful
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }

                                    // Parse the response data
                                    const data = await response.json();

                                    // Log the data to the console
                                    console.log('User Data:', data);
                                    createUserContainers(data);
                                } catch (error) {
                                    console.error('Error fetching user data:', error);
                                }
                            }




                            function createUserContainers(response) {
                                const foundedUserDiv = document.getElementById('foundeduser');
                                foundedUserDiv.innerHTML = '';

                                if (response.status && response.hasrow && response.users.length > 0) {
                                    response.users.forEach(user => {
                                        const userContainer = document.createElement('div');
                                        userContainer.classList.add('bg-odd-line-light', 'dark:bg-odd-line-dark', 'p-3', 'rounded-10px', 'w-full', 'overflow-hidden', 'flex', 'flex-row', 'flex-wrap', 'gap-4', 'items-start', 'justify-start', 'md:w-[49.5%]', 'relative');

                                        const iconDiv = document.createElement('div');
                                        iconDiv.classList.add('absolute', 'bg-theme-dark', 'dark:bg-theme-light', 'w-8', 'h-8', 'top-0', 'right-0', 'rounded-tr-10px', 'rounded-bl-10px', 'text-black', 'flex', 'justify-center', 'items-center');
                                        const iconSVG = document.createElement('svg');
                                        iconSVG.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                                        iconSVG.setAttribute('viewBox', '0 -960 960 960');
                                        iconSVG.setAttribute('width', '24px');
                                        iconSVG.classList.add('fill-light-text', 'dark:fill-dark-text');
                                        const iconPath = document.createElement('path');
                                        iconPath.setAttribute('d', 'm263-161.54 57.31-246.77-191.46-165.92 252.61-21.92L480-828.84l98.54 232.69 252.61 21.92-191.46 165.92L697-161.54 480-292.46 263-161.54Z');
                                        iconSVG.appendChild(iconPath);
                                        iconDiv.appendChild(iconSVG);

                                        const imgDiv = document.createElement('div');
                                        imgDiv.classList.add('bg-white', 'rounded-full', 'h-24', 'w-24', 'sm:w-28', 'md:h-28', 'overflow-hidden');
                                        const img = document.createElement('img');
                                        img.setAttribute('src', `./imgs/${user.uid}.png`);
                                        img.setAttribute('alt', user.fullname);
                                        imgDiv.appendChild(img);

                                        const textDiv = document.createElement('div');
                                        textDiv.classList.add('flex', 'flex-col', 'gap-1');

                                        const fullName = document.createElement('p');
                                        fullName.classList.add('font-bold');
                                        fullName.textContent = user.fullname;

                                        const dob = document.createElement('p');
                                        dob.classList.add('text-sm', 'flex', 'flex-row', 'gap-1', 'justify-start', 'items-center');
                                        const dobSVG = document.createElement('svg');
                                        dobSVG.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                                        dobSVG.setAttribute('height', '24px');
                                        dobSVG.setAttribute('viewBox', '0 -960 960 960');
                                        dobSVG.setAttribute('width', '24px');
                                        dobSVG.classList.add('fill-dark-text', 'dark:fill-light-text');
                                        const dobPath = document.createElement('path');
                                        dobPath.setAttribute('d', 'M174.62-100q-14.7 0-24.66-10.35-9.96-10.34-9.96-25.04v-195.38q0-29.92 21.19-51.12 21.2-21.19 51.12-21.19H220v-163.07q0-29.93 21.19-51.12 21.2-21.19 51.12-21.19H450v-58.77q-17.61-11.62-28.81-27.27Q410-740.15 410-761.85q0-13.46 5.23-26.03 5.23-12.58 15.69-23.04L480-860l49.08 49.08q10.46 10.46 15.69 23.04 5.23 12.57 5.23 26.03 0 21.7-11.19 37.35-11.2 15.65-28.81 27.27v58.77h157.69q29.92 0 51.12 21.19Q740-596.08 740-566.15v163.07h7.69q29.92 0 51.12 21.19Q820-360.69 820-330.77v195.38q0 14.7-10.35 25.04Q799.31-100 784.61-100H174.62ZM280-403.08h400v-163.07q0-5.39-3.46-8.85t-8.85-3.46H292.31q-5.39 0-8.85 3.46t-3.46 8.85v163.07ZM200-160h560v-170.77q0-5.39-3.46-8.85t-8.85-3.46H212.31q-5.39 0-8.85 3.46t-3.46 8.85V-160Zm80-243.08h400-400ZM200-160h560-560Zm540-243.08H220h520Z');
                                        dobSVG.appendChild(dobPath);
                                        dob.appendChild(dobSVG);
                                        dob.appendChild(document.createTextNode(user.dob));

                                        const education = document.createElement('p');
                                        education.classList.add('text-sm', 'flex', 'flex-row', 'gap-1', 'justify-start', 'items-start');
                                        const educationSVG = document.createElement('svg');
                                        educationSVG.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                                        educationSVG.setAttribute('height', '24px');
                                        educationSVG.setAttribute('viewBox', '0 -960 960 960');
                                        educationSVG.setAttribute('width', '24px');
                                        educationSVG.classList.add('fill-dark-text', 'dark:fill-light-text');
                                        const educationPath = document.createElement('path');
                                        educationPath.setAttribute('d', 'M480-166.16 220-307.39v-216.92L81.54-600 480-816.92 878.46-600v287.69h-60v-254.46L740-524.31v216.92L480-166.16ZM480-452l273.62-148L480-748 206.38-600 480-452Zm0 217.54 200-108v-149.85L480-383.15 280-492.31v149.85l200 108ZM480-452Zm0 72.31Zm0 0Z');
                                        educationSVG.appendChild(educationPath);
                                        education.appendChild(educationSVG);
                                        education.appendChild(document.createTextNode(user.education));

                                        textDiv.appendChild(fullName);
                                        textDiv.appendChild(dob);
                                        textDiv.appendChild(education);

                                        // userContainer.appendChild(iconDiv);
                                        userContainer.appendChild(imgDiv);
                                        userContainer.appendChild(textDiv);

                                        foundedUserDiv.appendChild(userContainer);
                                    });
                                } else {
                                    foundedUserDiv.innerHTML = '<p>No users found.</p>';
                                }
                            }

                        </script>
                    </div>
                    <div
                        class="bg-theme-dark text-light-text dark:bg-theme-light dark:text-light-text w-10 rounded-e-10px flex justify-center items-center cursor-pointer">
                        <svg class="fill-light-text dark:fill-dark-text w-5" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 -960 960 960">
                            <path
                                d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                        </svg>
                    </div>
                </div>
            </div>


            <div class="border-theme-dark dark:border-theme-light w-full flex flex-wrap">
                <div class="w-full flex flex-row text-start flex-wrap gap-2" id="foundeduser">
                </div>
            </div>



            <p class="text-lg font-bold flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-dark-text dark:fill-light-text"><path d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z"/></svg>
                Saved Profiles
            </p>

            <div class="border-theme-dark dark:border-theme-light w-full flex flex-wrap">

                <div class="w-full flex flex-row text-start flex-wrap gap-2" id="foundeduser">
                    <div
                        class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px w-full overflow-hidden flex flex-row flex-wrap gap-4 items-start justify-start md:w-[49.5%] relative">
                        <div class="flex flex-row gap-3">
                            <div
                                class="absolute bg-theme-dark dark:bg-theme-light w-8 h-8 top-0 right-0 rounded-tr-10px rounded-bl-10px text-black flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-light-text dark:fill-dark-text"
                                    viewBox="0 -960 960 960" width="24px">
                                    <path
                                        d="m263-161.54 57.31-246.77-191.46-165.92 252.61-21.92L480-828.84l98.54 232.69 252.61 21.92-191.46 165.92L697-161.54 480-292.46 263-161.54Z" />
                                </svg>
                            </div>

                            <!-- IMG -->
                            <div class="bg-white rounded-full h-24 w-24 sm:w-28 md:h-28 overflow-hidden">
                                <img src="./imgs/14bdbcefa36b1da6cc0d6b71d19172a3ad016a4769efb6dac88c15223725159d.png"
                                    alt="">
                            </div>

                            <div class="flex flex-col gap-1">

                                <p class=" font-bold">
                                    Tushar Sadashiv Neje
                                </p>
                                <p class=" text-sm flex flex-row gap-1 justify-start items-center">

                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="fill-dark-text dark:fill-light-text">
                                        <path
                                            d="M174.62-100q-14.7 0-24.66-10.35-9.96-10.34-9.96-25.04v-195.38q0-29.92 21.19-51.12 21.2-21.19 51.12-21.19H220v-163.07q0-29.93 21.19-51.12 21.2-21.19 51.12-21.19H450v-58.77q-17.61-11.62-28.81-27.27Q410-740.15 410-761.85q0-13.46 5.23-26.03 5.23-12.58 15.69-23.04L480-860l49.08 49.08q10.46 10.46 15.69 23.04 5.23 12.57 5.23 26.03 0 21.7-11.19 37.35-11.2 15.65-28.81 27.27v58.77h157.69q29.92 0 51.12 21.19Q740-596.08 740-566.15v163.07h7.69q29.92 0 51.12 21.19Q820-360.69 820-330.77v195.38q0 14.7-10.35 25.04Q799.31-100 784.61-100H174.62ZM280-403.08h400v-163.07q0-5.39-3.46-8.85t-8.85-3.46H292.31q-5.39 0-8.85 3.46t-3.46 8.85v163.07ZM200-160h560v-170.77q0-5.39-3.46-8.85t-8.85-3.46H212.31q-5.39 0-8.85 3.46t-3.46 8.85V-160Zm80-243.08h400-400ZM200-160h560-560Zm540-243.08H220h520Z" />
                                    </svg>
                                    15/07/2005
                                </p>
                                <p class=" text-sm flex flex-row gap-1 justify-start items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="fill-dark-text dark:fill-light-text">
                                        <path
                                            d="M480-166.16 220-307.39v-216.92L81.54-600 480-816.92 878.46-600v287.69h-60v-254.46L740-524.31v216.92L480-166.16ZM480-452l273.62-148L480-748 206.38-600 480-452Zm0 217.54 200-108v-149.85L480-383.15 280-492.31v149.85l200 108ZM480-452Zm0 72.31Zm0 0Z" />
                                    </svg>
                                    B.Tech Computer Engineering
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px w-full overflow-hidden flex flex-row flex-wrap gap-4 items-start justify-start md:w-[49.5%] relative">
                        <div class="flex flex-row gap-3">
                            <div
                                class="absolute bg-theme-dark dark:bg-theme-light w-8 h-8 top-0 right-0 rounded-tr-10px rounded-bl-10px text-black flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-light-text dark:fill-dark-text"
                                    viewBox="0 -960 960 960" width="24px">
                                    <path
                                        d="m263-161.54 57.31-246.77-191.46-165.92 252.61-21.92L480-828.84l98.54 232.69 252.61 21.92-191.46 165.92L697-161.54 480-292.46 263-161.54Z" />
                                </svg>
                            </div>

                            <!-- IMG -->
                            <div class="bg-white rounded-full h-24 w-24 sm:w-28 md:h-28 overflow-hidden">
                                <img src="./imgs/14bdbcefa36b1da6cc0d6b71d19172a3ad016a4769efb6dac88c15223725159d.png"
                                    alt="">
                            </div>

                            <div class="flex flex-col gap-1">

                                <p class=" font-bold">
                                    Tushar Sadashiv Neje
                                </p>
                                <p class=" text-sm flex flex-row gap-1 justify-start items-center">

                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="fill-dark-text dark:fill-light-text">
                                        <path
                                            d="M174.62-100q-14.7 0-24.66-10.35-9.96-10.34-9.96-25.04v-195.38q0-29.92 21.19-51.12 21.2-21.19 51.12-21.19H220v-163.07q0-29.93 21.19-51.12 21.2-21.19 51.12-21.19H450v-58.77q-17.61-11.62-28.81-27.27Q410-740.15 410-761.85q0-13.46 5.23-26.03 5.23-12.58 15.69-23.04L480-860l49.08 49.08q10.46 10.46 15.69 23.04 5.23 12.57 5.23 26.03 0 21.7-11.19 37.35-11.2 15.65-28.81 27.27v58.77h157.69q29.92 0 51.12 21.19Q740-596.08 740-566.15v163.07h7.69q29.92 0 51.12 21.19Q820-360.69 820-330.77v195.38q0 14.7-10.35 25.04Q799.31-100 784.61-100H174.62ZM280-403.08h400v-163.07q0-5.39-3.46-8.85t-8.85-3.46H292.31q-5.39 0-8.85 3.46t-3.46 8.85v163.07ZM200-160h560v-170.77q0-5.39-3.46-8.85t-8.85-3.46H212.31q-5.39 0-8.85 3.46t-3.46 8.85V-160Zm80-243.08h400-400ZM200-160h560-560Zm540-243.08H220h520Z" />
                                    </svg>
                                    15/07/2005
                                </p>
                                <p class=" text-sm flex flex-row gap-1 justify-start items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="fill-dark-text dark:fill-light-text">
                                        <path
                                            d="M480-166.16 220-307.39v-216.92L81.54-600 480-816.92 878.46-600v287.69h-60v-254.46L740-524.31v216.92L480-166.16ZM480-452l273.62-148L480-748 206.38-600 480-452Zm0 217.54 200-108v-149.85L480-383.15 280-492.31v149.85l200 108ZM480-452Zm0 72.31Zm0 0Z" />
                                    </svg>
                                    B.Tech Computer Engineering
                                </p>
                            </div>
                        </div>
                    </div>



                    <div
                        class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full overflow-hidden flex flex-row flex-wrap gap-4 items-start justify-start p-3 relative md:w-[49.5%]">

                        <div class="flex flex-row gap-3">
                            <div
                                class="absolute bg-theme-dark dark:bg-theme-light w-8 h-8 top-0 right-0 rounded-tr-10px rounded-bl-10px text-black flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-light-text dark:fill-dark-text"
                                    viewBox="0 -960 960 960" width="24px">
                                    <path
                                        d="m263-161.54 57.31-246.77-191.46-165.92 252.61-21.92L480-828.84l98.54 232.69 252.61 21.92-191.46 165.92L697-161.54 480-292.46 263-161.54Z" />
                                </svg>
                            </div>

                            <!-- IMG -->
                            <div class="bg-white rounded-full h-24 w-24 sm:w-28 md:h-28 overflow-hidden">
                                <img src="./imgs/14bdbcefa36b1da6cc0d6b71d19172a3ad016a4769efb6dac88c15223725159d.png"
                                    alt="">
                            </div>

                            <div class="flex flex-col gap-1">

                                <p class=" font-bold">
                                    Tushar Sadashiv Neje
                                </p>
                                <p class=" text-sm flex flex-row gap-1 justify-start items-center">

                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="fill-dark-text dark:fill-light-text">
                                        <path
                                            d="M174.62-100q-14.7 0-24.66-10.35-9.96-10.34-9.96-25.04v-195.38q0-29.92 21.19-51.12 21.2-21.19 51.12-21.19H220v-163.07q0-29.93 21.19-51.12 21.2-21.19 51.12-21.19H450v-58.77q-17.61-11.62-28.81-27.27Q410-740.15 410-761.85q0-13.46 5.23-26.03 5.23-12.58 15.69-23.04L480-860l49.08 49.08q10.46 10.46 15.69 23.04 5.23 12.57 5.23 26.03 0 21.7-11.19 37.35-11.2 15.65-28.81 27.27v58.77h157.69q29.92 0 51.12 21.19Q740-596.08 740-566.15v163.07h7.69q29.92 0 51.12 21.19Q820-360.69 820-330.77v195.38q0 14.7-10.35 25.04Q799.31-100 784.61-100H174.62ZM280-403.08h400v-163.07q0-5.39-3.46-8.85t-8.85-3.46H292.31q-5.39 0-8.85 3.46t-3.46 8.85v163.07ZM200-160h560v-170.77q0-5.39-3.46-8.85t-8.85-3.46H212.31q-5.39 0-8.85 3.46t-3.46 8.85V-160Zm80-243.08h400-400ZM200-160h560-560Zm540-243.08H220h520Z" />
                                    </svg>
                                    15/07/2005
                                </p>
                                <p class=" text-sm flex flex-row gap-1 justify-start items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="fill-dark-text dark:fill-light-text">
                                        <path
                                            d="M480-166.16 220-307.39v-216.92L81.54-600 480-816.92 878.46-600v287.69h-60v-254.46L740-524.31v216.92L480-166.16ZM480-452l273.62-148L480-748 206.38-600 480-452Zm0 217.54 200-108v-149.85L480-383.15 280-492.31v149.85l200 108ZM480-452Zm0 72.31Zm0 0Z" />
                                    </svg>
                                    B.Tech Computer Engineering
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        </div>
    </main>

    <br>
    <br>
    <br>
    <br>
    <br>

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
                    class="p-2 border dark:hover:shadow-dark-theme-shadow hover:shadow-light-theme-shadow dark:hover:border hover:border bg-container-light dark:bg-container-dark  border-dark-text dark:border-light-text h-8 w-8 rounded-full flex items-center justify-center transition-all duration-300 ease-in-out">
                    <svg class="fill-dark-text dark:fill-light-text" xmlns="http://www.w3.org/2000/svg" height="20px"
                        viewBox="0 -960 960 960" width="24px">
                        <path
                            d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z" />
                    </svg>
                </div>

                <p class="text-xs">Home</p>
            </div>

            <!-- Account -->
            <div class="inline-flex flex-col justify-center text-center items-center gap-2 cursor-pointer">

                <div
                    class="overflow-hidden dark:hover:shadow-dark-theme-shadow hover:shadow-light-theme-shadow dark:hover:border hover:border bg-container-light dark:bg-container-dark  border-dark-text dark:border-light-text h-8 w-8 rounded-full flex items-center justify-center transition-all duration-300 ease-in-out">
                    <?php

                    echo "<img src='" . $_SESSION['userInfo']['picture'] . "' alt=''
                        onerror='this.style.display='none'; document.getElementById('profile-svg').style.display='block'
                        onload='this.style.display='block'; document.getElementById('profile-svg').style.display='none';'>"
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
</body>

</html>