<?php

use SokoForm\NotificationRenderer\SokoNotificationRenderer;
use SokoForm\Renderer\SokoFormRenderer;

$controls = $v['form']['controls'];
$email = $controls['email'];
$pass = $controls['pass'];

$r = SokoFormRenderer::create()
    ->setForm($v['form'])
    ->setNotificationRenderer(SokoNotificationRenderer::create());


SokoNotificationRenderer::cssTheme();

?>

<style>
    .soko-notification {
        text-shadow: none;
        margin-bottom: 20px;
    }
</style>

<div class="login_wrapper">
    <div class="animate form login_form">
        <section class="login_content">
            <form method="post" action="">


                <?php $r->submitKey(); ?>

                <h1>Login Form</h1>

                <div>
                    <?php $r->notifications(); ?>
                </div>
                <div>
                    <input name="<?php echo htmlspecialchars($email['name']); ?>" type="text" class="form-control"
                           placeholder="<?php echo htmlspecialchars($email['placeholder']); ?>" required=""/>
                </div>
                <div>
                    <input name="<?php echo htmlspecialchars($pass['name']); ?>" type="password" class="form-control"
                           placeholder="<?php echo htmlspecialchars($pass['placeholder']); ?>" required=""/>
                </div>
                <div>
                    <button type="submit" class="btn btn-default submit">Log in</button>
<!--                    <a class="reset_pass" href="#">Lost your password?</a>-->
                </div>

                <div class="clearfix"></div>

                <div class="separator">


                    <div>
                        <h1><i class="fa fa-paw"></i> Nullos Admin!</h1>
                        <p>©2018 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>