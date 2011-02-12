	Ajax.Responders.register({
		onCreate : showLoader,
 		onComplete : hideLoader
	});
	
	Event.observe(window, 'load', init);
	
	//Event.observe(something, 'click', funcName);
	
	function init() {
		prepareForm("contact_form", "status", "letschat.php");
	}
	
	function prepareForm(formId, containerId, url) {
		if (!document.getElementById) return false;
		if (!$(formId)) return false;
		$(formId).onsubmit = function() {
			var data = $(formId).serialize(true);
			return (sendData(data, url, containerId));
		};
		$(formId).onkeypress = function() {
			enableSubmit(true);
		};
	}
	
	function sendData(params, url, containerId) {
		var ajax = new Ajax.Updater(containerId, url, {
			method: 'post',
			parameters: params,
			requestTimeout: 25,
			onTimeout: showTimeout,
			onSuccess: showResponse,
			onFailure: showFailure
		} );

		return false;
	}

	function showResponse(request) {
		$('status').innerHTML = request.responseText;

		var response = /Your Let's Chat question has been received/;

		if (request.responseText.match(response)) {
			$('contact_form').reset();
			$('contact_form').toggle();
		} else {
			errorEffects();
			enableSubmit(false);
		}
		
		return false;
	}
	
	function showFailure(request) {
		errorEffects();
		$('submit').value = "Send Question";
	}
	
	function showTimeout() {
		errorEffects();
		$('status').innerHTML = "Your request has failed.  Please try again.";
	}
	
	function showLoader() {
		$('loading').style.display = "block";
	}
	
	function hideLoader() {
		$('loading').style.display = "none";
	}
	
	function enableSubmit(enabled) {
		if (enabled == true) {
			$('submit').disabled = false;
			$('submit').style.color = "#ffffff";
		} else {
			$('submit').disabled = true;
			$('submit').style.color = "gray";
		}
	}
	
	function errorEffects() {
		new Effect.Highlight('status', {startcolor: '#232320', endcolor: '#000000', restorecolor: '#000000'});
	}

