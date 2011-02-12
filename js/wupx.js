	Event.observe(window, 'load', init);

	var element = 'lastPlays';
	var playlistUpdater;

	function init() {
		playlistUpdater = setInterval('blindOutAndIn()', 30000);
	}
	
	function blindOutAndIn() {
		new Effect.Parallel(
			[
				new Effect.Opacity(element, {to: 0}),
				new Effect.BlindUp(element)
			], {
				afterFinish: function() {
					new Ajax.Updater(element, '/playlists/playlists.php', {
						method:'get',
						onComplete:function() {
							new Effect.Parallel(
								[
									new Effect.Opacity(element, {from: 0, to: 1}),
              		new Effect.BlindDown(element)
            		], {});
          	}
          });
				}
			});		
	}