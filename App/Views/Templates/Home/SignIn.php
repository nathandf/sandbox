{{parent Layouts/Core.php}}

{{block head}}
    <link rel="stylesheet" type="text/css" href="./public/css/home.css" />
{{/block}}

{{block body}}
    <?php include( "App/Views/Components/Navigation/MainMenu.php" ); ?>
    <hr>
    <div class="pr bg-near-white">
        <div class="p20 pr z2">
            <div class="w-max-med center bsh bg-white p20 br10">
                <h2 class="fw6 fs32 tc pb20">Sign in</h2>
                <?php
                    if ( !is_null( $this->getData( "error" ) ) ) {
                        $this->renderError( $this->getVar( "error" ) );
                    }
                ?>
                <div>
                    <form action="<?=HOME?>user/auth/sign-in" method="post">
                        <input type="hidden" name="csrf_token" value="<?php $this->echoData( "csrf_token" ); ?>" />
                        <input type="hidden" name="sign_in" value="<?php $this->echoData( "csrf_token" ); ?>" />
                        <p class="label">Email</p>
                        <input type="email" name="email" required="required" value="<?php $this->echoData( "email" ) ?>" class="inp" />
                        <div class="container-password-component">
                            <p class="label">Password</p>
                            <input id="password" type="password" name="password" required="required" class="inp" />
                        </div>
                        <button type="submit" class="button mt20 theme-primary bsh-w-hov">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
        <svg class="db pa bl z1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#FA3954" fill-opacity="0.6" d="M0,320L120,320C240,320,480,320,720,272C960,224,1200,128,1320,80L1440,32L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
        </svg>
        <svg class="db pa bl z1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#357EDD" fill-opacity="1" d="M0,96L120,90.7C240,85,480,75,720,106.7C960,139,1200,213,1320,250.7L1440,288L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
        </svg>
    </div>
    <div class="p20"></div>
    <div class="pad-lrg"></div>
{{/block}}