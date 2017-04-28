Feature: Exporting Article List
    In order to store the article list data
    as a website user
    I must be able to export the article list as a plain text file or pdf

    Scenario: Click the export as plain text
    Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "computer" in the wordcloud
    And I press the "exportPlainTextButton" button
    Then I should download a plain text document that contains all of the article list information

    Scenario: Click the export as pdf
    Given The current page is "http://127.0.0.1"
    When I enter the term "radar" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "radar" in the wordcloud
    And I press the "exportPdfButton" button
    Then I should download a pdf document that contains all of the article list information
