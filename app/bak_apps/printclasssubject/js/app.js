(function(){
	$('#filter_course').on('change', function(){
		var course = $(this).val();
		$.post(ajaxUrl, {act: 'courseInfo', course: course}, function(response) {
			var data = $.parseJSON(response);
			$('#filter_year').html(data.years);
			$('#filter_term').html(data.terms);
			checkSelect();
		});
	});
	$('#filter_year').on('change', function(){
		$('#filter_term option').show();
		if ($(this).val() != ""){
			$('#filter_term option').hide();
			$('#filter_term').find('option[data-filter="'+$(this).val()+'"]').show();
		}
	});
	$('#filter_term, #filter_group_field').on('change', function(){
		var course = $('#filter_course').val();
		var group_field = $('#filter_group_field').val();
		var term = $('#filter_term').val();
		$.post(ajaxUrl, {act: 'getSubject', course: course, group_field: group_field, term: term}, function(response) {
			$('#filter_subject').html(response);
			checkSelect();
		});
	});
	$('#filter_subject').on('change', function() {
		var course = $('#filter_course').val();
		var term = $('#filter_term').val();
		var subject = $(this).val();
		$.post(ajaxUrl, {act: 'getClass', course: course, subject: subject, term: term}, function(response) {
			$('#listClass').html(response);
		});
	});

	$('#printBtn').on('click', function() {
		var template = $('#print_template').val();
		var allVals = [];
		var data = 'act=personal&template=' + template;
		$('#listClass input:checkbox:checked').each(function(){
			allVals.push($(this).val());
			data = data + '&class[]=' + $(this).val();
		});
		if (typeof allVals !== 'undefined' && allVals.length > 0)
			$.download(printUrl, data, 'post');
		else
			alert('Phải chọn lớp cần in trước!');
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