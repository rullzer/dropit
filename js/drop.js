$(document).ready(function() {

	new Dropzone(
		'#app-content-nextdrop .drop-area', {
			progressElement: null,
			uploadMultiple: false,
			createImageThumbnails: false,
			url: OC.generateUrl('apps/nextdrop/drop'),
			parallelUploads: 1,
			paramName: 'data',
			init: function() {
				this.on('drop', function() {

				});
				this.on('complete', function() {

				});
				this.on('success', function(file, resp) {
					$('#app-content-nextdrop #url-drop').val(resp.link);
				});
			}
		}
	);

	new Clipboard('#app-content-nextdrop .copyButton');

	$('#app-content-nextdrop .text-submit').on('click', function() {
		$.ajax(
			OC.generateUrl('apps/nextdrop/text'),
			{
				data: "text=" + $('#app-content-nextdrop .text-area')[0].value,
				method: "POST",
			}
		).done(function(resp) {
			$('#app-content-nextdrop #url-text').val(resp.link);
		});
	});
}
);
