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
                $stmt = $pdo->prepare("INSERT INTO `user_information`(`uid`, `owner`, `fullname`, `aboutme`,`dob`, `height`, `weight`, `location`, `timeofbirth`, `work`, `income`, `education`, `religion`, `caste`, `subcast`, `rashi`, `nakshatra`, `birthname`, `father`, `mother`, `brother`, `sister`, `address`, `contactno`, `linkshare`, `globalsearch`, `maritalstatus`, `template`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $stmt->execute([
                    $hased,
                    $userInfo->email,
                    'NA', // Full Name
                    '', // About Me
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
    class="bg-theme-light text-dark-text dark:bg-theme-dark dark:text-light-text transition-colors duration-300 font-Ubuntu relative">


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


        <div class="mt-4 flex p-3 flex-col gap-10">

            <!-- Share and Export -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Share or Export
                    Information
                </p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-start gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="flex flex-col text-start gap-1 w-full">
                            <div class="flex gap-3 w-full">

                                <Button onclick="document.getElementById('sharecont').classList.remove('hidden'); toggleShareCont(1);"
                                    class="flex flex-row gap-2 bg-odd-line-light dark:bg-odd-line-dark rounded-10px h-full w-full ">
                                    <div
                                        class="flex items-center bg-theme-dark dark:bg-theme-light p-halfp rounded-s-10px h-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960"
                                            width="22px" class="fill-light-text dark:fill-dark-text ">
                                            <path
                                                d="M680-80q-50 0-85-35t-35-85q0-6 3-28L282-392q-16 15-37 23.5t-45 8.5q-50 0-85-35t-35-85q0-50 35-85t85-35q24 0 45 8.5t37 23.5l281-164q-2-7-2.5-13.5T560-760q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35q-24 0-45-8.5T598-672L317-508q2 7 2.5 13.5t.5 14.5q0 8-.5 14.5T317-452l281 164q16-15 37-23.5t45-8.5q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-200q0-17-11.5-28.5T680-240q-17 0-28.5 11.5T640-200q0 17 11.5 28.5T680-160ZM200-440q17 0 28.5-11.5T240-480q0-17-11.5-28.5T200-520q-17 0-28.5 11.5T160-480q0 17 11.5 28.5T200-440Zm480-280q17 0 28.5-11.5T720-760q0-17-11.5-28.5T680-800q-17 0-28.5 11.5T640-760q0 17 11.5 28.5T680-720Zm0 520ZM200-480Zm480-280Z" />
                                        </svg>
                                    </div>

                                    <span class="text-sm p-3">
                                        Share Profile
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

            <!-- Edit Information -->
            <!-- <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Edit or Change
                    Template
                </p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-start gap-4 mt-5 w-full px-4 flex-wrap">

                        <div class="flex flex-col text-start gap-1 w-full">
                            <div class="flex gap-3 w-full">

                                <Button onclick="window.location.href = 'editprofile'"
                                    class="flex flex-row gap-2 bg-odd-line-light dark:bg-odd-line-dark rounded-10px h-full w-full ">
                                    <div
                                        class="flex items-center bg-theme-dark dark:bg-theme-light p-halfp rounded-s-10px h-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960"
                                            width="22px" class="fill-light-text dark:fill-dark-text ">

                                            <path
                                                d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                        </svg>
                                    </div>

                                    <span class="text-sm p-3">
                                        Edit User Profile
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
                                        Change Template
                                    </span>
                                </Button>

                            </div>
                        </div>

                    </div>

                </div>
            </div> -->

            <!-- GENERAL INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">General Info</p>

                <div class="absolute flex flex-col top-0 right-0 p-4 px-5 dark:text-dark-text dark:bg-theme-light text-light-text bg-theme-dark gap-3"
                    style="border-top-right-radius: 1.5rem; border-bottom-left-radius: 1.5rem;">

                    <p onclick="window.location.href = 'editprofile'" class="cursor-pointer">
                        <svg class="fill-light-text dark:fill-dark-text h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 -960 960 960">
                            <path
                                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z" />
                        </svg>
                    </p>
                    <hr class="dark:bg-theme-dark bg-theme-light opacity-50" style="height: 0.15rem; opacity: 50%;">
                    <p class="cursor-pointer">
                        <svg class="fill-light-text dark:fill-dark-text h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 -960 960 960">
                            <path
                                d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z" />

                        </svg>
                    </p>


                </div>

                <div class="mb-5 flex flex-col w-full md:w-auto justify-center items-center text-center">

                    <!-- Profile -->
                    <div class="flex flex-col gap-5 items-center">
                        <div
                            class="mt-8 rounded-full overflow-hidden w-40 h-40 border-4 border-theme-dark dark:border-theme-light">
                            <?php
                            echo '<input type="text" id="uid_prf" name="uid_prf" value="' . $userInfo['uid'] . '" hidden>';
                            echo '<img src="./imgs/' . $userInfo['uid'] . '.png" alt="Profile Photo">';
                            ?>

                        </div>

                        <!-- Name -->
                        <div class="p-4 rounded-10px dark:bg-odd-line-dark bg-odd-line-light">
                            <p class="text-lg font-semibold">
                                <?php echo $data[2]; ?>
                            </p>
                        </div>
                        <div class="p-4 rounded-10px dark:bg-odd-line-dark bg-odd-line-light">
                            <p class="text-sm">
                                <?php echo $data[3]; ?>
                            </p>
                        </div>
                    </div>
                    <!-- General Info -->
                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrapw">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Date of Birth</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[4]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Height</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[5]; ?> Ft
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Weight</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[6]; ?> kgs
                                </p>
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
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[7]; ?>
                                </p>
                            </div>
                        </div>


                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Time of Birth</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo DateTime::createFromFormat('H:i', $data[8])->format('h:i A') ?>
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
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[9]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Income</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[10]; ?> LPA
                                </p>
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
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[11]; ?>
                                </p>
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
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[12]; ?>
                                </p>
                            </div>
                        </div>


                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Caste</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[13]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Sub Caste</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[14]; ?>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <!-- LAGAN INFO -->
            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Lagan Info</p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Rashi</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[15]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Nakshatra</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[16]; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Birth Name (Navras Nav)</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[17]; ?>
                                </p>
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
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[18]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Mother's Name & Occupation</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[19]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Brother's Name & Occupation</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[20]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Sister's Name & Occupation</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[21]; ?>
                                </p>
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
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[22]; ?>
                                </p>
                            </div>
                        </div>

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Contact Number</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[23]; ?>
                                </p>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

            <!-- Privacy and Information Sharing -->
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

                                        <!-- INPUT-21 -->
                                        <?php

                                        if ($data[24]) {
                                            echo '<div class="w-5 h-5 mt-2 p-1 dark:bg-theme-light bg-theme-dark rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-auto fill-light-text dark:fill-dark-text" viewBox="0 -960 960 960">
                                                <path d="M382-208 122-468l90-90 170 170 366-366 90 90-456 456Z"/> 
                                                
                                            </svg>
                                            </div>
                                            <p class="mt-2 text-sm">Link Sharing Is Enabled.</p>';
                                        } else {
                                            echo '<div class="w-5 h-5 mt-2 p-1 dark:bg-theme-light bg-theme-dark rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-auto fill-light-text dark:fill-dark-text" viewBox="0 -960 960 960">
                                                <path d="m256-168-88-88 224-224-224-224 88-88 224 224 224-224 88 88-224 224 224 224-88 88-224-224-224 224Z"/>
                                                
                                            </svg>
                                            </div>
                                            <p class="mt-2 text-sm">Global Link Sharing Is Not Enabled.</p>';
                                        }
                                        ?>


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
                                        <?php

                                        if ($data[25]) {
                                            echo '<div class="w-5 h-5 mt-2 p-1 dark:bg-theme-light bg-theme-dark rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-auto fill-light-text dark:fill-dark-text" viewBox="0 -960 960 960">
                                                    <path d="M382-208 122-468l90-90 170 170 366-366 90 90-456 456Z"/> 
                                                    
                                                </svg>
                                                </div>
                                                <p class="mt-2 text-sm">Global Search Is Enabled.</p>';
                                        } else {
                                            echo '<div class="w-5 h-5 mt-2 p-1 dark:bg-theme-light bg-theme-dark rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-auto fill-light-text dark:fill-dark-text" viewBox="0 -960 960 960">
                                                    <path d="m256-168-88-88 224-224-224-224 88-88 224 224 224-224 88 88-224 224 224 224-88 88-224-224-224 224Z"/>
                                                    
                                                </svg>
                                                </div>
                                                <p class="mt-2 text-sm">Global Search Is Not Enabled.</p>';
                                        }
                                        ?>

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
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Marital Status</p>
                            <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-10px">
                                <p class="text-sm">
                                    <?php echo $data[26]; ?>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

            <div class="relative border border-theme-dark dark:border-theme-light box-border w-full rounded-3xl">
                <p class="absolute inline-block -top-3 left-5 px-3 bg-theme-light dark:bg-theme-dark">Template Setting
                </p>

                <div
                    class="mb-5 flex flex-col md:flex-row w-full justify-center items-center text-center md:text-left md:justify-start">

                    <div class="inline-flex justify-evenly gap-4 mt-5 w-full px-4 flex-nowrap">

                        <div class="w-full flex flex-col text-start gap-1">
                            <p class="text-xs">Selected Template</p>

                            <div class="flex flex-row items-center mt-1">

                                <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 rounded-s-10px w-full">
                                    <p class="text-sm">
                                        <?php echo $data[27]; ?>
                                    </p>
                                </div>

                                <div
                                    class="bg-theme-dark dark:bg-theme-light p-1 fill-light-text dark:fill-dark-text flex item-center h-full rounded-e-10px w-12 justify-center cursor-pointer">
                                    <!-- <div class="bg-odd-line-light dark:bg-odd-line-dark p-3 w-full"> -->

                                    <svg class="fill-light-text dark:fill-dark-text w-5"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                        <path
                                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z">
                                        </path>
                                    </svg>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

        </div>

    </main>

    <script>
        function toggleShareCont(num) {
            let shareCont = document.getElementById("sharecont");

            if (shareCont.classList.contains("translate-y-full")) {
                shareCont.classList.remove("translate-y-full", "opacity-0");
                shareCont.classList.add("translate-y-0", "opacity-100");
            } else {
                shareCont.classList.add("translate-y-full", "opacity-0");
                shareCont.classList.remove("translate-y-0", "opacity-100");
            }
        }

    </script>

    <div class="fixed w-full dark:bg-theme-light bg-theme-dark bottom-0 left-0 z-30 h-48 rounded-t-3xl transition-all duration-300 transform translate-y-full opacity-0"
        id="sharecont">
        <p class="flex justify-between dark:text-dark-text text-light-text mt-4 mx-5 mb-0">
            Share using
            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="24px"
                class="dark:fill-dark-text fill-light-text">
                <path
                    d="M680-80q-50 0-85-35t-35-85q0-6 3-28L282-392q-16 15-37 23.5t-45 8.5q-50 0-85-35t-35-85q0-50 35-85t85-35q24 0 45 8.5t37 23.5l281-164q-2-7-2.5-13.5T560-760q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35q-24 0-45-8.5T598-672L317-508q2 7 2.5 13.5t.5 14.5q0 8-.5 14.5T317-452l281 164q16-15 37-23.5t45-8.5q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-200q0-17-11.5-28.5T680-240q-17 0-28.5 11.5T640-200q0 17 11.5 28.5T680-160ZM200-440q17 0 28.5-11.5T240-480q0-17-11.5-28.5T200-520q-17 0-28.5 11.5T160-480q0 17 11.5 28.5T200-440Zm480-280q17 0 28.5-11.5T720-760q0-17-11.5-28.5T680-800q-17 0-28.5 11.5T640-760q0 17 11.5 28.5T680-720Zm0 520ZM200-480Zm480-280Z" />
            </svg>
        </p>
        <div class="flex flex-row p-3 h-min w-full justify-around items-center">
            <div class="flex flex-col justify-center items-center" onclick="showPopup('URL Copied to Clipboard',3)">
                <div
                    class="flex justify-center items-center w-16 h-16 rounded-full dark:bg-theme-dark bg-theme-light cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="28px"
                        class="dark:fill-light-text fill-dark-text">
                        <path
                            d="M360-240q-33 0-56.5-23.5T280-320v-480q0-33 23.5-56.5T360-880h360q33 0 56.5 23.5T800-800v480q0 33-23.5 56.5T720-240H360Zm0-80h360v-480H360v480ZM200-80q-33 0-56.5-23.5T120-160v-560h80v560h440v80H200Zm160-240v-480 480Z" />
                    </svg>
                </div>
                <p class="dark:text-dark-text text-light-text text-sm mt-1">Copy URL</p>
            </div>

            <div class="flex flex-col justify-center items-center" onclick="sendToWp()">
                <div
                    class="flex justify-center items-center w-16 h-16 rounded-full dark:bg-theme-dark bg-theme-light cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 48 48">
                        <path fill="#fff"
                            d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z">
                        </path>
                        <path fill="#fff"
                            d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z">
                        </path>
                        <path fill="#cfd8dc"
                            d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z">
                        </path>
                        <path fill="#40c351"
                            d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z">
                        </path>
                        <path fill="#fff" fill-rule="evenodd"
                            d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="dark:text-dark-text text-light-text text-sm mt-1">WhatsApp</p>
            </div>
        </div>
        <div class="flex mt-1 flex-row h-min w-full justify-around items-center cursor-pointer">
            <p class="dark:text-dark-text text-light-text font-bold"
                onclick="toggleShareCont(0)">Close</p>
        </div>
    </div>

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
        function sendToWp(){
            text = document.getElementById('uid_prf').value
            url = `${window.location.hostname}/OnlineBiodataSharingProject/user/?q=${text}`
            window.open(`https://wa.me/?text=${encodeURIComponent(url)}`, "_blank");
        }
        const popup = document.getElementById("popup");
        const popupMessage = document.getElementById("popupMessage");
        const closeBtn = document.getElementById("closeBtn");
        const borderloader = document.getElementById('border-loader');

        let timeout;

        // Function to show popup
        function showPopup(message, seconds) {
            text = document.getElementById('uid_prf').value
            url = `${window.location.hostname}/OnlineBiodataSharingProject/user/?q=${text}`
            navigator.clipboard.writeText(url);

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


    <script src="./theme-toggle.js"></script>
</body>

</html>