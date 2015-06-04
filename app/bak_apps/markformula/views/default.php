<?php
if (!defined('AREA')) {
    die('Access denied');
}
?>
<link rel="stylesheet" href="<?php echo AppObject::getBaseFile('app/groupfield/css/dataTables.bootstrap.css') ?>">
<script src="<?php echo AppObject::getBaseFile('app/groupfield/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo AppObject::getBaseFile('app/groupfield/js/dataTables.bootstrap.js') ?>"></script>
<div class="col-sm-3">
    <div class="well">
        Hướng dẫn sử dụng
    </div>
</div>

<div class="col-sm-9">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Đặt tham số công thức tính điểm</h4>
        </div>
        <div class="panel-body">
            <div class="well">
                <div class="message" style="display:none;">
                    <div class="alert alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <span class="alert-message"></span>
                    </div>
                </div>
                <table id="action-table" class="table" style="display:none;">
                    <tr>
                        <td>
                            <input type="hidden" name="id_formula" id="id_formula" value="">
                            <input type="hidden" name="id_course" id="id_course" value="">
                            <input type="text" name="course_name" id="course_name" class="form-control input-sm" disabled>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="element_percent" id="element_percent" class="form-control input-sm" placeholder="Điểm thành phần">
                                <span class="input-group-addon">%</span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="test_percent" id="test_percent" class="form-control input-sm" placeholder="Điểm thành phần">
                                <span class="input-group-addon">%</span>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="essay_mark" id="essay_mark" class="form-control input-sm" placeholder="Điểm xét tốt nghiệp">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <button class="btn btn-xs btn-primary updateBtn"><i class="glyphicon glyphicon-upload"></i> Cập nhật</button>
                            <button class="btn btn-xs btn-danger cancelBtn"><i class="glyphicon glyphicon-remove"></i> Hủy</button>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table table-striped" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Khóa học</th>
                        <th>Điểm thành phần</th>
                        <th>Điểm thi</th>
                        <th>Công thức</th>
                        <th>Điểm xét tốt nghiệp</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($this->items as $item) {
                        $i++;
                        ?>
                        <tr id="formular_<?= $item->id ?>">
                            <td></td>
                            <td class="course_name"><?= $this->courses[$item->id_course] ?></td>
                            <td class="element_percent"><?= $item->element_percent ?> %</td>
                            <td class="test_percent"><?= $item->test_percent ?> %</td>
                            <td class="formula" style="font-weight: bold;color: #f00;">CC*<?= ($item->element_percent / 100) ?> + DT*<?= ($item->test_percent / 100) ?></td>
                            <td class="essay_mark"><?= $item->essay_mark ?></td>
                            <td>
                                <input type="hidden" name="control_str" class="control_str" value="<?= $item->id ?>;<?= $item->id_course ?>;<?= $item->element_percent ?>;<?= $item->test_percent ?>;<?= $item->essay_mark ?>">
                                <button class="btn btn-xs btn-warning editBtn" data-id="<?= $item->id ?>"><i class="glyphicon glyphicon-pencil"></i> Sửa</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ajax xu ly: Made by Duyld2108-->
<script type="text/javascript">
    (function () {
        var list_courses = <?php echo json_encode($this->courses) ?>;
        var oTable = $('#datatable').DataTable({
            ordering: false,
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var index = iDisplayIndex + 1;
                $('td:eq(0)', nRow).html(index);
            }
        });

        var ajaxUrl = "<?= AppObject::getBaseFile('app/markformula/helpers/ajax.php') ?>";

        $(document).on('click', '.btn', function () {
            $('.message .alert').removeClass('alert-success alert-danger');
            $('.message').hide();
        });

        // Edit
        $('#datatable').on('click', '.editBtn', function () {
            var editData = $(this).parent('td').find('.control_str').val();
            var editData = editData.split(';');
            $('#id_formula').val(editData[0]);
            $('#id_course').val(editData[1]);
            $('#course_name').val(list_courses[editData[1]]);
            $('#element_percent').val(editData[2]);
            $('#test_percent').val(editData[3]);
            $('#essay_mark').val(editData[4]);
            $('#action-table').slideDown();
        });

        // Update
        $('#action-table').on('click', '.updateBtn', function () {
            var id_formula = $('#id_formula').val();
            var id_course = $('#id_course').val();
            var element_percent = $('#element_percent').val();
            var test_percent = $('#test_percent').val();
            var essay_mark = $('#essay_mark').val();
            $.post(ajaxUrl, {act: 'update', id_course: id_course, element_percent: element_percent, test_percent: test_percent, id_formula: id_formula, essay_mark: essay_mark}, function (data) {
                console.log(data);
                var data = $.parseJSON(data);
                $('.alert-message').html(data.message);
                $('.message .alert').addClass('alert-' + data.status);
                $('.message').show();
                if (data.status == 'success') {
                    var control_str = data.returndata;
                    var tmp = control_str.split(';');
                    $('#formular_' + tmp[0] + ' .course_name').html(list_courses[tmp[1]]);
                    $('#formular_' + tmp[0] + ' .element_percent').html(tmp[2] + ' %');
                    $('#formular_' + tmp[0] + ' .test_percent').html(tmp[3] + ' %');
                    $('#formular_' + tmp[0] + ' .formula').html('CC*' + tmp[2] / 100 + ' + DT*' + tmp[3] / 100);
                    $('#formular_' + tmp[0] + ' .essay_mark').html(tmp[4]);
                    $('#formular_' + tmp[0] + ' .control_str').val(control_str);
                    $('#action-table').find('input, select').val("");
                    $('#action-table').slideUp();
                }
            });
        });

        // Cancel
        $('#action-table').on('click', '.cancelBtn', function () {
            $('#action-table').find('input, select').val("");
            $('#action-table').slideUp();
        });
    })(jQuery);
</script>