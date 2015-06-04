(function(){
	$('.filter').on('change', function(){
		var course = $('#filter_course').val();
		var group_field = $('#filter_group_field').val();
		$.post(ajaxUrl, {act: 'getClass', course: course, group_field: group_field}, function(response) {
			$('#filter_class').html(response);
			checkSelect();
		});
	});

	$("#filter_class").on('change', function() {
		var id_class = $(this).val();
		$('#listStudents').html('');
		$.post(ajaxUrl, {act: 'classDetails', id_class: id_class}, function(response) {
			$('#listStudents').html(response);
		});
	});

	$('#updateBtn').on('click', function() {
		dataString = $('#myForm').serialize();
		$.ajax({
			url: ajaxUrl,
			type: 'POST',
			data: dataString,
			success: function(response){
				
			}
		})
	});

	var checkSelect = function(){
		$.each($('select'), function() {
			if ($(this).find('option').size() > 1){
				$(this).prop('disabled', false);
			} else {
				$(this).prop('disabled', true);
			}
		});
	}
})(jQuery);