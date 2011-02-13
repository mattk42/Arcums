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
							<li><img src="/images/icons/phone.gif" width="16" height="16" alt="Phone Icon" style="vertical-align: bottom;" />&nbsp;(906) 227-2348</li>
							<li><a href="aim:goim?screenname=wupxrequest" style="color: #ddd;"><img src="/images/icons/aim.gif" width="16" height="16" alt="AIM Icon" style="vertical-align: bottom;" />&nbsp;WUPXREQUEST</a></li>
						</ul>
					</div>
	<br />
  				<div class="sidebarTitle">
						<h3>Podcast</h3>
					</div>
  				<div id="songRequest" class="sidebar">



<a href="http://www.wupx.com/rss/podcast.xml"><img src="http://www.wupx.com/images/rss.png" alt="RSS Feed" style="float: center;" /></a>
<a href="http://phobos.apple.com/WebObjects/MZStore.woa/wa/viewPodcast?id=269188808"><img src="http://www.wupx.com/images/subscribe.png" alt="Subscribe with iTunes" style="float: center;" /></a><br />
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
	<div class="content"><span style="float: left;"><a href="/contact.php">Contact Us</a>&nbsp;/&nbsp;<a href="/about.php">About WUPX</a></span><strong>Find us on:</strong>&nbsp;&nbsp;<a href="http://nmu.facebook.com/group.php?gid=2202628826">Facebook</a>&nbsp;/&nbsp;<a href="http://www.myspace.com/radiox915">Myspace</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copyright &copy; 2008 WUPX<img src="/images/small_earl.gif" alt="Earl the Penguin" width="12" height="16" style="vertical-align: middle; margin-left: 5px; margin-bottom: 2px;" /></div>
	<div class="bottom"><span class="cornerBottomLeft" style="display: none;">&nbsp;</span>&nbsp;<span class="cornerBottomRight" style="display: none;">&nbsp;</span></div>
</div>

</body>
</html>
