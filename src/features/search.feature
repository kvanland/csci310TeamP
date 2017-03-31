Feature: Search
    In order to generate a word cloud for a given keyword or author
    as a website user
    I must be able to search for a word cloud based on a given keyword or author

    Scenario: Search for author Adleman
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a Word Cloud based on "Adleman"

    Scenario: Search for author abcdefg
    Given The current page is "http://127.0.0.1"
    When I enter the term "abcdefg" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a message saying there were no results

    Scenario: Search for term computers
    Given The current page is "http://127.0.0.1"
    When I enter the term "computers" into the search bar
    And I press the "searchKeywordButton" button
    Then I should see a Word Cloud based on "computers"

    Scenario: Search for term tuvwxyz
    Given The current page is "http://127.0.0.1"
    When I enter the term "tuvwxyz" into the search bar
    And I press the "searchKeywordButton" button
    Then I should see a message saying there were no results
