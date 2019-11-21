async function getIndex(){
    let q = `SELECT excel_name FROM ${dbName}`
    // let q = "index"
    return new Promise((resolve) => {
        queryDb(q).then((res) => {
            let index = [];
            for(let r of res){
                index.push(r.excel_name);
            }
            console.log(`...returning index: ${index}`);
            resolve(index);
        });
    })
}
function queryDb(query){
    console.log(`>> queryDb("${query}")`);
    // "select * from excel where excel_name LIKE '%$search%' OR excel_email LIKE '%$search%'  LIMIT 0 , 10"
    return new Promise((resolve,reject) => {
        var data = new FormData();
        data.append("query", query );
        var xhr = new XMLHttpRequest();
        xhr.open("POST", dbUrl, true);
        xhr.setRequestHeader("charset", "UTF-8");
        xhr.setRequestHeader("cache-control", "no-cache");
        xhr.send(data);     
        xhr.addEventListener("readystatechange", function () {
            if (this.readyState === 4) {
                console.log(`...got reponse from db: [${xhr.response}]`);
                let res = (JSON.parse(xhr.response)).data;
                console.log(`...got reponse from db:<${typeof res}>`);
                console.dir(res);
                resolve(res);
            }
        });

    });

}
function clabSearch(searchTerm,data){
    // console.log(`>> clab-search.clabSearch(${searchTerm},[{data}])`);
    let res = [];
    if(searchTerm.length > 0 && data.length > 0){
        searchTerm = searchTerm.toLowerCase();
        data.some((item)=>{
            if(res.length < 5){
                if(item.toLowerCase().includes(searchTerm)){
                res.push(item);
                }
            }
        });
    }
    // console.log(`...returning [${res}] (${res.length})`);
    return res;
}
function createSuggestionBox(parentNode){
    // console.log(`>> clab-search.createSuggestionBox(${parentNode})`);
    suggsDiv = document.createElement("DIV");
    suggsDiv.setAttribute("id", "suggsDiv");    
    parentNode.appendChild(suggsDiv);
    return suggsDiv;
}
function updateSuggestionBox(suggs,suggsDiv){
    suggsDiv.innerHTML = "";

    if (suggs.length > 0){
        suggsDiv.setAttribute("class", "clab-autocomplete");
        // suggsDiv.setAttribute("class", "autocomplete-items"); 
        suggs.map((s) => {
            b = document.createElement("DIV");
            // b.innerHTML += `<input type='hidden' value='${s}'>`;
            b.innerHTML += `${s}`;
            b.addEventListener('click',() => {
                searchInput.value = s;
                searchSubmitHandler();
            });
            suggsDiv.appendChild(b);
        });
    } else {
        suggsDiv.setAttribute("class", "");
    }
}
async function inputChangeHandler(searchTerm){
    // console.log(`>> clab-search.changeHandler(${searchTerm})`);
    suggs = clabSearch(searchTerm,indexData);
    // console.log(`...suggs == [${suggs}]`);
    updateSuggestionBox(suggs,testDiv);
}
function searchSubmitHandler(){
    console.log(`>> searchSubmitHandler()`);
    searchTerm = searchInput.value;
    inputChangeHandler("");
    searchInput.value = "";
    // let column = "honoree,donator";
    let column = "excel_name";
    // let q = 'dummyBrick';
    let q = `SELECT * FROM ${dbName} WHERE ${column}="${searchTerm}" LIMIT 5`;
    queryDb(q).then(res => {
        console.log(`...db returned: ${res}`);
        updateResultsDiv(res,resultsDiv)
    });
} 
function updateResultsDiv(results,resultsDiv){
    resultsDiv.innerHTML = "";
    if (results.length > 0){
        resultsDiv.setAttribute("class", "clab-results");
        // suggsDiv.setAttribute("class", "autocomplete-items"); 
        results.map((obj) => {
            b = document.createElement("DIV");
            let row = ""
            for(let k in obj){
                row += ` [ ${obj[k]} ]`;
            }
            b.innerHTML += `${row} <br>`;
            b.addEventListener('click',() => {
                alert("TODO: Redirect to brick url.");
            });
            resultsDiv.appendChild(b);
        });
    } else {
        resultsDiv.setAttribute("class", "");
    }
}
function domLoaded() {
    console.log("> clab-search.domLoaded()");
    dbName = "excel";
    dbUrl = "http://advancedkiosksmarketing.com/cblab/dblink.php" 
    searchInput = document.getElementById("clab-search");
    searchForm = document.getElementById("clab-search-form");
    testDiv = document.getElementById("test-div");
    resultsDiv = document.getElementById("results-div");
    suggsDiv = createSuggestionBox(testDiv);

    indexData = [];
    getIndex().then(res => {
        indexData = res;
        console.log(`...loaded index data.  indexData.length == ${indexData.length}`);
    });
}

console.log("> loaded clab-search.js");