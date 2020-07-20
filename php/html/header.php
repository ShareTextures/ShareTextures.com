<div id="header">
    <div id="header-logo">
         <div id="logo"> <a href="//<?php echo $_SERVER['HTTP_HOST'] ?>">
        <img style="height: 50px" src="//<?php echo $_SERVER['HTTP_HOST'] ?>/logo-hover.png" />
		       <div class="logoword">
        ShareTextures
    </div>
	</a>       </div> 
		
    </div>
    <div id="navbar-toggle"><i class="material-icons">menu</i></div>

    <ul id="navbar">
        <a href="//<?php echo $_SERVER['HTTP_HOST'] ?>"><li>Home</li></a>
        <a href="/textures"><li>Textures</li></a><!--
        --><a class='shrink-hack' href="//<?php echo $_SERVER['HTTP_HOST'] ?>/blog"><li>Blog</li></a><!--
        --><a href="/about-2/"><li>About/Contact</li></a>
    </ul>
 
    <div class='patreon-bar-wrapper' title="Next goal on Patreon: <?php
        echo goal_title($GLOBALS['PATREON_CURRENT_GOAL']);
        echo " ($";
        echo $GLOBALS['PATREON_EARNINGS'];
        echo " of $";
        echo $GLOBALS['PATREON_CURRENT_GOAL']['amount_cents']/100;
        echo ")";
        ?>">
        <a href="https://www.patreon.com/sharetextures/overview">
        <div class="patreon-bar-outer">
            <div class="patreon-bar-inner-wrapper">
                <div class="patreon-bar-inner" style="width: <?php
                    echo $GLOBALS['PATREON_CURRENT_GOAL']['completed_percentage'] ?>%">
                    <div class='patreon-bar-text'>
                        <img src="/files/site_images/icons/patreon_logo.svg">
                        <span class="text">
                        <?php
                        echo "Become a Patron | $";
                        echo ($GLOBALS['PATREON_CURRENT_GOAL']['amount_cents']/100) - $GLOBALS['PATREON_EARNINGS'];
                        echo " to goal";
                        ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        </a>
    </div>
</div>
<div class="nav-bar-spacer"></div>
<div id="push-footer">
