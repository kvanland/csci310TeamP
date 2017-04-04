Feature: Search
    In order to generate a word cloud for a given keyword or author
    as a website user
    I must be able to search for a word cloud based on a given keyword or author

    Scenario: Search for author Adleman
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a Word Cloud

    Scenario: Search for author abcdefg
    Given The current page is "http://127.0.0.1"
    When I enter the term "abcdefg" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a message saying there were no results

    Scenario: Search for term computers
    Given The current page is "http://127.0.0.1"
    When I enter the term "computers" into the search bar
    And I press the "searchKeywordButton" button
    Then I should see a Word Cloud

    Scenario: Search for term fjhaksjdfhladjksf
    Given The current page is "http://127.0.0.1"
    When I enter the term "fjhaksjdfhladjksf" into the search bar
    And I press the "searchKeywordButton" button
    Then I should see a message saying there were no results

    Scenario: Search for term algorithm with one paper
    Given The current page is "http://127.0.0.1"
    When I enter the term "algorithm" into the search bar
    And I limit articles to "1"
    And I press the "searchKeywordButton" button
    Then I should see a Word Cloud based on algorithm
