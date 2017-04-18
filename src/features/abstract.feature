Feature: Search History
    In order to understand what kinds of article contain a selected word
    as a website user
    I must be able to view the abstract of articles with that word highlighted

    Scenario: Click article from "information" wordcloud
    Given The current page is "http://127.0.0.1"
    When I enter the term "decidable" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "logic" in the wordcloud
    And I press on "Temporal logics over unranked trees" in the article list
    Then I should see the abstract of "Temporal logics over unranked trees"

    Scenario: Click article from "Cote" wordcloud
    Given The current page is "http://127.0.0.1"
    When I enter the term "Cote" into the search bar
    And I press the "searchAuthorButton" button
    And I press on "interpersonal" in the wordcloud
    And I press on "Automatic Speaker Identification from Interpersonal Synchrony of Body Motion Behavioral Patterns in Multi-Person Videos" in the article list
    Then I should see the abstract of "Automatic Speaker Identification from Interpersonal Synchrony of Body Motion Behavioral Patterns in Multi-Person Videos"
