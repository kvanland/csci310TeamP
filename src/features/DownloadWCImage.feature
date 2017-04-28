Feature: Download WC Image
	In order to save a WC for later viewing
	as a website user
	I must be able to download the WC as an image

Scenario: Download WC from searchByAuthor
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    And I press the "downloadButton" button
    Then the WC image should download


Scenario: Download WC from searchByKeyword
    Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press the "downloadButton" button
    Then the WC image should download