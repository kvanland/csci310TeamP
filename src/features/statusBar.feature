Feature: Status Bar
    In order to understand that the functionality of the website is working
    As a website user
    I should be able to visually see the progress of the website generating the word cloud

    Scenario: Search for author "Adleman"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Adleman" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a progress bar that gradually increases

    Scenario: Search for author "Cote"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Cote" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a progress bar that gradually increases

    Scenario: Search for author abcdefg
    Given The current page is "http://127.0.0.1"
    When I enter the term "abcdefg" into the search bar
    And I press the "searchAuthorButton" button
    Then I should see a progress bar that gradually increases
