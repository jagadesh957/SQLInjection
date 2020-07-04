             <div id="footer">             
                <p>            
                    <div style="float:left;"><?php echo Chunk::get('footer-links'); ?></div>
                    <div style="float:right;"><?php Action::run('theme_footer'); ?><?php echo Site::powered(); ?></div>
                </p>                    
            </div>            
        </div>
        <?php echo Snippet::get('google-analytics'); ?>                  
    </body>
     
</html>