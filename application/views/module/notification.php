<?
$lang = !isset($_COOKIE['nguyenanh_lang'])?'vi':'en';
$this->lang->load('content',$lang);
$menu					= $this->lang->line('menu');
$website				= $this->lang->line('website');
?>
<script>
$(document).ready(function() {
	<? if ($this->session->flashdata("error")) { ?>
		messageBox("ERROR", "<?=$website['error']?>", "<?=$this->session->flashdata("error")?>", "<?=$website['lose']?>");
	<? } else if ($this->session->flashdata("success")) { ?>
		messageBox("INFO", "<?=$website['success']?>", "<?=$this->session->flashdata("success")?>", "<?=$website['lose']?>");
	<? } else if ($this->session->flashdata("info")) { ?>
		messageBox("WARNING", "<?=$website['success']?>", "<?=$this->session->flashdata("info")?>", "<?=$website['lose']?>");
	<? } ?>
});
</script>