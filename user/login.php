<?php
ob_start();
require_once __DIR__ . '/../google_config.php';
require_once './db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Login
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <link href="./output.css" rel="stylesheet">
</head>

<body
    class="bg-theme-light text-dark-text dark:bg-theme-dark dark:text-light-text transition-colors duration-300 font-Ubuntu h-[100vh]">

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
    <div class="flex w-full justify-center">
        <p class="text-3xl font-bold">
            Welcome User!
        </p>
    </div>

    <div class="flex w-full justify-center">

        <div
            class="mt-10 relative flex w-login-signup-box h-auto rounded-3xl border border-dark-text dark:border-light-text">
            <p
                class="cursor-pointer absolute -top-4 left-12 bg-theme-dark dark:bg-theme-light text-light-text dark:text-dark-text px-3 py-1 rounded-lg">
                Login
            </p>
            <p class="cursor-pointer absolute -top-4 right-12 bg-theme-light dark:bg-theme-dark px-3 rounded-lg">
                Sign Up
            </p>

            <div class="flex w-full flex-col">

                <div class="flex w-full p-5 pb-0">
                    <form action="login.php" method="post" class="w-full flex flex-col">
                        <!-- Field -->
                        <div class="w-full flex flex-col mt-5">
                            <label class="text-xs ml-1 mb-1">
                                Username
                            </label>
                            <div class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-4 rounded-10px">

                                <p class="text-sm w-full">
                                <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                    <!-- INPUT-10 -->
                                    <input type="text" value=""
                                        placeholder="Enter Your Username Here"
                                        class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                        name="username" id="username">
                                </div>
                                </p>
                            </div>

                        </div>
                </div>

                <div class="flex w-full p-5 pt-3">
                    <!-- Field -->
                    <div class="w-full flex flex-col">
                        <label class="text-xs ml-1 mb-1">
                            Password
                        </label>

                        <div class="flex flex-row">


                            <div class="relative w-full bg-odd-line-light dark:bg-odd-line-dark p-4 rounded-s-10px">

                                <p class="text-sm w-full">
                                <div class="bg-odd-line-light dark:bg-odd-line-dark rounded-10px w-full">
                                    <!-- INPUT-10 -->
                                    <input type="password" value="" placeholder="Enter Your Password Here"
                                        class="appearance-none text-sm p-0 bg-transparent outline-none w-full"
                                        name="password" id="password">
                                </div>
                                </p>
                            </div>

                            <div class="dark:bg-theme-light bg-theme-dark rounded-e-10px flex items-center p-2">
                                <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" height="18px"
                                    viewBox="0 -960 960 960" width="18px"
                                    class="dark:fill-dark-text fill-light-text cursor-pointer">

                                    <path
                                        d="M630.92-441.08 586-486q9-49.69-28.35-89.35Q520.31-615 466-606l-44.92-44.92q13.54-6.08 27.77-9.12 14.23-3.04 31.15-3.04 68.08 0 115.58 47.5T643.08-500q0 16.92-3.04 31.54-3.04 14.61-9.12 27.38Zm127.23 124.46L714-358q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-46.61-46.61q37.92-15.08 77.46-22.23Q438.39-780 480-780q140.61 0 253.61 77.54T898.46-500q-22.23 53.61-57.42 100.08-35.2 46.46-82.89 83.3Zm32.31 231.39L628.62-245.85q-30.77 11.39-68.2 18.62Q523-220 480-220q-141 0-253.61-77.54Q113.77-375.08 61.54-500q22.15-53 57.23-98.88 35.08-45.89 77.23-79.58l-110.77-112 42.16-42.15 705.22 705.22-42.15 42.16Zm-552.3-551.08q-31.7 25.23-61.66 60.66Q146.54-540.23 128-500q50 101 143.5 160.5T480-280q27.31 0 54.39-4.62 27.07-4.61 45.92-9.53L529.69-346q-10.23 4.15-23.69 6.61-13.46 2.47-26 2.47-68.08 0-115.58-47.5T316.92-500q0-12.15 2.47-25.42 2.46-13.27 6.61-24.27l-87.84-86.62ZM541-531Zm-131.77 65.77Z" />

                                </svg>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex w-full p-5 pt-0">

                    <button name="user-login"
                        class="text-sm relative w-full bg-odd-line-light dark:bg-odd-line-dark p-2 rounded-10px">
                        Login
                    </button>
                </div>
                </form>

                <div class="flex justify-normal items-center w-full px-5">
                    <hr class="border-theme-dark dark:border-theme-light w-1/2 opacity-30">
                    <p class="text-xs px-4">
                        or
                    </p>
                    <hr class="border-theme-dark dark:border-theme-light w-1/2  opacity-30">
                </div>

                <!-- Recommended -->
                <div class="flex w-full justify-center">

                    <div class="inline text-xs px-2 py-1 rounded-md bg-green-500 w-min mt-2">
                        <span>Recommended</span>
                    </div>
                </div>
                <div class="w-full flex justify-center p-5 pt-2">

                    <form action="login.php" method="post" class="w-full flex">
                        <button name="google-login"
                            class="flex text-sm w-full bg-odd-line-light dark:bg-odd-line-dark rounded-10px">

                            <div
                                class="flex p-2 bg-theme-dark dark:bg-theme-light rounded-s-10px justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" class=""
                                    viewBox="0 0 50 50">
                                    <path fill="#FFC107"
                                        d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                                    </path>
                                    <path fill="#FF3D00"
                                        d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                                    </path>
                                    <path fill="#4CAF50"
                                        d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                                    </path>
                                    <path fill="#1976D2"
                                        d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                                    </path>
                                </svg>
                            </div>


                            <div class="flex w-full h-full justify-center items-center">
                                Continue with Google
                            </div>

                        </button>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <div id="auto-login" class="hidden absolute flex flex-col bg-important-red right-0 p-3 bottom-10 gap-4 items-center text-white rounded-s justify-start text-left">
    
    <span class="w-full ml-2">
        Logged In Previously?
    </span>
        <form class="flex flex-row gap-2 items-center" action="login.php" method="post">
            <Button name="auto-login" class="p-2 bg-white rounded text-black text-left">
                Auto Login
            </Button>
            or 
            <Button name="auto-login-remove" class="p-2 bg-white rounded text-black">
                Remove Auto Login
            </Button> 
        </form>
        <span style="font-size: 0.65rem;" class="text-left">
            *Auto Login get's Removed After 2 Hours Automatically.
        </span>
    </div>


    <script src="./theme-toggle.js"></script>
    <script src="./loginjs.js"></script>
    <script>
        function error_login(msg, status) {
            alert(msg);
        }

        function toggleAutoLogin(){
            document.getElementById('auto-login').classList.toggle('hidden');
        }
    </script>
</body>

</html>


<?php
if(isset($_POST['auto-login'])){
    if (isset($_COOKIE["user_access"])) {
        header("Location: profile.php");
        exit();
    }
}
if(isset($_POST['auto-login-remove'])){
    if (isset($_COOKIE["user_access"])) {
        setcookie("user_access", base64_encode(""), time() + 0, "/", "", true, true);
        header("Location: login");
    }
}
if (isset($_COOKIE["user_access"])) {

    echo "<script>
    toggleAutoLogin();
    </script>";

}
if (isset($_POST['google-login'])) {
    if (!isset($_SESSION['access_token'])) {
        $login_url = $client->createAuthUrl();
        header("Location: " . $login_url);
        exit();
    } else {
        header("Location: profile.php");
        exit();
    }
}
if (isset($_POST["user-login"])) {
    if (isset($_COOKIE["user_access"])) {
        // echo base64_decode(($_COOKIE["user_access"]));
        header("Location: profile.php");
    } else {
        echo var_dump($_POST);
        $user_username = $_POST["username"];
        $user_password = $_POST["password"];

        // Manual Login
        $stmt = $pdo->prepare("SELECT COUNT(*),password,uid FROM user_accounts WHERE username = ?");
        $stmt->execute([$user_username]);

        while ($row = $stmt->fetch()) {
            if ($row['COUNT(*)'] <= 0) {
                echo '<h1>' . $user_username;
                $user_password;
                $row["COUNT(*)"];
                var_dump($row) . '</h1>';
                echo '
                <script>
                    error_login("Login Failed! Account not found!",1);
                </script>
                ';
            } else {
                echo $row['password'] . ' ' . hash('sha256', $user_password);
                if ($row['password'] == hash('sha256', $user_password)) {
                    setcookie("user_access", base64_encode($row['uid']), time() + 3600 * 2, "/", "", true, true);
                    header("Location: profile.php");
                    // echo base64_decode(($_COOKIE["user_access"]));
                }
                echo '
                <script>
                    error_login("Login Failed! Check Password",0);
                </script>
                ';
            }
        }
    }
}

// Write function for manual username and password login

?>