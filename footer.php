<?php require_once "config.php"; ?>
  		  </div>
  		</div>
  		<div id="right">
  		  <div class="contentStrip"><span class="contentStripLeft">&nbsp;</span></div>
  		</div>
  		<div id="center">
  			<div class="content">
					<div class="sidebarTitle">
						<h3>On Air</h3>
					</div>
  				<div id="onAir" class="sidebar">
						<ul>
							<?php require "schedule/preview.php" ?>
					</ul>
					</div>
					<br />
					<div class="sidebarTitle">
						<h3><a href="<?php echo $root?>/charter.php">Last Played</a></h3>
					</div>
  				<div id="lastPlays" class="sidebar">
						<?php require "playlists/playlists.php" ?>
					</div>
					<br />
  				<div class="sidebarTitle">
						<h3>Request a Song</h3>
					</div>
  				<div id="songRequest" class="sidebar">
						<ul>
						</ul>
					</div>
	<br />
  				<div class="sidebarTitle">
						<h3>Podcast <a href="<?php echo $root; ?>/rss/podcast.xml"><img src="<?php echo $root; ?>/themes/<?php echo $curtheme; ?>/images/rss.png" alt="RSS Feed" style="float: center;" /></a></h3>
					</div>
  				<div id="songRequest" class="sidebar">




<?php include "podcast.php"; ?>




					</div>
					<br />
	  	  </div>
	  	  <br />
		  </div>
  	</div>
  	<div class="clear"></div>
  </div>
</div>

<div id="footer">
	<div class="content">Copyright &copy; 2011 Matthew Knox<img src="<?php echo $root; ?>/themes/<?php echo $curtheme; ?>/images/small_logo.gif" width="12" height="16" style="vertical-align: middle; margin-left: 5px; margin-bottom: 2px;" /></div>
	<div class="bottom"><span class="cornerBottomLeft" style="display: none;">&nbsp;</span>&nbsp;<span class="cornerBottomRight" style="display: none;">&nbsp;</span></div>
</div>

</body>
</html>
