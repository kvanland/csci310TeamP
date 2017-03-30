
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

function requestWordCloudData(type, searchTerm){ //Map<String, int>
	//type: int (0 for keyword, 1 for last name)
	// searchTerm: String

	/*
	var search = "http://localhost/backend/getWordCloud.php?artists=" + artists;
	$.ajax({
	 	url: search,
	 	success: function (result) {
	 		r = JSON.parse(result);
	 	},
	 	async : false
	 });
	 return r;
	 */
	
}

function requestArticleList(word){ //JSON object array
	/*
	var r;
	
	var search = "http://localhost/backend/getSongs.php?word=" + word + "&artist=" + artist;
	$.ajax({
	 	url: search,
	 	success: function (result) {
	 		r = JSON.parse(result);
	 	},
	 	async : false
	 });
	 return r;
	*/
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

}

/***************************************************************
                      Song List
***************************************************************/

function populateArticleList(songData){ //void
	clearArticleList();
		/*
		var columns = ['Song', 'Artist', 'Frequency'];
		var table = d3.select('#SongList').append('table');
		var thead = table.append('thead');
		var	tbody = table.append('tbody');
		table.append('caption').text(currentWord);
		console.log("populate");
		// append the header row
		thead.append('tr')
	  		.selectAll('th')
	  		.data(columns).enter()
	  		.append('th')
	    	.text(function (column) { return column; });

		// create a row for each object in the data
		var rows = tbody.selectAll('tr')
	 	 	.data(songData)
	  		.enter()
	  		.append('tr');


	  	rows.on("click", function (d, i) {
	  		songClickAction(songList[i]["Song"], songList[i]["Artist"]);
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
	    	.text(function (d) { return d.value; });
		*/

}

function clearArticleList(){ //void
	d3.select("#ArticleList").selectAll("*").remove();
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
		searchButton.disabled = true;
		searchBar.value = "";
		setPage(0);
	} else { 
		// take user back to cloud page
		setPage(1);
		shiftInputsDown();
		showSearch();
		hideSongListPage();
		hideLyricPage();
		showWordCloudPage();
	}

}

function backAction(){
		if(PAGE[1]){ 
		//If the user is on the cloud page, reset page
		setPage(0);
		clearView();
		searchButton.disabled = true;
		shiftInputsCenter();
		searchBar.value = "";
		
	}else if(PAGE[2]){ 
		//If the user is on the songList page, go to wordCloudPage
		setPage(1);
		shiftInputsDown();
		showSearch();
		hideSongListPage();
		showWordCloudPage();
	}else if(PAGE[3]){ 
		//If the user is on the lyrics page, go to songListPage
		setPage(2);
		hideLyricPage();
		showSongListPage();
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

function colorToggle() {
	/*
		TODO
		make so doesn't re-request data from the backend
	*/
  populateWordCloud();
  
        
}
function populateWordCloud(){ //void
	clearWordCloud(); // reset canvas
	var words = wordCloudData;
	
              var width = wCCanvas.clientWidth;
               if(document.getElementById('blackAndWhite').checked) { 
    d3.wordcloud()
        .size([width, 500])
        .font('Raleway')
        .selector("#wCCanvas")
        .fill(d3.scale.ordinal().range(["black", "white"]))
        .words(words)
        .start();
  }
  else {
    d3.wordcloud()
        .size([width, 500])
        .font('Raleway')
        .selector("#wCCanvas")
        .fill(d3.scale.ordinal().range(["#ff7f7f", "#ffb481", "#fffa8b", "#9cff86", "#89d8ff", "#a8e6cf", "#ECCDFA"]))
        .words(words)
        .start();
  }

  d3.select("#wCCanvas").selectAll("text").on("click", function(d, i) { wordClickAction(d3.select(this).text()); });

            
 
 }


function clearWordCloud(){ //void
	d3.select("#wCCanvas").selectAll("*").remove();
}

function wordClickAction(word){ //void
	//word: string


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



function searchByKeywordAction(){ //void

	/*
		TODO
		add selected artist to artistList
		handle bad input
	*/
	/*

	shiftInputsDown();
	setVisible("back");
	setPage(1);
	wordCloudData = requestWordCloudData();
	populateWordCloud();
	showWordCloudPage();
	*/

}

function searchByAuthorAction(){ //void

	/*
	shiftInputsDown();
	setVisible("back");
	setPage(1);
	if($.inArray(searchBar.value, artistList) > -1)
		return;
	addToArtistList(searchBar.value);
	wordCloudData = requestWordCloudData();
	populateWordCloud();
	showWordCloudPage();
	/*
		TODO
		add selected artist to artistList
		update WC to add artist
		handle bad input
	*/
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
