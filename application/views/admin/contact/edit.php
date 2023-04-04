<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title"><?=!empty($item->name) ? $item->name : ''?></h1>
		<form id="frm-admin" name="adminForm" action="" method="POST">
			<input type="hidden" id="task" name="task" value="">
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Name</td>
					<td><input type="text" id="name" name="name" class="form-control" value="<?=!empty($item->name) ? $item->name : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Email</td>
					<td><input type="text" id="email" name="email" class="form-control" value="<?=!empty($item->email) ? $item->email : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Phone</td>
					<td><input type="text" id="phone" name="phone" class="form-control" value="<?=!empty($item->phone) ? $item->phone : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Message</td>
					<td><textarea name="content" id="content" class="form-control" rows="5" required="required"><?=!empty($item->content) ? $item->content : ''?></textarea></td>
				</tr>
			</table>
			<div class="text-right">
				<button class="btn btn-sm btn-default btn-cancel">Back</button>
			</div>
		</form>
	</div>
</div>

<script>
$(document).ready(function() {
	$(".btn-save").click(function(){
		submitButton("save");
	});
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
});
</script>