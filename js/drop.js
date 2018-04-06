$(document).ready(function() {

	new Dropzone(
		'#app-content-dropit .drop-area', {
			progressElement: null,
			uploadMultiple: false,
			createImageThumbnails: false,
			url: OC.generateUrl('apps/dropit/drop'),
			parallelUploads: 1,
			paramName: 'data',
			init: function() {
				this.on('drop', function() {

				});
				this.on('complete', function() {

				});
				this.on('success', function(file, resp) {
					$('#app-content-dropit #url-drop').val(resp.link);
				});
			}
		}
	);

	new Clipboard('#app-content-dropit .copyButton');

	$('#app-content-dropit .text-submit').on('click', function() {
		$.ajax(
			OC.generateUrl('apps/dropit/text'),
			{
				data: {text: $('#app-content-dropit .text-area')[0].value},
				method: "POST",
			}
		).done(function(resp) {
			$('#app-content-dropit #url-text').val(resp.link);
		});
	});
}
);
