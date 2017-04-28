

QUnit.test( "Search History Test", function( assert ) {
  previousSearches = [];
  addSearch("yes");
  assert.equal(previousSearches[0], "yes", "PASSED");
});

QUnit.test( "Export Plain Text Test", function( assert ) {
  PAGE = [true, true, true];
  var article = [];
  article["title"] = "title nine";
  article["authors"] = ["first auth", "second guy"];
  article["frequency"] = "5";
  article["conference"] = "confguy";
  article["download"] = "download";
  article["bibtex"] = "bibby";

  articleList = [article];
  currentWord = "wordington";
  
  exportPlainText();
  var correctContent = "wordington\n\ntitle: title nine\nauthor: first auth, second guy\nfrequency: 5\nconference: ";
  correctContent += "confguy\ndownload: download\nbibtex: bibby\n\n";
  
  assert.equal(content,correctContent,"Passed");
});

QUnit.test( "Export PDF Test", function( assert ) {
  PAGE = [true, true, true];
  var article = [];
  article["title"] = "title nine";
  article["authors"] = ["first auth", "second guy"];
  article["frequency"] = "5";
  article["conference"] = "confguy";
  article["download"] = "download";
  article["bibtex"] = "bibby";

  articleList = [article];
  currentWord = "wordington";
  
  exportPdf();
  var correctRows = ["title nine", ["first auth", "second guy"], "5", "confguy", "download", "bibby"];
  
  assert.equal(rows,correctRows,"Passed");
});