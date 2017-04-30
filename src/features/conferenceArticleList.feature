Feature: Viewing conference list
    In order to create a list of articles from a specific conference
    as a website user
    I must be able to click on a conference in an article list

    Scenario: Click on conference "IEEE Transactions on Microwave Theory and Techniques"
    Given The current page is "http://127.0.0.1"
    When I enter the term "radar" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "radar" in the wordcloud
    And I press on "IEEE Transactions on Microwave Theory and Techniques" in the article list
    Then I should see an article list from "IEEE Transactions on Microwave Theory and Techniques"

    Scenario: Click on conference "2010 Chinese Conference on Pattern Recognition (CCPR)"
    Given The current page is "http://127.0.0.1"
    When I enter the term "computer" into the search bar
    And I press the "searchKeywordButton" button
    And I press on "computer" in the wordcloud
    And I press on "2010 Chinese Conference on Pattern Recognition (CCPR)" in the article list
    Then I should see an article list from "2010 Chinese Conference on Pattern Recognition (CCPR)"
