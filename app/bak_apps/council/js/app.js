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

	$('#listStudents').on('click', '.student', function() {
		$('.student').removeClass('selected');
		$(this).addClass('selected');
		var id_student = $(this).data('id');
		$('#essayDetails input,textarea').prop('disabled', false).val('');
		$.post(ajaxUrl, {act: 'getEssay', id_student: id_student}, function(response) {
			var data = $.parseJSON(response);
			$('#essay_name').val(data.vn_name); if (data.vn_name != null) $('#essay_name').prop('disabled', true);
			$('#essay_name_en').val(data.en_name); if (data.en_name != null) $('#essay_name_en').prop('disabled', true);
			$('#chairman').val(data.chairman); if (data.chairman != null) $('#chairman').prop('disabled', true);
			$('#secretary').val(data.secretary); if (data.secretary != null) $('#secretary').prop('disabled', true);
			$('#critic_1').val(data.critic_1); if (data.critic_1 != null) $('#critic_1').prop('disabled', true);
			$('#critic_2').val(data.critic_2); if (data.critic_2 != null) $('#critic_2').prop('disabled', true);
			$('#member').val(data.member); if (data.member != null) $('#member').prop('disabled', true);
		});
	});

	$('#editBtn').on('click', function() {
		$('#essayDetails input,textarea').prop('disabled', false);
	});

	$('#updateBtn').on('click', function() {
		var id_student = $('.student.selected').data('id');
		if (id_student > 0){
			var essay_name = $('#essay_name').val();
			var essay_name_en = $('#essay_name_en').val();
			var chairman = $('#chairman').val();
			var secretary = $('#secretary').val();
			var critic_1 = $('#critic_1').val();
			var critic_2 = $('#critic_2').val();
			var member = $('#member').val();
			$.post(ajaxUrl, {act: 'updateEssay', id_student: id_student, name: essay_name, name_en: essay_name_en, chairman: chairman, secretary: secretary, critic_1: critic_1, critic_2: critic_2, member: member}, function(response) {
				$('.message').html(response);
			});
		}
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