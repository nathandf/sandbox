{{parent Layouts/Core.php}}

{{block head}}

{{/block}}

{{block body}}
	<?php include( "App/Views/Components/Navigation/MainMenu.php" ); ?>
    <hr>
    <div>
        <div class="bg-near-white pr">
            <div class="g g3 gg20 grc1-800 p20 pr z2">
                <div class="p20 bg-white bsh br10">
                    <h1 class="pt20 pb20 tc">Rapid Resumé</h1>
                    <p class="fs20 c-gray mt10">Creating the perfect Resumé is a pain in the ass. Rapidly build HTML & PDF Resumés and Cover Letters specifically designed for the company and position you're applying for.</p>
                    <div class="p20 w-max-med g g1 center">
                        <a href="<?=HOME?>resume/" class="button dib bsh-w-hov c-white bg-blue mr10 fs20">Start</a>
                    </div>
                </div>
            </div>
            <svg class="db pa tr z1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#357EDD" fill-opacity="0.1" d="M0,320L1440,32L1440,0L0,0Z"></path>
            </svg>
            <svg class="db pa bl z1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#357EDD" fill-opacity="0.3" d="M0,128L120,128C240,128,480,128,720,144C960,160,1200,192,1320,208L1440,224L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
            </svg>
            <svg class="db pa bl z1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#357EDD" fill-opacity="0.3" d="M0,320L120,320C240,320,480,320,720,272C960,224,1200,128,1320,80L1440,32L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
            </svg>
        </div>
    </div>
{{/block}}