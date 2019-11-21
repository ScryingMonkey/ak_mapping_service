<div class="wrap">
    <h1>AK Mapping: Best Path Search</h1>
    
    <?php settings_errors(); ?>
    <div>
        <p><b>Coming soon: Settings for search bar</b></p>
        <ol>
            <li>Database connectiong settings</li>
            <li>Database columns to pull for autocomplete</li>
        </ol>
    </div>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'ak_search_options_group' ); // from Admin.php->settings->option_group
            do_settings_sections( 'ak_mapping_search' ); // from Admin.php->pages->menu_slug
            submit_button();
        ?>
    </form>
</div>
