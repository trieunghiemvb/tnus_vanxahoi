(function(){
	$('.filter').on('change', function() {
		$('.depend').prop('disabled', $('#filter_course').val() != "" ? false : true);
		$('#filter_class').prop('disabled', $('#filter_course').val() != "" ? false : true);
		$('#filter_major').prop('disabled', $('#filter_group_field').val() > 1 ? false : true);

		if ($('#filter_course').val() != ""){
			var course = $('#filter_course').val(), group_field = $('#filter_group_field').val();
			$.post(ajaxUrl, {act: 'filterCourse', course: course, group_field: group_field}, function(response) {
				console.log(response);
				var data = $.parseJSON(response);
				if (data.classes != "")
					$('#filter_class').html(data.classes);
				if (data.years != "")
					$('#filter_year').html(data.years);
				if (data.terms != "")
					$('#filter_term').html(data.terms);
				$('#filter_major').html(data.majors);
			});
		}
	});

	$('#filter_class').on('change', function() {
		var id_class = $(this).val();
		$.post(ajaxUrl, {act: 'listStudent', id_class: id_class}, function(response) {
			$('#listStudent').html(response);
		});
	});

	$('#printPersonal').on('click', function() {
		var signer = $("#signer").val();
		var allVals = [];
		var data = 'act=personal&signer=' + signer;
		$('input.print_student:checkbox:checked').each(function(){
			allVals.push($(this).val());
			data = data + '&students[]=' + $(this).val();
		});
		if (typeof allVals !== 'undefined' && allVals.length > 0)
			$.download(printUrl, data, 'post');
		else
			alert('Phải chọn Học viên trước!');
		console.log(allVals);
	});
	$('#printByTerm').on('click', function() {
		var id_class = $('#filter_class').val();
		var term = $('#filter_term').val();
		var data = 'act=printterm&class=' + id_class + '&term='+term;
		if (id_class > 0 && term > 0)
			$.download(printUrl, data, 'post');
		else
			alert('Phải chọn Lớp và học kỳ cần in trước');
	});
	$('#printByYear').on('click', function() {
		var id_class = $('#filter_class').val();
		var year = $('#filter_year').val();
		var print_type = $('.mark_select_type:checked').val();
		var data = 'act=printyear&class=' + id_class + '&year='+year + '&sel_type='+print_type;
		if (id_class > 0 && year != "")
			$.download(printUrl, data, 'post');
		else
			alert('Phải chọn Lớp và năm học cần in trước');
	});
	$('#printByCourse').on('click', function() {
		var id_class = $('#filter_class').val();
		var print_type = $('.mark_select_type:checked').val();
		var data = 'act=printcourse&class=' + id_class + '&sel_type='+print_type;
		if (id_class > 0)
			$.download(printUrl, data, 'post');
		else
			alert('Hãy chọn lớp trước');
	});

	$(document)
	.on('change', '#checkAllBtn', function(){
		// Checkall Button Change
		var id = $(this).data('id');
		$('tbody#listStudent input:checkbox:not(:disabled)').prop('checked', this.checked);
	})
	.on('change', '#listStudent input:checkbox', function(){
		// Count Checkboxes
		var totalCheckboxes = $('tbody#listStudent input:checkbox').length;
		// Count Checkboxes checked
		var totalChecked = $('tbody#listStudent input:checkbox:checked').length;
		if(totalCheckboxes == totalChecked)
			$('#checkAllBtn').prop('checked', true);
		else
			$('#checkAllBtn').prop('checked', false);
	})
})(jQuery);