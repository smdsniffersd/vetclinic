
<?php
require_once __DIR__ . '/../../config.php';
$personal = AllFetch('SELECT first_name, second_name, experience_work, photo, p.name as professions_name FROM `personal` LEFT JOIN professions as p ON personal.profession_id = p.id WHERE personal.profession_id BETWEEN 1 AND 5;');

?>
<section class="sixth-monit">
        <div class="Big-text-Meet_Our_Team">
            <span class="Big-Meet_Our_Team">Meet Our Team: Compassionate</span>
            <span class="Big-Meet_Our_Team">Experts Dedicated to Pets</span>
        </div>
        <nav class="nav-Meet_Our_Team">
            <ul class="nav-ul-Meet_Our_Team">
                <li class="li-Meet_Our_Team" id="All" onclick="sortingNav(this.id)">All</li>
                <li class="li-Meet_Our_Team" id="Veterinarian" onclick="sortingNav(this.id)">Veterinarian</li>
                <li class="li-Meet_Our_Team" id="Receptionist" onclick="sortingNav(this.id)">Receptionist</li>
                <li class="li-Meet_Our_Team" id="Practice Manager" onclick="sortingNav(this.id)">Practice Manager</li>
                <li class="li-Meet_Our_Team" id="Groomer" onclick="sortingNav(this.id)">Groomer</li>
                <li class="li-Meet_Our_Team" id="Kennel Staff" onclick="sortingNav(this.id)">Kennel Staff</li>

            </ul>


        </nav>
        <ul class="ul-doctors">
            <?php foreach ($personal as $person): ?>
                <li class="li-doctors">
                    <img class="doctors-photo" src="<?= htmlspecialchars($person['photo']) ?>" alt="doctors-photo">
                    <div class="doctors-descrip-big">
                        <div class="doctors-descrip">
                            <div class="doctors-descrip-small">
                                <span class="doctor-name">Dr. <?= htmlspecialchars($person['first_name']) ?> <?= htmlspecialchars($person['second_name']) ?></span>
                                <img class="bag-doctors-img" src="image/bag-doctors.png" alt="bag-doctors-img">
                                <span class="doctor-exp"><?= htmlspecialchars($person['experience_work']) ?>+ Years Experience</span>
                            </div>
                            <img class="arrow-black-img" src="image/arrow-black.png" alt="arrow-black">
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>


    </section>

<script>

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