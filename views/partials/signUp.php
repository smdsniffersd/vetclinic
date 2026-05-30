<div id="signUpOverlay" class="signUpOverlay">
        <h2>Sign Up</h2>
        <form action="/vetcare/php_components/registration.php" method="post" id="signform" class="signform">
            <input type="text" name="firstName" placeholder="Your first name" required>
            <input type="text" name="secondName" placeholder="Your second name" required>
            <input type="email" name="email" placeholder="youremail@gmail.com" required>
            <input type="tel" name="phone" placeholder="+7 999 123 45 67" pattern="\+7[0-9]{10}" title="Формат: +79991234567" required>
            <input type="text" name="address" placeholder="Your street your house" required>
            <input type="password" name="password" id="passwordfirst" placeholder="Create safety password" minlength="6" required>
            <input type="password" id="passwordsecond" placeholder="Repeat your safety password" minlength="6" required>
            <label><input type="checkbox" name="consent" required>I agree to the processing of personal data</label>
            <div>
                <button type="submit">Sign Up</button>
                <button type="button" id="cancelBtnSignUp">Cancel</button>
            </div>

        </form>
    </div>

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

</script>