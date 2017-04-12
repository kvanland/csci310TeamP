Feature: Viewing article list
    In order to easily make new word clouds from an article list
    as a website user
    I must be able to click an author name to make a word cloud absed on that author

    Scenario: Click "Max Kanovich"
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "systems" in the wordcloud
    And I press on "Max Kanovich" in the article list
    Then I should see a Word Cloud

    Scenario: Click "Sonal Mahajan"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Halfond" into the search bar
    And I press the "searchAuthorButton" button
    And I press on "energy" in the wordcloud
    And I press on "Sonal Mahajan" in the article list
    Then I should see a Word Cloud
