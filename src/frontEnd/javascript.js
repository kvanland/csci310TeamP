
/***************************************************************
                      Word Cloud Model
***************************************************************/
var autoCompleteList; //JSON object array
var wordCloudData; //JSON object array
var lyrics; //String
var songList; //JSON object array

function setAutoCompleteList(autoCompleteListData){ //void
	//autoCompleteListData: JSON object array
	autoCompleteList = autoCompleteListData;
}

function setWordCloudData(data){ //void
	//wordCloudData: Map<String, int>
	wordCloudData = data;
}

function setLyrics(lyricsData){ //void
	//lyrics: string
	lyrics = lyricsData;
}

function setSongList(songListData){ //void
	//songList: JSON object array
	songList = songListData;
}

function getSongList(){ //JSON object array
	return songList;
}

function getWordCloudData(){ //Map<String, int>
	return wordCloudData;
}

function getLyrics(){ //String
	return Lyrics;
}

function getAutoCompleteList(){ //JSON object array
	return autoCompleteList;
}

function autoQueryList(query, callback) { //JSON object array
  callback(requestAutoCompleteList(query));
}

/***************************************************************
                     Data Requester
***************************************************************/

function requestAutoCompleteList(search){ //JSON object array
	var r;
	var artist = search;
	var search = "http://localhost/backend/getSuggestions.php?artist=" + artist;

	  $.ajax({
        url: search,
        success: function (result) {
 
            r = JSON.parse(result);
        },
        async: false
    });


  return r;
	/*
		 TODO
		use AJAX to request autocompleteList raw data
		parse string into json object array
		return array
	*/

}



function requestLyrics(songTitle, artist){ //String
	//songTitle: string, artist: string
	
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


}

function requestWordCloudData(){ //Map<String, int>
	//artistList: JSON object array

	var artists = artistList[0];
	for(var i = 1; i < artistList.length; i++) {
		artists += "," + artistList[i]
	}
	var search = "http://localhost/backend/getWordCloud.php?artists=" + artists;
	$.ajax({
	 	url: search,
	 	success: function (result) {
	 		r = JSON.parse(result);
	 	},
	 	async : false
	 });
	 return r;
	
}

function requestSongList(word, artist){ //JSON object array
	var r;
	var artists = artistList[0];
	for(var i = 1; i < artistList.length; i++) {
		artists += "," + artistList[i]
	}
	var search = "http://localhost/backend/getSongs.php?word=" + word + "&artist=" + artist;
	$.ajax({
	 	url: search,
	 	success: function (result) {
	 		r = JSON.parse(result);
	 	},
	 	async : false
	 });
	 return r;

}

/***************************************************************
                      Word Cloud View Model
***************************************************************/
var currentWord; //String
var currentSong; //JSON object
var artistList = new Array(); //JSON object array

function clearArtistList(){ //void 
	artistList = new Array();
}

function addToArtistList(artist){ //void 
	//artist: string
	artistList.push(artist);
}

function setCurrentWord(word){ //void
	//word: string
	currentWord = word;
}

function setCurrentSong(song){ //void
	//song: JSON Object
	currentSong = song;
}

function clearView(){ //void 
	//Set screen to initial state
	setInvisible("WordCloud");
	setInvisible("Lyrics");
	setInvisible("SongList");
	setInvisible("back");
	clearArtistList();
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

function showLyricPage(){ //void
	setPage(3);
	setVisible("Lyrics");
}

function hideLyricPage(){ //void
	setInvisible("Lyrics");
	document.getElementById("Lyrics").innerHTML = "";
}

function showSongListPage(){ //void
	setPage(2);
	setVisible("SongList");
	populateSongList(getSongList());
}

function hideSongListPage(){ //void
	setInvisible("SongList");
	clearSongList();
}

/***************************************************************
                      Lyrics
***************************************************************/
var lyricsCanvas = document.getElementById("lyricsCanvas");

function populateLyrics(lyrics, artist, word){ //void

	/*
		TODO
		use lyrics from model
		format page
	*/

	
	var lyric = String(lyrics);
	var inner = lyrics.replace(new RegExp(" " +word + " ", "g"), '<span style="color:yellow"> ' + word + ' </span>');
	inner = inner.replace(new RegExp(" " +word + ",", "g"), '<span style="color:yellow"> ' + word + ',</span>');
	inner = currentSong + "<br> <br>" + inner;
     var theDiv = document.getElementById("Lyrics");
	theDiv.innerHTML = inner; 
}

function clearLyrics(){ //void
	lyrics = '';
}

/***************************************************************
                      Song List
***************************************************************/

function populateSongList(songData){ //void
	clearSongList();
	
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


}

function clearSongList(){ //void
	d3.select("#SongList").selectAll("*").remove();
}

function songClickAction(name, artist){ //void
	// Function requests song lyrics, then displays the lyrics
	var lyricData = requestLyrics(name, artist);
	setCurrentSong(name);
	setLyrics(lyricData);
	populateLyrics(lyricData, artist, currentWord);
	hideSearch();
	hideSongListPage();
	showLyricPage();
	setPage(3);
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
                      WordCloud
***************************************************************/
var wCCanvas = document.getElementById("wCCanvas");

function colorToggle() {
	/*
		TODO
		update color on wordcloud
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

	/*
		TODO
		use model data
	*/

	setCurrentWord(word);
	var songList = requestSongList(currentWord, artistList[0]);
	for(var i = 1; i < artistList.length; i++) {

		songList = songList.concat(requestSongList(currentWord, artistList[i]))

	}
	setSongList(songList);
	songList.sort(function(a, b) {
    	return parseFloat(b.Frequency) - parseFloat(a.Frequency);
	});
	hideWordCloudPage();
	hideSearch();
	showSongListPage();
}

/***************************************************************
                      Search
***************************************************************/
var searchButton = document.getElementById("searchButton");
var searchBar = document.getElementById("searchBar");
var shareButton = document.getElementById("shareButton");
var mergeButton = document.getElementById("mergeButton");
var searchContainer = document.getElementById("Search");


//jQuery function to set up the auto complete functionality for the search bar
$("#searchBar").autocomplete({
    minLength: 3, //Sets the minimum search length before autocomplete begins
    source: function(request, callback) { //Obtains an up to date autocomplete list
      var searchParam = request.term;
      autoQueryList(searchParam, callback)
    },
    focus: function (event, ui) {
    	$("#searchBar").val(ui.item.label);
    	document.getElementById("searchButton").disabled = false;
    	return false;
    }
  });

//Setting the types of data for the auto complete function
$("#searchBar").data("ui-autocomplete")._renderItem = function(ul, item){
    var $li = $('<li>'),
      $img = $('<img>');

      //Displays the artist image
      $img.attr({
        src: item.icon,
        alt: item.value
      });
      $img.css("width", "32px");
      $img.css("height", "32px");


      //Displays the artist name
      $li.attr('data-value', item.label);
      $li.append('<a href="#">');
      $li.find('a').append($img).append(item.label);    

      return $li.appendTo(ul);

  }
 

function userTypes() {
	/*
		TODO 
		update autolistevery time user types
	*/
	searchButton.disabled = true;
  var searchString = d3.select("#searchBar").value;

}

function showAutoComplete(search){ //void
//TODO get rid of this function it serves no purpose
}

function hideAutoComplete(){ //void
	setInvisible("autoList");
}

function searchAction(){ //void

	/*
		TODO
		add selected artist to artistList
		handle bad input
	*/

	shiftInputsDown();
	setVisible("back");
	setPage(1);
	clearArtistList();
	addToArtistList(searchBar.value);
	wordCloudData = requestWordCloudData();
	populateWordCloud();
	showWordCloudPage();

}

function shareAction(){ //void
	//facebook API
}

function mergeAction(){ //void

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
