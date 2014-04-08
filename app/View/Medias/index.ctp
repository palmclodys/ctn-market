<div class="bloc">
    <div class="content">
		<div id="plupload">
		    <div id="droparea" href="#">
		    	<p><?php echo __d('media',"Déplacer les fichiers ici"); ?></p>
		    	<?php echo __d('media',"ou"); ?><br/>
		    	<a id="browse" href="#"><?php echo __d('media',"Parcourir"); ?></a>
		    	<p class="small">(<?php echo __d('media','%s seulement',implode(', ', $extensions)); ?>)</p>
		    </div>
		</div>
		<table class="head" cellspacing="0">
			<thead>
				<tr>
					<th style="width:55%"><?php echo __d('media',"Médias"); ?></th>
					<th style="width:20%"> &nbsp; </th>
					<th style="width:25%"><?php echo __d('media',"Actions"); ?></th>
				</tr>
			</thead>
		</table>
		<div id="filelist">
			<?php echo $this->Form->create('Media',array('url'=>array('controller'=>'medias','action'=>'order'))); ?>
			<?php foreach($medias as $media): $media = current($media);  ?>
				<?php require('media.ctp'); ?>
			<?php endforeach; ?>
			<?php echo $this->Form->end(); ?>
		</div>

    </div>
</div>

<?php $this->Html->script('/js/jquery.form.js',array('inline'=>false)); ?>
<?php $this->Html->script('/js/plupload.js',array('inline'=>false)); ?>
<?php $this->Html->script('/js/plupload.html5.js',array('inline'=>false)); ?>
<?php $this->Html->script('/js/plupload.flash.js',array('inline'=>false)); ?>
<?php $this->Html->scriptStart(array('inline'=>false)); ?>


jQuery(function(){
	$( "#filelist>form" ).sortable({
		update:function(){
			i = 0;
			$('#filelist>form>div').each(function(){
				i++;
				$(this).find('input').val(i);
			});
			$('#MediaIndexForm').ajaxSubmit();
		}
	});

	var theFrame = $("#medias-<?php echo $ref; ?>-<?php echo $ref_id; ?>", parent.document.body);

	var maxfiles = parseInt('<?php echo $maxfiles[$logged_user['type']]; ?>');

	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash',
		container: 'plupload',
		browse_button : 'browse',
		max_file_size : '10mb',
		flash_swf_url : '<?php echo Router::url('/js/plupload.flash.swf'); ?>',
		url : '<?php echo Router::url(array('controller'=>'medias','action'=>'upload',$ref,$ref_id,'?' => "id=$id")); ?>',
		 filters : [
			{title : "Accepted files", extensions : "<?php echo implode(',', $extensions); ?>"},
		],
		drop_element : 'droparea',
		multipart:true,
		urlstream_upload:true
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {

		if(up.files.length > maxfiles ) {
			up.splice(maxfiles);
			alert('Pas plus de '+maxfiles + ' image(s)');
		} else {
			for (var i in files) {
				$('#filelist>form').prepend('<div class="item" id="' + files[i].id + '">&nbsp; &nbsp;' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <div class="progressbar"><div class="progress"></div></div></div>');
			}
			uploader.start();
			$('#droparea').removeClass('dropping');
			theFrame.css({ height:$('body').height() + 40 });
		}
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#'+file.id).find('.progress').css('width',file.percent+'%')
	});

	uploader.bind('FileUploaded', function(up, file, response){
		var response = jQuery.parseJSON(response.response);
		if(response.error){
			alert(response.error)
		}else{
			$('#'+file.id).before(response.content);
		}
		$('#'+file.id).remove();
	});
	uploader.bind('Error',function(up, err){
		alert(err.message);
		$('#droparea').removeClass('dropping');
		uploader.refresh();
	});
	$('#droparea').bind({
       dragover : function(e){
           $(this).addClass('dropping');
       },
       dragleave : function(e){
           $(this).removeClass('dropping');
       }
	});

	$('a.del').live('click',function(e){
		e.preventDefault();
		elem = $(this);
		if(confirm('<?php echo __d('media',"Voulez vous vraiment supprimer ce média ?"); ?>')){
			$.post(elem.attr('href'),{},function(data){
				elem.parents('.item').slideUp();
			});
		}
		theFrame.animate({ height:theFrame.height() - 40 });
	});

	theFrame.height($(document.body).height() + 50);
});

<?php $this->Html->scriptEnd(); ?>