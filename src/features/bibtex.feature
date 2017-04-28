Feature: Bibtex
    In order to look at the article
    as a website user
    I must be able to download the article as a pdf

    Scenario: Click the bibtex link in the article list
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "logic" in the wordcloud
    And I press on the first "bibtexLink" link
    Then I should open a new window