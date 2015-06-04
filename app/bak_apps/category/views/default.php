<?php if (!defined('AREA')) {
    die('Access denied');
} ?><link href="<?php echo AppObject::getBaseFile('app/category/css/style.css') ?>" rel="stylesheet" media="screen"><script>	function checkdata(event) {
        switch (event) {
            case 'new':
                document.getElementById("task").value = "new";
                document.getElementById("category_form").submit();
                break;
            case 'edit':
                document.getElementById("task").value = "edit";
                document.getElementById("category_form").submit();
                break;
            case 'delete':
                document.getElementById("task").value = "delete";
                document.getElementById("category_form").submit();
                break;
        }
    }</script><div id="con_title">	<div class="con_title"><h3>Quản lý chủ đề</h3></div>	<div class="con_description"><strong>Quản lý các chủ đề của bài viết hiện trên trang chủ</strong></div></div><?php /* * ********* */ ?><form action="" method="post" class="form-search" id="category_form">	<div id="con_content">		<div id="con_toonbar">			<div class="con_search">				<input type="text" class="input-medium " id="search" name="search" value="<?php if (isset($_REQUEST['search'])) {
    echo $_REQUEST['search'];
} ?>" onchange="document.adminForm.submit();">				<button type="submit" class="btn">Search</button>				<button onclick="document.getElementById('search').value = '';
            this.form.submit();"  class="btn">Reset</button>			</div>						<div class="con_toonbar">				<button onclick="checkdata('new')"  class="btn">Thêm Mới</button>				<button onclick="checkdata('edit')"  class="btn ">Sửa</button>				<button onclick="checkdata('delete')"  class="btn ">Xóa</button>			</div>		</div>			<?php global $message;
echo $message; ?>		<div class="con_content">			<table class="table table-bordered">				<tr>					<th style="width:5%">#</th>					<th style="width:70%">Tên Chủ Đề</th>					<th style="width:10%">Tác Giả</th>									</tr>									<?php foreach ($this->items as $item) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='check[]' id='check' value='" . $item['id'] . "'></td>";
    echo "<td><a href='admin.php?app=category&task=edit&check[]=" . $item['id'] . "'>" . $item['title'] . "</a></td>";
    echo "<td>" . $item['cread_by'] . "</td>";
    echo "</tr>";
} ?>							</table>			<?php echo $this->pagination; ?>		</div>	</div>		<input type="hidden" name="task" id="task" value="" /></form>