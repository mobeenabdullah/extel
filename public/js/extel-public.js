(function( $ ) {
	'use strict';

	$(document).ready(function () {

		function updatePopupContent(getCertificationID, downloadOnly) {
			$.ajax({
				type: "post",
				url: extel_ajax_obj.ajax_url,
				data: { action: 'data_fetch_certifications', getCertificationID },
				success: function (data) {
					$('.certification__popup-content').html(data);
					if (downloadOnly) {
						$('.cert-next-prev').hide();
						$('.certification__popup-content--right h4').hide();
						$('.certification__popup-content--right .cert-content').hide();
					}
				}
			});
		}

		$('.show-readmore-popup').on('click', function () {
			$('.certificationPopup__cover').css('display', 'flex');
			const getCertificationID = $(this).data('certificate-id');
			updatePopupContent(getCertificationID);
		});

		$('.show-download-popup').on('click', function () {
			$('.certificationPopup__cover').css('display', 'flex');
			const getCertificationID = $(this).data('certificate-id');
			updatePopupContent(getCertificationID, 'downloadOnly');
		});

		$('.certification__popup-content').on('click', '.accreditation-adjacent', function () {
			hidePopup();
			$('.certificationPopup__cover').css('display', 'flex');
			const getCertificationID = $(this).data('certificate-id');
			updatePopupContent(getCertificationID);
		});

		function hidePopup() {
			$('.certificationPopup__cover').css('display', 'none');
			$('.certification__popup-content').html('');
		}

		$('#closePopup').on('click', hidePopup);

		$(document).keydown(function(e) {
			if (e.keyCode == 27) {
				hidePopup();
			}
		});

	});

})( jQuery );
