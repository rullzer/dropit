$(document).ready(function() {

	new Dropzone(
		'#app-content-dropit .drop-area', {
			uploadMultiple: false,
			createImageThumbnails: false,
			url: OC.generateUrl('apps/dropit/drop'),
			parallelUploads: 1,
			paramName: 'data',
			clickable: ['.drop-area', '.drop-area .dz-clickable'],
			init: function() {
				var self = this;
				this.on('drop', function() {

				});
				this.on('complete', function(file) {
					self.removeFile(file);
				});
				this.on('success', function(file, resp) {
					self.removeFile(file);
					$('#app-content-dropit #url-drop').val(resp.link);
					$('.url-share').slideDown();
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
			$('#app-content-dropit #url-drop').val(resp.link);
			$('.url-share').slideDown();
		});
	});

	$('#app-content-dropit .text-area').on('change', function() {
		if (!$.trim($(this).val())) {
			$('.drop-text .hint').show();
		} else {
			$('.drop-text .hint').hide();
		}
	});
}
);
