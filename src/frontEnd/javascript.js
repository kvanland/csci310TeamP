
/***************************************************************
                      Word Cloud Model
***************************************************************/
var wordCloudData; //JSON object array
var articleText; //String
var articleList; //JSON object array


function setWordCloudData(data){ //void
	//wordCloudData: Map<String, int>
	wordCloudData = data;
}

function setArticleText(text){ //void
	//text: string
	articleText = text;
}

function setArticleList(list){ //void
	//list: JSON object array
	articleList = list;
}

function getArticleList(){ //JSON object array
	return articleList;
}

function getWordCloudData(){ //Map<String, int>
	return wordCloudData;
}

function getArticleText(){ //String
	return articleText;
}



/***************************************************************
                     Data Requester
***************************************************************/


function requestArticleText(articleTitle, author, journal){ //String
	//articleTitle: string, author: string, journal: string
	
	/*
	var r;
	var search = "http://localhost:8081/api/find/" + artist + "/" + songTitle;

	 $.ajax({
	 	url: search,
	 	success: function (result) {
	 		r = result;
	 		r = r["lyric"];
	 		r = r.replace(new RegExp("\n", "g"), ' <br>');

	 	},
	 	async: false
	 });
	 return r; 
	 */


}

function requestWordCloudData(type, searchTerm, numberOfArticles){ //Map<String, int>
	//type: int (0 for keyword, 1 for last name)
	// searchTerm: String
	if(numberOfArticles == 0){
		alert("Please enter a valid number of articles.");
		return;
	}


	var searchType;
	if(type == 0)
		searchType = "keyword";
	else
		searchType = "author";

	var search = "http://localhost/csci310TeamP/src/backend/getWordCloud.php?searchTerm=" + searchTerm + "&searchType=" + searchType + "&numArticles=" + numberOfArticles; // might change
    var wc_status = "http://localhost/csci310TeamP/src/backend/getStatus.php";
    var search_result;
	$.ajax({
	 	url: search,
	 	success: function (result) {
	 		search_result = JSON.parse(result);
	 		 if (search_result["result"] === "fail") {
        		alert("No articles found!");
        	}else{
        		updateStatus(0);
        		showStatusBar();
        		pollStatus();
        	}
	 	},
	 	async : true
	});
}

function pollStatus(){
	showStatusBar();
	var progress_result;
	var wc_status = "http://localhost/csci310TeamP/src/backend/getStatus.php";
	$.ajax({
        url: wc_status,
        success: function (result) {
            progress_result = JSON.parse(result);
            console.log(progress_result.status);
            if (progress_result["status"] != 100) {
               	updateStatus(progress_result["status"]);
                pollStatus();
            } else {
            	updateStatus(progress_result["status"]);
            	setTimeout(function(){
	            	hideStatusBar();
	                setWordCloudData(progress_result["wordCloud"]);
	        		shiftInputsDown();
					setVisible("back");
					setPage(1);
	        		populateWordCloud();
	        		showWordCloudPage();
            	}, 500);

               
            	}
                },
                async : true
            });
}



function requestArticleList(word){ //JSON object array
	
	var r;
	
	console.log("requesting");
	var search = "http://localhost/csci310TeamP/src/backend/getArticleList.php?word=" + word;
	$.ajax({
	 	url: search,
	 	success: function (result) {
	 		r = JSON.parse(result);
			 console.log(r);
	 	},
	 	async : false
	 });
	
	 return r;
	
}

/***************************************************************
                      Word Cloud View Model
***************************************************************/
var currentWord; //String
var currentArticle; //JSON object


function setCurrentWord(word){ //void
	//word: string
	currentWord = word;
}

function setCurrentArticle(article){ //void
	//song: JSON Object
	currentArticle = article;
}

function clearView(){ //void 
	//Set screen to initial state
	setInvisible("WordCloud");
	setInvisible("ArticlePage");
	setInvisible("ArticleList");
	setInvisible("back");
	shiftInputsCenter();
	showSearch();
	setPage(0);

}

function showWordCloudPage(){ //void
	setPage(1);
	setVisible("WordCloud");
	setHeight("wCCanvas", "500px");
   
}

function hideWordCloudPage(){ //void 
	setInvisible("WordCloud");
	setHeight("wCCanvas", "0");
}

function showArticlePage(){ //void
	setPage(3);
	setVisible("ArticlePage");
}

function hideArticlePage(){ //void
	setInvisible("ArticlePage");
}

function showArticleListPage(){ //void
	setPage(2);
	setVisible("ArticleList");
	populateArticleList(getArticleList());
}

function hideArticleListPage(){ //void
	setInvisible("ArticleList");
	clearArticleList();
}

function hideStatusBar() {
	statusBar.style.display = "none";
}
function showStatusBar() {
	statusBar.style.display = "block";
}



/***************************************************************
                      ArticlePage
***************************************************************/
var articleCanvas = document.getElementById("articleCanvas");

function populateArticlePage(articleText, author, word){ //void



	/*	
	var lyric = String(lyrics);
	var inner = lyrics.replace(new RegExp(" " +word + " ", "g"), '<span style="color:yellow"> ' + word + ' </span>');
	inner = inner.replace(new RegExp(" " +word + ",", "g"), '<span style="color:yellow"> ' + word + ',</span>');
	inner = currentSong + "<br> <br>" + inner;
     var theDiv = document.getElementById("Lyrics");
	theDiv.innerHTML = inner; 
	*/
}

function clearArticlePage(){ //void
	d3.select('#ArticlePage').selectAll("*").remove();
}

/***************************************************************
                      Article List
***************************************************************/
var li;
// tooltip
var tooltip = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0);

function populateArticleList(articleData){ //void

		clearArticleList();

		var tableData = "[ ";
		for(var i = 0; i < articleData['articles'].length; i++) {
			var curr = articleData['articles'][i];
			if(i == 0) 
				tableData += "{ ";
			else 
				tableData += ", {"
			tableData += "\"" + "title" + "\" : ";
			tableData += "\"" + curr.title + "\", ";
			tableData += "\"" + "authors" + "\" : ";
			var authorString = "";
			for(var j = 0; j < curr.authors.length; j++) {
				authorString += curr.authors[j];
				if(j != curr.authors.length-1) {
					authorString += ", ";
				}
			}
			console.log(authorString);
			tableData += "\"" + authorString + "\", ";
			tableData += "\"" + "frequency" + "\" : ";
			tableData += curr.frequency + ", ";
			tableData += "\"" + "conference" + "\" : ";
			tableData += "\"" + curr.conference + "\", ";
			tableData += "\"" + "download" + "\" : ";
			tableData += "\"" + curr.download + "\", ";
			tableData += "\"" + "bibtex" + "\" : ";
			tableData += "\"" + curr.bibtex + "\" ";
			tableData += "}"
		}
		tableData += " ]";
		console.log(tableData);
		tableData = JSON.parse(tableData);
		//var test = "[ { \"title\" : \"On the Feasibility of Breast Cancer Imaging Systems at Millimeter-Waves Frequencies\", \"authors\" : \"Simona Di Meo,  Pedro Fidel Espín-López,  Andrea Martellosio,  Marco Pasian,  Giulia Matrone,  Maurizio Bozzi,  Giovanni Magenes,  Andrea Mazzanti,  Luca Perregrini,  Francesco Svelto,  Paul Eugene Summers,  Giuseppe Renne,  Lorenzo Preda,  Massimo Bellomi\", \"frequency\" : 1, \"conference\" : \"IEEE Transactions on Microwave Theory and Techniques\", \"download\" : \"down\", \"bibtex\" : \"bib\" },  { \"title\" : \"GaN Single-Polarity Power Supply Bootstrapped Comparator for High-Temperature Electronics\", \"authors\" : \"Xiaosen Liu,  Kevin J. Chen\", \"frequency\" : 1, \"conference\" : \"IEEE Electron Device Letters\", \"download\" : \"down\", \"bibtex\" : \"bib\" }, { \"title\" : \"GaN Single-Polarity Power Supply Bootstrapped Comparator for High-Temperature Electronics\", \"authors\" : \"Xiaosen Liu,  Kevin J. Chen\", \"frequency\" : 5, \"conference\" : \"IEEE Electron Device Letters\", \"download\" : \"down\", \"bibtex\" : \"bib\" } ]";
		//tableData = JSON.parse(test);
		var columns = ['title', 'authors', 'frequency', 'conference', 'download', 'bibtex'];
		var table = d3.select('#ArticleList').append('table');
		var thead = table.append('thead');
		var	tbody = table.append('tbody');
		table.append('caption').text(currentWord).style("font-size", "18px").style("font-weight", "bold");
		console.log("populate");
		// append the header row
		thead.append('tr')
	  		.selectAll('th')
	  		.data(columns).enter()
	  		.append('th')
	    	.text(function (column) { return column; })
	    	.on("click", function (d, i) {
	    		if(i%6 < 4) {
	    			sortTable(i);
	    		}
	    	});

		// create a row for each object in the data
		var rows = tbody.selectAll('tr')
	 	 	.data(tableData)
	  		.enter()
	  		.append('tr');


	  	rows.on("click", function (d, i) {

	  		//songClickAction(songList[i]["Song"], songList[i]["Artist"]);
	  	})
		// create a cell in each row for each column
		var cells = rows.selectAll('td')
	  		.data(function (row) {
	    		return columns.map(function (column) {
	      			return {column: column, value: row[column]};
	    		});
	 		 })
	  		.enter()
	  		.append('td')
	    	.html(function (d, i) { 
	    		// if author cell add list of authors
	    		if(i%6==1) {
	    			var authors = d.value.split(", ");
	    			var t = "<ul>";
	    			for(var j = 0; j < authors.length; j++) {
	    				t = t + "<li>" + authors[j] + "</li>";
	    			}
	    			t += "</ul>";
	    			return t;

	    		} else {
	    			return d.value; 
	    		}
	    	});

	    cells.on("click", function (d, i) {
	    	if(i%6 == 0) {
	    		titleCellAction(d.value);
	    	}
	    	// if(i%6 == 1) {
	    	// 	authorCellAction(d.value);
	    	// }
	    	if(i%6 == 3) {
	    		conferenceCellAction(d.value);
	    	}
	    	if(i%6 == 4) {
	    		downloadCellAction(d.value);
	    	}
	    	if(i%6 == 5) {
	    		bibCellAction(d.value);
	    	}
	    });

	    var authorLi = cells.selectAll("li");
	    authorLi.on("click", function(d, i) {
	    	var author = d3.select(this)[0][0].innerHTML.trim();
	    	authorCellAction(author);
	    });

	    table.attr("id", 'myTable');
	    	

}

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName("TR");
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++; 
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

function titleCellAction(d) {
	alert(d);
}

function authorCellAction(d) {
	homeAction();
	homeAction();
	searchBar.value = d;
	updateStatus(0);
    showStatusBar();
	searchByAuthorAction();
	
}

function conferenceCellAction(d) {
	alert(d);
}

function downloadCellAction(d) {
	alert(d);
}

function bibCellAction(d) {
	alert(d);
}


function clearArticleList(){ //void
	d3.select('#ArticleList').selectAll("*").remove();

}

function articleClickAction(title, author, journal){ //void
	// Function requests article page data, then displays the page
	/*
	var lyricData = requestLyrics(name, artist);
	setCurrentSong(name);
	setLyrics(lyricData);
	populateLyrics(lyricData, artist, currentWord);
	hideSearch();
	hideSongListPage();
	showLyricPage();
	setPage(3);
	*/
}

/***************************************************************
                      HTML
***************************************************************/

//PAGE[0] initial, PAGE[1] cloud, PAGE[2] songs, PAGE[3] lyrics
var PAGE = [true, false, false, false];


//Helper functions
function setPage(page){
	var length = PAGE.length;
	var i;
	for(i = 0; i < length; i++){
		PAGE[i] = false;
	}
	PAGE[page] = true;
}

function homeAction(){
	if(PAGE[0]) {
		// if user is already all the way home do nothing
		return;
	}
	else if(PAGE[1]){ 
		//If the user is on the cloud page go all the way home
		clearView();
		searchBar.value = "";
		wordCloudData = null;
		setPage(0);
	} else { 
		// take user back to cloud page
		setPage(1);
		shiftInputsDown();
		showSearch();
		hideArticlePage();
		hideArticleListPage();
		showWordCloudPage();
	}

}

function backAction(){
		if(PAGE[1]){ 
		//If the user is on the cloud page, reset page
		setPage(0);
		clearView();
		shiftInputsCenter();
		searchBar.value = "";
		
	}else if(PAGE[2]){ 
		//If the user is on the articleList page, go to wordCloudPage
		setPage(1);
		shiftInputsDown();
		showSearch();
		hideArticleListPage();
		showWordCloudPage();
	}else if(PAGE[3]){ 
		//If the user is on the lyrics page, go to songListPage
		setPage(2);
		hideArticlePage();
		showArticleListPage();
	}
}


/***************************************************************
                      Status Bar
***************************************************************/
var statusBar = document.getElementById("status");
function updateStatus(percent) {
	var w = "" + percent + "%"
	document.getElementById("innerBar").style.width = w;

}


/***************************************************************
                      WordCloud
***************************************************************/
var wCCanvas = document.getElementById("wCCanvas");

function populateWordCloud(){ //void
	clearWordCloud(); // reset canvas
	var words = wordCloudData;
    var width = wCCanvas.clientWidth;

    d3.wordcloud()
        .size([width, 500])
        .font('Raleway')
        .selector("#wCCanvas")
        .fill(d3.scale.ordinal().range(["#ff7f7f", "#ffb481", "#fffa8b", "#9cff86", "#89d8ff", "#a8e6cf", "#ECCDFA"]))
        .words(words)
        .start();
  d3.select("#wCCanvas").selectAll("text").on("click", function(d, i) { wordClickAction(d3.select(this).text()); });
console.log("selected word");
            
 
 }


function clearWordCloud(){ //void
	d3.select("#wCCanvas").selectAll("*").remove();
}

function wordClickAction(word){ //void
	//word: string
	setPage(2);
	console.log("clicked");
	setCurrentWord(word);
	var articleList = requestArticleList(currentWord);
	setArticleList(articleList);
	hideWordCloudPage();
	hideSearch();
	showArticleListPage();
}

/***************************************************************
                      Search
***************************************************************/
var searchKeywordButton = document.getElementById("searchKeywordButton");
var searchAuthorButton = document.getElementById("searchAuthorButton");
var searchBar = document.getElementById("searchBar");
var searchContainer = document.getElementById("Search");
var articleInput = document.getElementById("articleInput");



function searchByKeywordAction(){ //void
	hideWordCloudPage();
	requestWordCloudData(0, searchBar.value, articleInput.value);
}

function searchByAuthorAction(){ //void
	hideWordCloudPage();
	requestWordCloudData(1, searchBar.value, articleInput.value);
}

function hideSearch(){ //void 
	setInvisible("Search");
}

function showSearch(){ //void
	setVisible("Search");
}

/***************************************************************
                      Animation Functions
***************************************************************/

function shiftInputsDown(){ //void
	searchContainer.style.paddingTop = "0%";
}

function shiftInputsCenter(){ //void
	setHeight("wCCanvas", "1");
	searchContainer.style.paddingTop = "10%";
}

function setHeight(id, height){ //void
	//id: string, height: string
	document.getElementById(id).style.height = height;
}

function setInvisible(id){ //void 
	//id: string
	document.getElementById(id).style.visibility = "hidden";
}

function setVisible (id) { //void 
	//id: string
	document.getElementById(id).style.visibility = "visible";
}
