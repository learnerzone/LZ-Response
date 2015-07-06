				<div id="sidebar1" class="col-sm-3" role="complementary">
				<?php
				global $current_user;
      get_currentuserinfo();

      echo 'Logged in as:<br /><div id="usernameloggedin">' . $current_user->display_name . "</a></div>";
      ?>
<hr>

<?php dynamic_sidebar( 'sidebar1' ); ?>
						<?php //} ?>
				</div>
				