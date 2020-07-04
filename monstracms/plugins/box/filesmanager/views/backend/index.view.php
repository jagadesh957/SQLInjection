<h2><?php echo __('Files', 'filesmanager'); ?></h2>
<br />

<!-- Filesmanager_upload_files -->
    <?php
        echo (
            Form::open(null, array('enctype' => 'multipart/form-data')).
            Form::input('file', null, array('type' => 'file', 'size' => '25')).Html::br().
            Form::submit('upload_file', __('Upload', 'filesmanager'), array('class' => 'btn default btn-small')).
            Form::close()
        )
    ?>
<!-- /Filesmanager_upload_files -->

<!-- Filesmanger_path -->
<ul class="breadcrumb">

      <?php
        $path_parts = explode ('/',$path);
        $s = '';
        foreach ($path_parts as $p) {
            $s .= $p.'/';
            if($p == $current[count($current)-2]) $active = ' class="active"'; else $active = ''; 
            echo '<span class="divider">/<span> <li'.$active.'><a href="index.php?id=filesmanager&path='.$s.'">'.$p.'</a></li>';     
        }    
    ?>
</ul>
<!-- /Filesmanger_path -->

<table class="table table-bordered">
    <thead>
        <tr>
            <td><?php echo __('Name', 'filesmanager'); ?></td>
            <td><?php echo __('Extension', 'filesmanager'); ?></td>
            <td><?php echo __('Size', 'filesmanager'); ?></td>
            <td width="30%"><?php echo __('Actions', 'filesmanager'); ?></td>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($dir_list)) foreach ($dir_list as $dir) { ?>
        <tr>        
            <td>
                <b><?php echo Html::anchor($dir, 'index.php?id=filesmanager&path='.$path.$dir.'/'); ?></b>
            </td>
            <td>
               
            </td>
            <td>

            </td>
            <td>
            <?php echo Html::anchor(__('Delete', 'filesmanager'),
                       'index.php?id=filesmanager&delete_dir='.$dir.'&path='.$path,
                       array('class' => 'btn btn-small', 'onclick' => "return confirmDelete('".__('Delete directory: :dir', 'filesmanager', array(':dir' => $dir))."')"));
            ?>
            </td>
        </tr>
        <?php } ?>    
        <?php if (isset($files_list)) foreach ($files_list as $file) { $ext = File::ext($file); ?>
        <?php if ( ! in_array($ext, $forbidden_types)) { ?>
        <?php if (in_array($ext, $image_types)) $class = 'lightbox_trigger'; else $class = ''; ?>
        <tr>        
            <td>
                <?php echo Html::anchor(File::name($file), $site_url.'public/'.$path.$file, array('target'=>'_blank' , 'class' => $class));?>
            </td>
            <td>
                <?php echo $ext; ?>
            </td>
            <td>
                <?php echo Number::byteFormat(filesize($files_path.'/'.$file)); ?>
            </td>
            <td>
            <?php echo Html::anchor(__('Delete', 'filesmanager'),
                       'index.php?id=filesmanager&delete_file='.$file.'&path='.$path,
                       array('class' => 'btn btn-actions', 'onclick' => "return confirmDelete('".__('Delete file: :file', 'filesmanager', array(':file' => $file))."')"));
            ?>                      
            </td>
        </tr>        
        <?php } } ?> 
    </tbody>
</table>