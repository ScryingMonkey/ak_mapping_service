<div class="wrap">
    <h1>AK Mapping Service Admin Panel</h1>
    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php
            settings_fields( 'ak_options_group' ); // from Admin.php->settings->option_group
            do_settings_sections( 'AK_Mapping_Service' ); // from Admin.php->pages->menu_slug
            submit_button();
        ?>
    </form>
</div>