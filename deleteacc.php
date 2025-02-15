<?php
// Start session
session_start();
include('google_config.php');

if (isset($_SESSION['access_token'])) {
    $client->revokeToken();
}
session_unset();
session_destroy();

//
//exit();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link href="./user/output.css" rel="stylesheet">
    <link href="./user/input.css" rel="stylesheet">
</head>

<body
    class="bg-theme-light text-dark-text dark:bg-theme-dark dark:text-light-text transition-colors duration-300 font-Ubuntu">

    <div class="m-4 md:m-8 z-0">
        <div class="inline-flex w-full flex-row justify-between p-5">
            <p style="font-size: 1.5rem;" class="font-bold">Logo Image</p>
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
                            class="hidden flex-col bg-container-light dark:bg-container-dark h-auto w-max pt-4 rounded gap-2 peer-checked:flex">

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

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIN CONTAINER -->
    <div class="flex w-full absolute justify-center top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">

        <div
            class="relative min-w-64 min-h-28 flex border-theme-dark dark:border-theme-light border rounded-2xl p-4 flex-col justify-center items-center">

            <p class="absolute -top-3 px-4 dark:bg-theme-dark bg-theme-light">
                Logout Successfully!
            </p>


            <div class="dark:bg-theme-light bg-theme-dark rounded-full p-4 flex justify-center items-center m-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="fill-theme-light dark:fill-theme-dark" height="4rem"
                    viewBox="0 -960 960 960" width="4rem">
                    <path
                        d="m400-416 236-236q11-11 28-11t28 11q11 11 11 28t-11 28L428-332q-12 12-28 12t-28-12L268-436q-11-11-11-28t11-28q11-11 28-11t28 11l76 76Z" />
                </svg>

            </div>

            <span class="" style="font-size: 0.6rem;">
                All Sessions cleared!
            </span>

            <span
                class="mt-2 text-sm px-3 py-1 bg-theme-dark text-light-text dark:text-dark-text dark:bg-theme-light rounded-lg">
                See You Soon :)
            </span>

            <span onclick="window.location.href='/OnlineBiodataSharingProject/user/'"
                class="mt-5 text-sm px-3 py-1 bg-theme-dark text-light-text dark:text-dark-text dark:bg-theme-light rounded-lg cursor-pointer">
                Back to Login
            </span>

        </div>

    </div>



    <script src="./user/theme-toggle.js"></script>
    <script src="./user/loginjs.js"></script>
</body>

</html>