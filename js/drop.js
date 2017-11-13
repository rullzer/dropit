$(document).ready(function() {

	new Dropzone(
		'#drop', {
			progressElement: null,
			uploadMultiple: false,
			createImageThumbnails: false,
			url: window.location.href + "/drop",
			parallelUploads: 1,
			paramName: 'data',
			init: function() {
				this.on('drop', function() {

				});
				this.on('complete', function() {

				});
				this.on('success', function(file, resp) {
					var li = document.createElement('li');
					li.innerHTML = file.name + ': <a href="' + resp.link + '">' + resp.link + '</a>';
					$('#uploads').append(li);
				});
			}
		}
	);
}
);
