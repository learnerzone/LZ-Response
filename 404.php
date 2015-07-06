<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-12 clearfix" role="main">

					<article id="post-not-found" class="clearfix">
						
						<header>
								<center>
							<div class="hero-unit">
							
								<h1><?php _e("404 - SOMETHING WENT WRONG!","wpbootstrap"); ?></h1>
								<h3><?php _e("This is embarassing and someone ought to get fired!","wpbootstrap"); ?></h3>
															
							</div>
						</header> <!-- end article header --></center>
					<center>
						<section class="post_content">
							
							<p><?php _e("Whatever you were looking for was not found, and someone MUST pay!<br>Pick from the list below who you think should be fired!","wpbootstrap"); ?></p>

							<div class="row">
								<div class="col col-lg-12">
									<center><table>
										<tr>
											<td class="paddedcell grow-rotate box-shadow-outset"><a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/01661.jpg"><center><h2 style="margin-top:0; margin-bottom:0;">Gav</h2><h3 style="margin-top:0; margin-bottom:0;">The Manager</h3></a></center></td>
											
											<td class="paddedcell grow-rotate box-shadow-outset"><a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/01662.jpg"><center><h2 style="margin-top:0; margin-bottom:0;">Amy</h2><h3 style="margin-top:0; margin-bottom:0;">The Developer</h3></a></center></td>
											
											<td class="paddedcell grow-rotate box-shadow-outset"><a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/01663.jpg"><center><h2 style="margin-top:0; margin-bottom:0;">Andy</h2><h3 style="margin-top:0; margin-bottom:0;">The Developer</h3></a></center></td>
											
											<td class="paddedcell grow-rotate box-shadow-outset"><a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/01664.jpg"><center><h2 style="margin-top:0; margin-bottom:0;">Brandon</h2><h3 style="margin-top:0; margin-bottom:0;">The Apprentice</h3></a></center></td>
										</tr>
									</table></center>
								</div>
							</div>
					</center>

						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
			
				</div> <!-- end #main -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>