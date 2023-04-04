<div class="cluster">
	<div class="container-fluid">
		<h1 class="page-title">Slide</h1>
		<form id="frm-admin" name="adminForm" action="" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="">
			<table class="table table-bordered">
				<tr>
					<td class="table-head text-right" width="10%">Title</td>
					<td><input type="text" id="title" name="title" class="form-control" value="<?=!empty($item->title) ? $item->title : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Thumbnail</td>
					<td>
						<label class="wrap-upload-thumb" style="background: url('<?=BASE_URL?><?=!empty($item->thumbnail) ? $item->thumbnail : ''?>') no-repeat">
							<input type="file" name="thumbnail" id="file-upload" value="<?=!empty($item->title) ? $item->title : ''?>">
							<i class="fa fa-cloud-upload" aria-hidden="true"></i>
						</label>
					</td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">URL (link)</td>
					<td><input type="text" id="link" name="link" class="form-control" value="<?=!empty($item->link) ? $item->link : ''?>"></td>
				</tr>
				<tr>
					<td class="table-head text-right" width="10%">Description</td>
					<td><textarea name="description" id="description" class="form-control" rows="3" required="required"><?=!empty($item->description) ? $item->description : ''?></textarea></td>
				</tr>
				<tr>
					<td class="table-head text-right">Status</td>
					<td>
						<select id="active" name="active" class="form-control">
							<option value="1">Show</option>
							<option value="0">Hide</option>
						</select>
						<script type="text/javascript">
							$("#active").val("<?=!empty($item->active) ? $item->active : 1 ?>");
						</script>
					</td>
				</tr>
			</table>
			<div class="pull-right">
				<button class="btn btn-sm btn-primary btn-save">Update</button>
				<button class="btn btn-sm btn-default btn-cancel">Cancel</button>
			</div>
		</form>
	</div>
</div>

<script>
$(document).ready(function() {
	jQuery.noConflict();
	$("#file-upload").change(function() {
		readURL(this);
	});
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.wrap-upload-thumb').css({
					"background-image": "url('"+e.target.result+"')"
				});
				$('.wrap-upload-thumb > i').css({
					"color": "rgba(52, 73, 94, 0.38)"
				});
			};
			reader.readAsDataURL(input.files[0]);
		}
	}
	$(".btn-cancel").click(function(){
		submitButton("cancel");
	});
	$(".btn-save").click(function(){
		submitButton("save");
	});
});
</script>