let AUTOSUGGEST_INDEX = ['default'];
let ORIGIN_SECTION_OPTIONS = ['default'];

let SEARCH_INPUT = null;
let SEARCH_FORM = null;
let RESULTS_DIV = null;
let SUGGS_DIV = null;
let ORIGIN_SELECT = null;

// Sets a list of attrs (attrs) on an element (el).
function addAttrs(el, attrs){
    attrs.forEach(a => {
        el.setAttribute(a['key'], a['value']);
    });
}
// Searches data for searchTerm, and returns up to 5 matches.
function autosuggestBrickSearch(searchTerm, data){
    console.log(`>> ak-mapping-search.autosuggestBrickSearch( ${searchTerm}, [ data : ${data.length}] )`);
    
    let res = [];
    if(searchTerm && searchTerm.length > 0 && data.length > 0){
        searchTerm = searchTerm.toLowerCase();

        data.some( (brick) => {
            brick.searchTerm.split("|").some( t => {
                if(t.toLowerCase().includes(searchTerm)){
                    res.push(brick);
                    return true;
                }
            });
            return res.length >= 5;
        });
        // suggs = (suggs) ? res : [];
    }
    console.log(`...returning res (${res.length})`);
    return res;
}
// Called on dom loaded to create Origin dropdown box options
function updateOriginsDropdown(origins){
    console.log(`>> ak-mapping-search.updateOriginsDropdown(${origins})`);

    ORIGIN_SELECT.innerHTML = '';

    // create an option for each origin
    origins.map((o) => {
            option = document.createElement("OPTION");
            option.setAttribute("class", "ak-mapping-origin-option");
            option.setAttribute("value", o.sectionId);
            option.innerHTML = o.label;
            ORIGIN_SELECT.appendChild(option);
        });
}
// Called when value of search input changes.
function updateSuggestionBox(suggs,suggsDiv){
    console.log(`>> ak-mapping-search.updateSuggestionBox( ${suggs}, ${suggsDiv} )`);
    suggsDiv.innerHTML = "";
    console.dir(suggs);
    if (suggs.length > 0){
        // suggsDiv.setAttribute("class", "autocomplete-items"); 
        suggs.map((s) => {
            // s = {
            //     brickNumber: "1",
            //     description: "J.K. Hottendorf,
            //         ↵Major USMC
            //         ↵Nederland, Texas"
            //         donor: "abel albert",
            //     honor: "abigail adrian",
            //     searchTerm: "1|abigail adrian|abel albert",
            //     section: "A"
            // }
            b = document.createElement("DIV");
            b.setAttribute("class", "ak-mapping-autocomplete-suggestion");
            honoree = s.honor.toLowerCase()
                .split(' ')
                .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
                .join(' ');
            b.innerHTML += `<p>Brick ID(${s.brickNumber}): ${honoree}<br>${s.description}</p>`;
            b.addEventListener('click',() => {
                SEARCH_INPUT.value = s;
                searchSubmitHandler(s);
            });
            suggsDiv.appendChild(b);
        });
    } else {
        suggsDiv.setAttribute("class", "");
    }
}
// Called when a autosuggest option is clicked.
function searchSubmitHandler(suggSelection){  
    console.log(`>> ak-mapping-search.searchSubmitHandler(${suggSelection})`);
            // s = {
            //     brickNumber: "1",
            //     description: '',
            //     honor: "abigail adrian",
            //     searchTerm: "1|abigail adrian|abel albert",
            //     section: "A"
            // }
    // get id of selected brick, and origin section number
    brickId = suggsSelection.brickNumber;
    let originSection = ORIGIN_SELECT.value;
    console.log(`...redirecting to directions for origin section [${originSection}] and destination brick id [${suggSelection}]`);

    // blank out suggestions
    inputChangeHandler("");
    SEARCH_INPUT.value = "";
    SUGGS_DIV.innerHTML = "";

    // Navigate to suggestions page for selected brickId.

    // let q = `SELECT * FROM ${dbName} WHERE ${column}="${searchTerm}" LIMIT 5`;
    // queryDb(q).then(res => {
    //     console.log(`...db returned: ${res}`);
    //     updateResultsDiv(res,RESULTS_DIV)
    // });
} 
// Curl autosuggest list from api
async function getAutoSuggestList(){
    console.log(`>> ak-mapping-search.getAutoSuggestList()`);

    let dummy_res = new Promise((resolve) => {
        let mockIndex = ak_mapping_vars.AUTOSUGGEST_INDEX; //TODO: Replace with API call
        // console.dir(mockIndex);
        console.log("...first 5 objects");
        [...Array(5).keys()].forEach( n=> {  console.dir(mockIndex[n]);  });
        
        // convert object to list
        let  reslist = Object.values(mockIndex);
        console.log(`...reslist <${typeof(reslist)}>`);
        console.dir(reslist);

        resolve(reslist);
    });

    return dummy_res;
}
// Curl origin options list from api
async function getOriginOptions(){
    console.log(">> ak-mapping-search.getOriginOptions()"); 
    //TODO: Currently returning mock data.  Need to curl data from php or api.  

    let dummy_res = new Promise((resolve) => {
        mockOptions = ak_mapping_vars.ORIGIN_SECTION_OPTIONS;
        resolve(mockOptions);
    });

    return dummy_res;
}
// Called when search input value changes
async function inputChangeHandler(searchInput){
    console.log(`>> ak-mapping-search.inputChangeHandler(${searchInput})`);
    searchTerm = searchInput.value;
    // suggs = mappingSearch(searchTerm, AUTOSUGGEST_INDEX);
    suggs = autosuggestBrickSearch(searchTerm, AUTOSUGGEST_INDEX);
    // console.log(`...suggs == [${suggs}]`);
    updateSuggestionBox(suggs,SUGGS_DIV);
}
// Called after initial dom is loaded.
function searchBarDomLoaded() {
    console.log(">> ak-mapping-search.searchBarDomLoaded()"); 
    // set constants for dom elements
    SEARCH_INPUT = document.getElementById("ak-mapping-search-input");
    SEARCH_FORM = document.getElementById("ak-mapping-search-form");
    RESULTS_DIV = document.getElementById("ak-mapping-search-results");
    SUGGS_DIV = document.getElementById("ak-mapping-search-suggs");
    ORIGIN_SELECT = document.getElementById("ak-mapping-search-origin-dropdown");

    // get autosuggest list
    let indexData = [];
    getAutoSuggestList().then(res => {
        AUTOSUGGEST_INDEX = res;
        console.log(`...loaded autosuggest list. <${typeof(AUTOSUGGEST_INDEX)}> : ${AUTOSUGGEST_INDEX.length}`);
    }).catch(err => {
        console.log(`ERROR! Could not curl autosuggest list.  ${err}`);
    });

    // get origin options and updating dropdown menu
    getOriginOptions().then(res => {
        ORIGIN_SECTION_OPTIONS = res;
        console.log(`...loaded origin options. <${typeof(ORIGIN_SECTION_OPTIONS)}> : ${ORIGIN_SECTION_OPTIONS.length}`);
        updateOriginsDropdown(res);
    }).catch(err => {
        console.log(`ERROR! Could not curl origin options.  ${err}`);
    });

}

console.log(">> ak_scripts.js loaded");