<?php
require_once './db.php';
if(!isset($_GET['q'])){
    echo "User not Found!";
    exit();
}
$uid = $_GET["q"];
$stmt = $pdo->prepare("SELECT * FROM user_information WHERE uid = ?");
$stmt->execute([$uid]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$row){
    echo "User not Found!";
    exit();
}
if($row['template'] != 'TEMP0'){
    echo "User not Found!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEMP0 => Light Template-1</title>
    <link href="../output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <style>
        ::-webkit-scrollbar{
            display: none;
        }
    </style>
</head>

<body class="min-h-screen h-screen font-Ubuntu">
    <div class="flex h-full w-full bg-TEMP0-o-fill">

        <div class="m-3 flex flex-col w-full bg-TEMP0-i-fill p-4 gap-3 overflow-y-scroll rounded-xl">

            <div class="flex flex-row gap-3 mt-8 w-full justify-center md:justify-start h-min">

                <div class="flex md:w-1/4 justify-center items-start">
                    <div class="w-28 h-28 bg-black rounded-full md:w-36 md:h-36 overflow-hidden">
                    <?php echo '<img src="../imgs/'.$row['uid'].'.png" alt="">'; ?>
                    </div>
                </div>

                <div class="flex flex-col gap-2 md:w-4/5 ml-2">
                    <p class="font-bold text-xl mt-3">
                        <?php echo $row['fullname'] ?>
                    </p>

                    <p class="w-auto text-sm">
                        <?php echo $row['aboutme'] ?>
                        
                    </p>
                </div>
            </div>

            <!-- General Information -->
            <div class="flex flex-col w-full gap-5 mt-3">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        General Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Date of Birth -
                        </span>
                        <?php echo $row['dob'] ?>
                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Height -
                        </span>
                        <?php echo $row['height'] ?> ft.
                    </p>

                    <p class="text-md">
                        <span class="font-semibold">
                            Weight -
                        </span>
                        <?php echo $row['weight'] ?> kg.
                    </p>
                </div>
            </div>

            <!-- Birth Information -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Birth Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Location of Birth -
                        </span>
                        <?php echo $row['location'] ?>
                    </p>

                    <p class="text-md">
                        <span class="font-semibold">
                            Birth Time -
                        </span>
                        <?php echo DateTime::createFromFormat('H:i',$row['timeofbirth'])->format('h:i A') ?>
                    </p>
                </div>
            </div>

            <!-- Occupation Information -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Occupation Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Job/Business -
                        </span>
                        <?php echo $row['work'] ?>
                    </p>

                    <p class="text-md">
                        <span class="font-semibold">
                            Income -
                        </span>
                        <?php echo $row['income'] ?> LPA
                    </p>
                </div>
            </div>

            <!-- Education Information -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Education Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Education -
                        </span>
                        <?php echo $row['education'] ?>
                    </p>
                </div>
            </div>

            <!-- Religion & Cast Information -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Religion & Cast
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Religion -
                        </span>
                        <?php echo $row['religion'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Cast -
                        </span>
                        <?php echo $row['caste'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Sub-Cast -
                        </span>
                        <?php echo $row['subcast'] ?>

                    </p>
                </div>
            </div>

            <!-- Rashi Information -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Rashi Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Rashi -
                        </span>
                        <?php echo $row['rashi'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Nakshatra -
                        </span>
                        <?php echo $row['nakshatra'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Birth Name -
                        </span>
                        <?php echo $row['birthname'] ?>

                    </p>
                </div>
            </div>

            <!-- Family Information -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Family Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Father's Information -
                        </span>
                        <?php echo $row['father'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Mother's Information -
                        </span>
                        <?php echo $row['mother'] ?>
                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Brother's Information -
                        </span>
                        <?php echo $row['brother'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Sister's Information -
                        </span>
                        <?php echo $row['sister'] ?>

                    </p>
                </div>
            </div>

            <!-- Address & Contact Info -->
            <div class="flex flex-col w-full gap-5">
                <div class="relative h-[0.06rem] w-full bg-gray-300 mt-2">
                    <p class="absolute text-black opacity-100 -top-3 left-5 bg-TEMP0-i-fill px-3">
                        Address & Contact Information
                    </p>
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-md">
                        <span class="font-semibold">
                            Address -
                        </span>
                        <?php echo $row['address'] ?>

                    </p>
                    <p class="text-md">
                        <span class="font-semibold">
                            Contact Information -
                        </span>
                        <?php echo $row['contactno'] ?>

                    </p>
                </div>
            </div>


            <div class="w-full mt-5">
                <p class="text-xs">
                    Uploaded By <?php 
                        $len = strlen($row['owner']);
                        $stars = $len-3;
                        $str = '';
                        for($i = 0; $i < $stars; $i++){
                            $str .= '*';
                        }
                        echo substr($row['owner'],0,3).$str;  
                    
                    ?>
                </p>
            </div>


        </div>

    </div>
</body>

</html>