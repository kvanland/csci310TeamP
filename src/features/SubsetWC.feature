Feature: Creating WC from subset of articles selected
	In order to view a WC from a subset of articles
	as a website user
	I must be able to select the articles and create the WC with them

	Scenario: Create Subset WC from article list page
	Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "computer" in the wordcloud
    And I select the top "2" articles in the table
    And I press the "createSubsetWC" button
    Then I should see a wordcloud made from the "2" articles

    Scenario: Create Subset WC from conference list page
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    And I press on "language" in the wordcloud
    And I select the top "1" articles in the table
    And I press the "createSubsetWC" button
    Then I should see a wordcloud made from the "1" articles

    Scenario: Create WC with no articles selected
    Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "computer" in the wordcloud
    And I select the top "0" articles in the table
    And I press the "createSubsetWC" button
    Then I should see an alert to "Please select a subset of articles"