var playjoomupdate_error_callback = dummy_error_handler;
var playjoomupdate_stat_inbytes = 0;
var playjoomupdate_stat_outbytes = 0;
var playjoomupdate_stat_files = 0;
var playjoomupdate_stat_percent = 0;
var playjoomupdate_factory = null;
var playjoomupdate_progress_bar = null;

/**
 * An extremely simple error handler, dumping error messages to screen
 * 
 * @param error The error message string
 */
function dummy_error_handler(error)
{
	alert("ERROR:\n"+error);
}

/**
 * Performs an AJAX request and returns the parsed JSON output.
 * 
 * @param data An object with the query data, e.g. a serialized form
 * @param successCallback A function accepting a single object parameter, called on success
 * @param errorCallback A function accepting a single string parameter, called on failure
 */
function doAjax(data, successCallback, errorCallback)
{
	var json = JSON.stringify(data);
	if( playjoomupdate_password.length > 0 )
	{
		json = AesCtr.encrypt( json, playjoomupdate_password, 128 );
	}
	var post_data = 'json='+encodeURIComponent(json);


	var structure =
	{
		onSuccess: function(msg, responseXML)
		{
			// Initialize
			var junk = null;
			var message = "";

			// Get rid of junk before the data
			var valid_pos = msg.indexOf('###');
			if( valid_pos == -1 ) {
				// Valid data not found in the response
				msg = 'Invalid AJAX data:\n' + msg;
				if(playjoomupdate_error_callback != null)
				{
					playjoomupdate_error_callback(msg);
				}
				return;
			} else if( valid_pos != 0 ) {
				// Data is prefixed with junk
				junk = msg.substr(0, valid_pos);
				message = msg.substr(valid_pos);
			}
			else
			{
				message = msg;
			}
			message = message.substr(3); // Remove triple hash in the beginning

			// Get of rid of junk after the data
			var valid_pos = message.lastIndexOf('###');
			message = message.substr(0, valid_pos); // Remove triple hash in the end
			// Decrypt if required
			if( playjoomupdate_password.length > 0 )
			{
				try {
					var data = JSON.parse(message);
				} catch(err) {
					message = AesCtr.decrypt(message, playjoomupdate_password, 128);
				}
			}

			try {
				var data = JSON.parse(message);
			} catch(err) {
				var msg = err.message + "\n<br/>\n<pre>\n" + message + "\n</pre>";
				if(playjoomupdate_error_callback != null)
				{
					playjoomupdate_error_callback(msg);
				}
				return;
			}

			// Call the callback function
			successCallback(data);
		},
		onFailure: function(req) {
			var message = 'AJAX Loading Error: '+req.statusText;
			if(playjoomupdate_error_callback != null)
			{
				playjoomupdate_error_callback(msg);
			}
		}
	};

	var ajax_object = null;
	structure.url = playjoomupdate_ajax_url;
	ajax_object = new Request(structure);
	ajax_object.send(post_data);
}

/**
 * Pings the update script (making sure its executable!!)
 * @return
 */
function pingUpdate()
{
	// Reset variables
	playjoomupdate_stat_files = 0;
	playjoomupdate_stat_inbytes = 0;
	playjoomupdate_stat_outbytes = 0;

	// Do AJAX post
	var post = {task : 'ping'};
	doAjax(post, function(data){
		startUpdate(data);
	});
}

/**
 * Starts the update
 * @return
 */
function startUpdate()
{
	// Reset variables
	playjoomupdate_stat_files = 0;
	playjoomupdate_stat_inbytes = 0;
	playjoomupdate_stat_outbytes = 0;

	var post = { task : 'startRestore' };
	doAjax(post, function(data){
		processUpdateStep(data);
	});
}

/**
 * Steps through the update
 * @param data
 * @return
 */
function processUpdateStep(data)
{
	if(data.status == false)
	{
		if(playjoomupdate_error_callback != null)
		{
			playjoomupdate_error_callback(data.message);
		}
	}
	else
	{
		if(data.done)
		{
			playjoomupdate_factory = data.factory;
			window.location = playjoomupdate_return_url;
		}
		else
		{
			// Add data to variables
			playjoomupdate_stat_inbytes += data.bytesIn;
			playjoomupdate_stat_percent = (playjoomupdate_stat_inbytes * 100) / playjoomupdate_totalsize;
			
			// Create progress bar once
			if (playjoomupdate_progress_bar == null) 
			{
				playjoomupdate_progress_bar = new Fx.ProgressBar(document.id('progress'));
			}
			playjoomupdate_progress_bar.set(playjoomupdate_stat_percent);
			playjoomupdate_stat_outbytes += data.bytesOut;
			playjoomupdate_stat_files += data.files;

			// Display data
			document.getElementById('extpercent').innerHTML = new Number(playjoomupdate_stat_percent).formatPercentage(1);
			document.getElementById('extbytesin').innerHTML = new Number(playjoomupdate_stat_inbytes).format();
			document.getElementById('extbytesout').innerHTML = new Number(playjoomupdate_stat_outbytes).format();
			document.getElementById('extfiles').innerHTML = new Number(playjoomupdate_stat_files).format(); 

			// Do AJAX post
			post = {
				task: 'stepRestore',
				factory: data.factory
			};
			doAjax(post, function(data){
				processUpdateStep(data);
			});
		}
	}
}

window.addEvent('domready', function() {
	pingUpdate();
	var el = $$('div.playjoomupdate_spinner');
	el.set('spinner', {class: 'playjoomupdate_spinner'});
	el.spin();
});