Feature: Abstract
    In order to understand what kinds of articles contain a selected word
    as a website user
    I must be able to view the abstract of articles with that word highlighted

    Scenario: Click article from "decidable" wordcloud
    Given The current page is "http://127.0.0.1"
    When I enter the term "Implementations of Coherent Software-Defined Dual-Polarized Radars" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "radar" in the wordcloud
    And I press on "Implementations of Coherent Software-Defined Dual-Polarized Radars" in the article list
    Then I should see the abstract of "Implementations of Coherent Software-Defined Dual-Polarized Radars"
