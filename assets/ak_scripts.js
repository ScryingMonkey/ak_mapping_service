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
function autosuggestBrickSearch(searchTerm, data, filters=[]){
    console.log(`>> ak-mapping-search.autosuggestBrickSearch( \"${searchTerm}\", [ data : ${data.length}],  [ filters : ${filters.length}])`);
    console.dir(filters);
    console.dir(data);
    let res = [];    

    if(searchTerm && searchTerm.length > 0 && data.length > 0){
        searchTerm = searchTerm.toLowerCase();
        filters = ['Honor','Donor']; // TODO: dummy value.  Remove when receiving filters from form
        filters = filters.map( x => x.toLowerCase()); // converts all filter keys to lower case

        data.some( (brick) => {
            let keys = null;
            if (filters !== undefined || filters.length > 0) {
                // if filters is not empty, search only fields in filters
                keys = Object.keys(brick)
                    .map( x => x.toLowerCase()) // converts all brick keys to lower case
                    .filter( x=> filters.includes(x)); // removes keys that are not in filters
                keys.some( key => {
                    if(brick[key].includes(searchTerm)){
                        res.push(brick);
                        return true;
                    }
                });
            } else {  
                // if filters is empty, search all searchTerms (index of all fields)
                brick.searchTerm.split("|").some( t => {
                    if(t.toLowerCase().includes(searchTerm)){
                        res.push(brick);
                        return true;
                    }
                });
            }



            return res.length >= 5;
        });
    }
    
    console.log(`...returning res (${res.length})`);
    return res;
}
function checkForBrickClusters(data){
    // "25904" : {
    //     "brickNumber" : "25904",
    //     "description" : "LtCol\r\nDavid E. Phillips\r\nUSMC 1984-2008",
    //     "donor" : "adam adams",
    //     "honor" : "abraham adams",
    //     "searchTerm" : "25904|abraham adams|adam adams",
    //     "section" : "152"
    //   },

    let res = {};
    let l = "[[";
    let keysToCheck = ['honor','section','donor'];

    keysToCheck.map( k2c => {
        res[k2c] = {};
        data.map( (brick, index) => {
            if(brick[k2c] in res[k2c]){
                l += ".";
                res[k2c][brick[k2c]].push(brick);
            } else {
                l +="+";
                res[k2c][brick[k2c]] = [brick];
            }
            if(index < 10){
                console.log(`...checking [${k2c}]  brick[k2c](${brick[k2c]}) in res[k2c](${res[k2c]}) : ${(brick[k2c] in res[k2c])}`);
                console.dir(brick[k2c]);
                console.dir(res[k2c]);
            }
        });
        l +="]\n\n[";

    });
    l += "]]";
    console.log(l);
    return res;
}
function printBrickClusters(data){
    // data = {
    //     honoree:[
    //         Joe Smoe : [...bricks...],
    //         Tom Smoe : [...bricks...],
    //         ...
    //     ],
    //     donor:[...],
    //     section:[...]
    // };

    Object.keys(data).map( (k2c) => {
        // console.log(`...printing [${k2c}]`);
        console.log(`Unique ${k2c} values: ${Object.keys(data[k2c]).length}`)
        console.log(`...Object.keys(data[${k2c}]).length = ${Object.keys(data[k2c]).length};`);
        console.dir(data[k2c]);
    });
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
            b.innerHTML += `<div class="ak-mapping-search-nameresult">Brick ID(${s.brickNumber}): ${honoree}</div><p>${s.description}</p>`;
            b.addEventListener('click',() => {
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
    let brickId = suggSelection.brickNumber;
    let originSection = ORIGIN_SELECT.value;
    console.log(`...redirecting to directions for origin section [${originSection}] and destination brick id [${brickId}]`);

    // blank out suggestions
    inputChangeHandler("");
    SEARCH_INPUT.value = "";
    SUGGS_DIV.innerHTML = "";

    // Navigate to suggestions page for selected brickId.
    rootUrl = 'http://marine.advancedkiosksmarketing.com';
    params = `originSectionName=${originSection}&destinationBrickNumber=${brickId}`;
    window.location.href = `${rootUrl}/result?${params}`;

    // $.ajax({
    //     type: "POST",
    //     url: "some.php",
    //     data: { name: "John" }
    // }).done(function( msg ) {
    //     alert( "Data Saved: " + msg );
    // });

    // var xhttp = new XMLHttpRequest();
    //   xhttp.open("GET", "myroutine.php", true);
    //   xhttp.send();
    

    // let q = `SELECT * FROM ${dbName} WHERE ${column}="${searchTerm}" LIMIT 5`;
    // queryDb(q).then(res => {
    //     console.log(`...db returned: ${res}`);
    //     updateResultsDiv(res,RESULTS_DIV)
    // });
} 
// function queryDb(query){
//     console.log(`>> queryDb("${query}")`);
//     return new Promise((resolve,reject) => {
//         var data = new FormData();
//         data.append("query", query );
//         var xhr = new XMLHttpRequest();
//         xhr.open("POST", dbUrl, true);
//         xhr.setRequestHeader("charset", "UTF-8");
//         xhr.setRequestHeader("cache-control", "no-cache");
//         xhr.send(data);     
//         xhr.addEventListener("readystatechange", function () {
//             if (this.readyState === 4) {
//                 console.log(`...got reponse from db: [${xhr.response}]`);
//                 let res = (JSON.parse(xhr.response)).data;
//                 console.log(`...got reponse from db:<${typeof res}>`);
//                 console.dir(res);
//                 resolve(res);
//             }
//         });

//     });

// }
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