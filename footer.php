<?php require_once "config.php"; ?>
  		  </div>
  		</div>
  		<div id="right">
  		  <div class="contentStrip"><span class="contentStripLeft">&nbsp;</span></div>
  		</div>
  		<div id="center">
  			<div class="content">
		<?php		
			$dir = "/var/www/arcums/sidebar/";

// Open a known directory, and proceed to read its contents
$dir_syntax_regex="/\d+_.*/";
if (is_dir($dir)) {
    $files = scandir($dir);
        foreach ($files as $file) {
       		if (filetype($dir.$file)=='dir' && preg_match($dir_syntax_regex,$file)){
			if(is_file($dir.$file."/title.html")){
				$title=file_get_contents($dir.$file."/title.html");
			}
			else{
				$title=preg_replace("/\d+_/","",$file);
				$title=preg_replace("/_/"," ",$title);
			}	
			echo '<div class="sidebarTitle">
                                                <h3>'.$title.'</h3>
                                        </div>
                                <div id="onAir" class="sidebar">
                                                <ul>';
                                                        require "$dir$file/index.php"; 
                                       echo' </ul>
                                        </div>
                                        <br />';
		}
       	 
	}
}
	  	?>	 
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
