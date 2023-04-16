
    <?php
    global $user;
    ?>
    <div class="login">
        <div class="col-4 bg-white border rounded p-4 shadow-sm">
            <form method="post" action="assets/php/actions.php?verify_email">
                <div class="d-flex justify-content-center">


                </div>
                <h1 class="h5 mb-3 fw-normal">Verify Your Email Id <small>(<?=$user['email']?>)</small></h1>


                <p>Enter 6 Digit Code Sent to You</p>
                <div class="form-floating mt-1">

                    <input type="text" name="code" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">******</label>
                </div>
                <?php
            if(isset($_GET['resent'])){
                ?>
            <p class="text-success">Please, Check your Email</p>
            <?php
            }
                ?>
                <?=showError('email_verify')?>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" type="submit">Verify Email</button>
                    <a href="assets/php/actions.php?resend_code"class="text-decoration-none" type="submit">Resend Code</a>





                </div>
                <br>
                <a href="assets/php/actions.php?logout" class="text-decoration-none mt-5"><i class="bi bi-arrow-left-circle-fill"></i>
                    Logout</a>
            </form>
        </div>
    </div>
