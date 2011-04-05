var replayBrowser = new Object();

replayBrowser.init = function(data, timeouts) {
	// init iframe/modal dialog
	this.initStructure();
	
	// parse data
	this.parseData(data, timeouts);
	
	// set speed
	this.setSpeed(1/6) // 1 minute time-on-page = 10 seconds replay (60 = 5) 
	
	// start the loop
	this.startSession();
}

replayBrowser.frame = null;
replayBrowser.locationBar = null;
replayBrowser.initStructure = function() {
	var dialog = jQuery(
		'<div id="replayBrowser">' +
		'<p><a href="#" id="replayPrevious">Vorige</a> - <a href="#" id="replayNext">Volgende</a> - '+
		'Locatie: <span id="replayLocationbar"></span> - <a href="#" id="replayPlay">Paused</a></p>' +
		'<p id="replayBrowserClose"><a href="#">x</a></p>' +
		'<iframe src=""></iframe>' +	
		'</div>'
		);
	//$('table.datagrid').before(dialog);
	$('#replayWindow').html(dialog);
	
	this.frame = $('#replayBrowser iframe').get(0);
	this.locationBar = $('#replayLocationbar');
	$('#replayPrevious').click(function() { replayBrowser.sessionPrevious(); return false; });
	$('#replayNext').click(function() { replayBrowser.sessionNext(); return false; });
	$('#replayPlay').click(function() { replayBrowser.toggleSession(); return false; });
	$('#replayBrowserClose').click(function() { replayBrowser.sessionEnd(); return false; });
}

replayBrowser.sessionData = null;
replayBrowser.sessionTimes = null;
replayBrowser.sessionPos = 0;
replayBrowser.sessionStatus = false; // false = paused, true = playing
replayBrowser.sessionSpeed = 1;
replayBrowser.sessionTimeout = null;
replayBrowser.parseData = function(data, timeouts) {
	this.sessionPos = 0;
	this.sessionData = data;
	this.sessionTimes = timeouts;
}

replayBrowser.startSession = function() {
	this.setLocation(this.sessionData[this.sessionPos]);
	this.sessionStatus = true;
	$('#replayPlay').text("Playing");
	
	if(this.sessionPos + 1 == this.sessionData.length) return this.stopSession();
	
	var timeout = this.calculateTimeout(this.sessionPos, this.sessionPos+1);
	this.sessionTimeout = setTimeout("replayBrowser.sessionNext()", 1000 * timeout);
	console.log("New timeout: ", timeout);
}

replayBrowser.stopSession = function() {
	this.sessionStatus = false; this.sessionPos = 0;
	$('#replay-play').text("Stopped");
	clearTimeout(this.sessionTimeout);
}

replayBrowser.pauseSession = function() {
	this.sessionStatus = false;
	$('#replayPlay').text("Stopped");
	clearTimeout(this.sessionTimeout);
}

replayBrowser.toggleSession = function() {
	if(this.sessionStatus) this.pauseSession();
	else this.startSession();
}

replayBrowser.sessionEnd = function() {
	$('#replayBrowser').remove();
	clearTimeout(this.sessionTimeout);
}

replayBrowser.sessionNext = function() {
	if(this.sessionPos + 1 < this.sessionData.length) this.sessionPos++;

	this.setLocation(this.sessionData[this.sessionPos]);
	
	clearTimeout(this.sessionTimeout);
	if(this.sessionStatus) {
		if(this.sessionPos + 1 == this.sessionData.length) return this.stopSession();
		
		var timeout = this.calculateTimeout(this.sessionPos, this.sessionPos+1);
		this.sessionTimeout = setTimeout("replayBrowser.sessionNext()", 1000 * timeout);
		console.log("New timeout: ", timeout);
	}
}

replayBrowser.sessionPrevious = function() {
	if(this.sessionPos > 0) replayBrowser.sessionPos--;
	this.setLocation(this.sessionData[this.sessionPos]);
	
	clearTimeout(this.sessionTimeout);
}


replayBrowser.setSpeed = function(speed) {
	this.speed = speed;
}

replayBrowser.setLocation = function(url) {
	try {
		this.frame.src = url
	}
	catch(Exception) { }
	this.locationBar.text(url);
}

replayBrowser.calculateTimeout = function(start, end) {
	var timeout = (this.sessionTimes[end] - this.sessionTimes[start]) * this.speed;
	return (timeout > 5) ? 5 : timeout;
}