<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$personalModel = new Personal();
$personal = $personalModel->getDoctors();

?>
<!DOCTYPE html>

<html lang="eu">


<head>

    <title>Vetcare</title>
    <meta charset="utf-8">

    <link rel="stylesheet" href="css/total_css.css">
    <link rel="stylesheet" href="css/css17.04.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" type="image/png" sizes="16x16" href="image/small_lapka.png">
    <meta name="msapplication-TileImage" content="image/small_lapka.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>



<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>
    <img class="first-line-img" src="image/first-line-img.jpg" alt="">
    <main class="main">



        <div class="text_info">

            <p class="hesh-tag">#1Petcare in this country</p>
            <div class="Big-text">
                <span>Ensuring Care and</span>
                <span>Support for Your</span>
                <span>Beloved Pet's</span>
            </div>


            <div class="text-descr">
                <span>Our veterinary clinic provides comprehensive and</span>
                <span>compassionate care for your beloved pets</span>

            </div>

            <div class="button_class">

                <a href="/vetclinic/booking" class="Book">Book Appointment</a>
                <a href="/vetclinic/pet-health-programs" class="Get">Get Started</a>

            </div>

        </div>
        <div class="main_img">
            <div class="first-line-photo">
                <img class="left" src="image/main_img_1.jpg" alt="Cat">
                <img class="right" src="image/main_img_2.jpg" alt="Cat">
            </div>
            <div class="second-line-photo">
                <img class="left" src="image/main_img_3.jpg" alt="Cat">
                <img class="right" src="image/main_img_4.jpg" alt="Cat">
            </div>

        </div>

    </main>
    <img class="underline-img" src="image/underline.png" alt="underline">
    <?php require_once __DIR__ . '/../partials/likefooter.php'; ?>
    <?php require_once __DIR__ . '/../partials/statistic.php'; ?>
    <?php require_once __DIR__ . '/../partials/ourservice.php'; ?>
    <?php require_once __DIR__ . '/../partials/subscribes.php'; ?>
    <?php require_once __DIR__ . '/../partials/personal.php'; ?>

    <section class="seventh-monit">

        <div class="Big-text-What_Our_Client_Say"><span class="Big-text-What_Our_Client_Say-span">What Our Client Say</span></div>

        <div class="seventh-block">




            <div class="seventh-monit-text-div">

                <div class="seventh-monit-orange-text">
                    <span class="seventh-monit-orange-text-span">John Corner, Melbourne</span>
                </div>
                <div class="seventh-monit-photos-mobi">
                    <div class="seventh-monit-photos-next">
                        <img class="seventh-photo-left" src="image/seventh-photo-left.png" alt="seventh-photo-left">
                        <div class="seventh-photo-right">

                            <img class="seventh-photo-rigth-top" src="image/seventh-photo-rigth-top.png" alt="seventh-photo-rigth-top">
                            <img class="seventh-photo-rigth-bottom" src="image/seventh-photo-rigth-bottom.png" alt="seventh-photo-rigth-bottom">

                        </div>
                    </div>
                </div>

                <div class="seventh-monit-text-block">
                    <span class="seventh-monit-span-main">So far, this pet shop has proven to be the</span>
                    <span class="seventh-monit-span-main">best in the area when it comes to providing</span>
                    <span class="seventh-monit-span-main">expert and reliable services for pet owners.</span>
                    <span class="seventh-monit-span-main">Their team operates with genuine care and</span>
                    <span class="seventh-monit-span-main">passion.</span>
                </div>

                <div class="seventh-monit-buttons">
                    <button class="seventh-monit-button"><img class="seventh-button-left" src="image/seventh-button-left.png" alt="seventh-button-left"></button>
                    <button class="seventh-monit-button"><img class="seventh-button-rigth" src="image/seventh-button-rigth.png" alt="seventh-button-rigth"></button>

                </div>

            </div>


            <div class="seventh-monit-photos">
                <div class="seventh-monit-photos-next">
                    <img class="seventh-photo-left" src="image/seventh-photo-left.png" alt="seventh-photo-left">
                    <div class="seventh-photo-right">

                        <img class="seventh-photo-rigth-top" src="image/seventh-photo-rigth-top.png" alt="seventh-photo-rigth-top">
                        <img class="seventh-photo-rigth-bottom" src="image/seventh-photo-rigth-bottom.png" alt="seventh-photo-rigth-bottom">

                    </div>
                </div>
            </div>

        </div>



    </section>
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
    <?php require_once __DIR__ . '/../partials/chat-container.php'; ?>
    <?php require_once __DIR__ . '/../partials/signUp.php'; ?>
    <?php require_once __DIR__ . '/../partials/login.php'; ?>



    <script src="scripts/total_js.js"></script>
    <script src="scripts/index.js"></script>

    <script>
        document.getElementById('registration').addEventListener('click', function() {
            document.getElementById("signUpOverlay").style.display = 'block';
            document.getElementById("loginOverlay").style.display = 'none';
        })
        document.getElementById('cancelBtnSignUp').addEventListener('click', function() {
            document.getElementById("signUpOverlay").style.display = 'none';
        })

        document.getElementById('signform').addEventListener('submit', function(e) {

            const pass1 = document.getElementById('passwordfirst').value;
            const pass2 = document.getElementById('passwordsecond').value;
            if (pass1 !== pass2) {

                alert('Пароли не совпадают');
                e.preventDefault();
            }
        })






        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.li-plan');

            cards.forEach(card => {
                const namePlan = card.querySelector('.price-block-plan');
                const button = card.querySelector('.Enroll-button, .Enroll-button-popular');
                if (button) {
                    card.addEventListener('mouseenter', function() {
                        card.style.backgroundColor = '#FF9900';
                        button.style.backgroundColor = 'black';
                        button.style.color = 'white';
                        button.style.border = 'black';
                        button.style.cursor = 'pointer';
                        namePlan.style.backgroundColor = 'black';
                        namePlan.style.color = 'white';
                    });

                    card.addEventListener('mouseleave', function() {
                        card.style.backgroundColor = '';
                        button.style.backgroundColor = '';
                        button.style.color = '';
                        button.style.border = 'white';
                        button.style.cursor = '';
                        namePlan.style.backgroundColor = '#FF9900';
                    });
                }
            });
        });



        const nav = document.querySelector('.li-Meet_Our_Team');
        const doctors = document.querySelectorAll('.li-doctors');

        function sortingNav(id) {
            const Elementid = id;
            const personalData = <?php echo json_encode($personal); ?>;

            if (Elementid == 'All') {
                doctors.forEach(doctor => {
                    doctor.style.display = 'flex';
                });
                return;
            }
            doctors.forEach((doctor, index) => {
                if (personalData[index]['professions_name'] == Elementid) {
                    console.log(Elementid);
                    console.log(personalData[index]);
                    doctor.style.display = 'flex';
                } else {
                    doctor.style.display = 'none';
                }


            })
        }
    </script>
</body>


</html>