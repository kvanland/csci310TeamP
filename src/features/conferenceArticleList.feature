Feature: Viewing article list
    In order to create a list of articles from a specific conference
    as a website user
    I must be able to click on a conference in an article list

    Scenario: Click on conference "20th Annual Symposium on Foundations of Computer Science (sfcs 1979)"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    And I press on "complexity" in the wordcloud
    And I press on "20th Annual Symposium on Foundations of Computer Science (sfcs 1979)" in the article list
    Then I should see an article list from "20th Annual Symposium on Foundations of Computer Science (sfcs 1979)"

    Scenario: Click on conference "IEEE Electron Device Letters"
    Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "power" in the wordcloud
    And I press on "IEEE Electron Device Letters" in the article list
    Then I should see an article list from "IEEE Electron Device Letters"
