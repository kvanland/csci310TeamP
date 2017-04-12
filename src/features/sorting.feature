Feature: Viewing article list
    In order to view article lists in different orders
    as a website user
    I must be able to sort article lists by frequency, author, conference, and title

    Scenario: Sort by frequency
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "systems" in the wordcloud
    And I press on "frequency" in the article table header
    Then The first article in the article list is "Compositional reasoning and decidable checking for dependent contract types"

    Scenario: Sort by author
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "systems" in the wordcloud
    And I press on "author" in the article table header
    Then The first article in the article list is "Race-free scenarios of message sequence charts"

    Scenario: Reverse sort by conference
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "systems" in the wordcloud
    And I press on "conference" in the article table header
    Then The first article in the article list is "Compositional reasoning and decidable checking for dependent contract types"

    Scenario: Reverse sort by title
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "systems" in the wordcloud
    And I press on "title" in the article table header
    Then The first article in the article list is "Collaborative Planning With Privacy"
