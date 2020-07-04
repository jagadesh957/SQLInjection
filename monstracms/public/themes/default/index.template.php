<?php Chunk::get('header'); ?>   
    

    <?php Chunk::get('navigation'); ?>

    <div id="main">    

        <div id="content">                                              

            <div>
                <?php Action::run('theme_pre_content'); ?>
            </div>

            <div>
                <?php echo Site::content(); ?>
            </div>

            <div>
                <?php Action::run('theme_post_content'); ?>
            </div>

        </div>

    </div>
    
<?php Chunk::get('footer'); ?>