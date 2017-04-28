Feature: Click author WC
    In order to easily make new word clouds from an article list
    as a website user
    I must be able to click an author name to make a word cloud absed on that author

    Scenario: Click "Robert Kossler"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Implementations of Coherent Software-Defined Dual-Polarized Radars" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "radar" in the wordcloud
    And I select the author "Robert Kossler"
    Then I should see a Word Cloud


