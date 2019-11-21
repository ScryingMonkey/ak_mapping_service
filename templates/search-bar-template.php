<div class="wrap">    
    <?php settings_errors(); ?>
    <div>

        <form id="ak-mapping-search-form" 
            class="ak-mapping-search-form" 
            onsubmit="return false;">

            Search: <input 
                type="text" 
                id="ak-mapping-search-input" 
                class="ak-mapping-search-input" 
                placeholder=" Search here ... " 
                value="" 
                oninput="inputChangeHandler(this,this.value)"
                autocomplete="ak-do-not-autofill" />

            Origin: <select 
                id="ak-mapping-search-origin-dropdown"
                class="ak-mapping-search-origin-dropdown">
            </select>

            <input 
                type="submit" 
                value="Submit" 
                onclick="searchSubmitHandler()" />

        </form>

        <div 
            id="ak-mapping-search-suggs" 
            class="ak-mapping-search-suggs">
        </div>

        <div 
            id="ak-mapping-search-results" 
            class="ak-mapping-search-results">
        </div>

        <script>
            console.log("...loaded search-bar-template.");
            searchBarDomLoaded();
        </script>

    </div>
</div>