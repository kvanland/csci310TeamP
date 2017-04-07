Feature: Search History
    In order to regenerate previously searched wordclouds
    as a website user
    I must be able to access my search history

    Scenario: Search for author adleman
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    And I press the "searchHistoryButton" button
    Then I should see a dropdown with "Adleman"

    Scenario: Generate wordcloud from Adleman in search history
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    And I press the "searchHistoryButton" button
    And I press on "Adleman" in the dropdown
    Then I should see a Word Cloud

    Scenario: Search for keyword jjjj
    Given The current page is "http://127.0.0.1"
    When I enter the term "jjjj" into the search bar
    And I press the "searchAuthorButton" button
    And I press the "searchHistoryButton" button
    Then I should see a dropdown with "jjjj"

    Scenario: Click on jjjj in search history
    Given The current page is "http://127.0.0.1"
    When I enter the term "jjjj" into the search bar
    And I press the "searchAuthorButton" button
    And I press the "searchHistoryButton" button
    And I press on "jjjj" in the dropdown
    Then I should not see a wordcloud