$(function(){
	$('#filter_course').on('change', function(e) {
		e.preventDefault();
		var course = $(this).val();
		$.post(ajaxUrl, {act: 'changeCourse', course: course}, function(response) {
			$('#listClass').html(response);
			buildTree();
		});
	});

	$(document)
	.on('click', '.classes', function(e) {
		e.preventDefault();
		var id_class = $(this).attr('id');
		$.post(ajaxUrl, {act: 'changeClass', id_class: id_class}, function(response) {
			$('#listStudent').html(response);
		});
	})
	.on('click', '.viewDetail', function(e) {
		e.preventDefault();
		var id_student = $(this).attr('id');
		$.post(ajaxUrl, {act: 'viewDetail', student: id_student}, function(response) {
			if(response){
				$('#myModal .modal-body').html(response);
				$('#myModal').modal();
			}
		});
	});

	var buildTree = function(){
		$('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Thu gọn tùy chọn');
		$('.tree li.parent_li > span').on('click', function(e) {
			var children = $(this).parent('li.parent_li').find(' > ul > li');
			if (children.is(":visible")){
				children.hide('fast');
				if ($(this).find(' > i').hasClass('glyphicon-folder-open'))
					$(this).attr('title', 'Mở rộng tùy chọn').find(' > i').addClass('glyphicon-folder-close').removeClass('glyphicon-folder-open');
				else
					$(this).attr('title', 'Mở rộng tùy chọn').find(' > i').addClass('glyphicon-circle-arrow-down').removeClass('glyphicon-circle-arrow-right');
			} else {
				children.show('fast');
				if ($(this).find(' > i').hasClass('glyphicon-folder-close'))
					$(this).attr('title', 'Mở rộng tùy chọn').find(' > i').addClass('glyphicon-folder-open').removeClass('glyphicon-folder-close');
				else
					$(this).attr('title', 'Mở rộng tùy chọn').find(' > i').addClass('glyphicon-circle-arrow-right').removeClass('glyphicon-circle-arrow-down');
			}
			e.stopPropagation();
		});
	}
});