Feature: Viewing article list
    In order to show the list of articles from the wordclud that contain a word
    as a website user
    I must be able to click on a word in the word cloud

    Scenario: Click on word "lunar"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    And I press on "lunar" in the wordcloud
    Then I should see an article list

    Scenario: Click on word "comparator"
    Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "comparator" in the wordcloud
    Then I should see an article list