Feature: Viewing article list
    In order to show the list of articles from the wordclud that contain a word
    as a website user
    I must be able to click on a word in the word cloud

    Scenario: Click on word "comparator"
    Given The current page is "http://127.0.0.1"
    When I enter the term "On the Feasibility of Breast Cancer Imaging Systems at Millimeter-Waves Frequencies" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "imaging" in the wordcloud
    Then I should see an article list for "imaging"

    Scenario: Click on word "lunar"
    Given The current page is "http://127.0.0.1"
    When I enter the term "Implementations of Coherent Software-Defined Dual-Polarized Radars" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "radar" in the wordcloud
    Then I should see an article list for "lunar"