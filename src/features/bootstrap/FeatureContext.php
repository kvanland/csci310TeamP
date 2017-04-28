<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\ClosureContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\MinkExtension\Context\MinkContext;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public $session;
    public function __construct()
    {
        $driver = new \Behat\Mink\Driver\Selenium2Driver('chrome');
        $session = new \Behat\Mink\Session($driver);
        $session->start();
    }

    /**
     * @Given The current page is :arg1
     */
    public function theCurrentPageIs($arg1)
    {
        $session = $this->getSession();
        $session->visit("http://localhost/csci310TeamP/src/index.html");
    }

    /**
     * @When I enter the term :arg1 into the search bar
     */
    public function iEnterTheTermIntoTheSearchBar($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $searchBar = $page->findById("searchBar");
        if (!$searchBar)
            throw new Exception("Search bar could not be found");
        else 
            $searchBar->setvalue($arg1);
    }

    /**
     * @When I press the :arg1 button
     */
    public function iPressTheButton($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $button = $page->findById($arg1);
        if (!$button)
            throw new Exception($arg1 . " not found");
        else 
            $button->click();
        sleep(1);
    }

    /**
     * @Then I should see a Word Cloud based on algorithm
     */
    public function iShouldSeeAWordCloudBasedOnAlgorithm()
    {
        sleep(15); // wait for wordcloud generation to finish 
        $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', "scada"));
        if(!$word){
            throw new Exception("Word " + "scada" + " could not be found");
        }
        $word = $page->find('named', array('content', "cyber-physical"));
        if(!$word){
            throw new Exception("Word " + "cyber-physical" + " could not be found");
        }
        $word = $page->find('named', array('content', "criterion"));
        if(!$word){
            throw new Exception("Word " + "criterion" + " could not be found");
        }
        $word = $page->find('named', array('content', "discrete-time"));
        if(!$word){
            throw new Exception("Word " + "discrete-time" + " could not be found");
        }
        $word = $page->find('named', array('content', "kalman"));
        if(!$word){
            throw new Exception("Word " + "kalman" + " could not be found");
        }

    }

    /**
     * @Then I should see a message saying there were no results
     */
    public function iShouldSeeAMessageSayingThereWereNoResults()
    {
        sleep(2);
        $session = $this->getSession();
        $page = $session->getPage();
        $message = $session->getDriver()->getWebDriverSession()->getAlert_text();
        if (!$message) {
            throw new Exception("No alert window found");
        } else {
            $session->getDriver()->getWebDriverSession()->accept_alert();
        }
    }

    /**
     * @Then I should see a progress bar that gradually increases
     */
    public function iShouldSeeAProgressBarThatGraduallyIncreases()
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $progress_bar = $page->findById("innerBar");
        $style = $progress_bar->getAttribute("style");
        if ($style == "width: 100%;") {
            return;
        }
        sleep(5);
        $new_style = $progress_bar->getAttribute("style");;
        if ($new_style == $style) {
            throw new Exception("Progress bar doesn't gradually increase");
        }
    }

    /**
     * @Then I should see a Word Cloud
     */
    public function iShouldSeeAWordCloud()
    {
        sleep(20); // wait for wordcloud generation to finish 
        $session = $this->getSession();
        $page = $session->getPage();
        $wordCloudCanvas = $page->findById("wCCanvas");
        if(!$wordCloudCanvas){
            throw new Exception("wCCanvas could not be found");
        }else{
            if(!$wordCloudCanvas->isVisible())
                throw new Exception("wcCanvas is not visible");
        }
    }

    /**
     * @When I limit articles to :arg1
     */
    public function iLimitArticlesTo($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $articleField = $page->findById("articleInput");
        if(!$articleField){
            throw new Exception("articleInput could not be found");
        }else{
            $articleField->setvalue($arg1);
        }
    }

    /**
     * @When I press on :arg1 in the wordcloud
     */
    public function iPressOnInTheWordcloud($arg1)
    {
        sleep(20); // let word cloud generation finish
         $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', $arg1));
        if(!$word){
            throw new Exception("Word ".$arg1." could not be found");
        }else{
            $word->click();
        }

    }

    /**
     * @When I press on :arg1 in the article list
     */
    public function iPressOnInTheArticleList($arg1)
    {
        sleep(2);
        $session = $this->getSession();
        $page = $session->getPage();
        $article = $page->find('named', array('content', $arg1));
        if (!$article) {
            throw new Exception("Article ".$arg1." not found.");
        }
        $article->click();
        /*
        $songListDiv = $page->findById("ArticleList");
        $table_rows = $songListDiv->findAll('css', 'tr');
        foreach($table_rows as $row){
            $table_data = $row->findall('css', 'td');
            $par = $table_data[0]->find('css', 'p');
            if($par->getText() == $arg1){
                $par->click();
            }
        }
         */
    }

    /**
     * @Then I should see the abstract of :arg1
     */
    public function iShouldSeeTheAbstractOf($arg1)
    {
        sleep(2);
        $session = $this->getSession();
        $page = $session->getPage();
        $articleDiv = $page->findById("ArticlePage");
        $expectedAbstract; if($arg1 == "Temporal logics over unranked trees")  {
            $expectedAbstract = "Temporal logics over unranked trees  Abstract: We consider unranked trees that have become an active subject of study recently due to XML applications, and characterize commonly used fragments of first-order (FO) and monadic second-order logic (MSO) for them via various temporal logics. We look at both unordered trees and ordered trees (in which children of the same node are ordered by the next-sibling relation), and characterize Boolean and unary FO and MSO queries. For MSO Boolean queries, we use extensions of the μ-calculus: with counting for unordered trees, and with the past for ordered. For Boolean FO queries, we use similar extensions of CTL*. We then use composition techniques to transfer results to unary queries. For the ordered case, we need the same logics as for Boolean queries, but for the unordered case, we need to add both past and counting to the μ-calculus and CTL*. We also consider MSO sibling-invariant queries, that can use the sibling ordering but do not depend on the particular one used, and capture them by a variant of the μ-calculus with modulo quantifiers.";

        }  else if( $arg1 == "Automatic Speaker Identification from Interpersonal Synchrony of Body Motion Behavioral Patterns in Multi-Person Videos") {
                $expectedAbstract = "Automatic Speaker Identification from Interpersonal Synchrony of Body Motion Behavioral Patterns in Multi-Person Videos  Abstract: Interpersonal synchrony, i.e. the temporal coordination of persons during social interactions, was traditionally studied by developmental psychologists. It now holds an important role in fields such as social signal processing, usually treated as a dyadic issue. In this paper, we focus on the behavioral patterns from body motion to identify subtle social interactions in the context of multi-person discussion panels, typically involving more than two interacting individuals. We propose a computer-vision based approach for automatic speaker identification that takes advantage of body motion interpersonal synchrony between participants. The approach characterizes human body motion with a novel feature descriptor based on the pixel change history of multiple body regions, which is then used to classify the motor behavioral patterns of the participants into speaking/non-speaking. Our approach was evaluated on a challenging dataset of video segments from discussion panel scenes collected from YouTube. Results are very promising and suggest that interpersonal synchrony of motion behavior is indeed indicative of speaker/listener roles.";
        }

        $abstract = $articleDiv->getText();
        if ($abstract != $expectedAbstract) {
            print($expectedAbstract);
            print("-----------");
            print($abstract);
            throw new Exception("Abstract does not match for ".$arg1.".");
        }
    }

    /**
     * @Then I should see an article list
     */
    public function iShouldSeeAnArticleList()
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $songListDiv = $page->findById("ArticleList");
        $table_rows = $songListDiv->findAll('css', 'tr');
        foreach($table_rows as $row){
            $table_data = $row->findall('css', 'td');
            if(count($table_data)==0) {
                throw new Exception("No article list seen");
            };
        }
    }

    /**
     * @Then I should see an article list from :arg1
     */
    public function iShouldSeeAnArticleListFrom($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I select the word :arg1
     */
    public function iSelectTheWord($arg1)
    {
        
        $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', $arg1));
        if(!$word){
            throw new Exception("Word " + $arg1 + " could not be found");
        }else{
            $word->click();
        }
    }

    /**
     * @When I select the author :arg1
     */
    public function iSelectTheAuthor($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $songListDiv = $page->findById("ArticleList");
        $table_rows = $songListDiv->findAll('css', 'tr');
        foreach($table_rows as $row){
            $table_data = $row->findall('css', 'td');
            $list = $table_data[1]->find('css', 'ul');
            $authors = $list->findAll('css', 'li');
            foreach ($authors as $author) {
                if($author->getText() == $arg1) {
                    $author->click();
                }
            }
        }
    }

    /**
     * @Then I should see a Word Cloud based on Adelman
     */
    public function iShouldSeeAWordCloudBasedOnAdelman()
    {
        sleep(15); // wait for wordcloud generation to finish 
        $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', "optical"));
        if(!$word){
            throw new Exception("Word " + "optical" + " could not be found");
        }
        $word = $page->find('named', array('content', "compensate"));
        if(!$word){
            throw new Exception("Word " + "compensate" + " could not be found");
        }
        $word = $page->find('named', array('content', "detection"));
        if(!$word){
            throw new Exception("Word " + "detection" + " could not be found");
        }
        $word = $page->find('named', array('content', "modulation"));
        if(!$word){
            throw new Exception("Word " + "modulation" + " could not be found");
        }
        $word = $page->find('named', array('content', "feedback"));
        if(!$word){
            throw new Exception("Word " + "feedback" + " could not be found");
        }
    }

    /**
     * @Then I should see a dropdown with :arg1
     */
    public function iShouldSeeADropdownWith($arg1)
    {
        sleep(2);
        $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', "SearchHistory"));
        $word->click();
        $dropdown = $page->findById("dropdown-content");
        if (!$dropdown) {
            throw new Exception("Dropdown not seen");
        }
    }

    /**
     * @When I press on :arg1 in the dropdown
     */
    public function iPressOnInTheDropdown($arg1)
    {
        sleep(2);
        $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', $arg1));
        $word->click();
    }

    /**
     * @Then I should not see a wordcloud
     */
    public function iShouldNotSeeAWordcloud()
    {
        sleep(20); // wait for wordcloud generation to finish 
        $session = $this->getSession();
        $page = $session->getPage();
        $wordCloudCanvas = $page->findById("wCCanvas");
        if(!$wordCloudCanvas){
            throw new Exception("wCCanvas could not be found");
        }else{
            if($wordCloudCanvas->isVisible())
                throw new Exception("Word Cloud is visible");
        }
    }

    /**
     * @When I press on :arg1 in the article table header
     */
    public function iPressOnInTheArticleTableHeader($arg1)
    {
        sleep(2);
        $session = $this->getSession();
        $page = $session->getPage();
        $content = $page->find('named', array('content', $arg1));
        if (!$content) {
            throw new Exception($arg1. " not found in article header");
        }
        $content->click();
    }

    /**
     * @Then The first article in the article list is :arg1
     */
    public function theFirstArticleInTheArticleListIs($arg1)
    {
//        $session = $this->getSession();
//        $page = $session->getPage();
//        $content = $page->find('css', sprintf('table tr:contains("%s")', $arg1));
//        print_r($content);
//        if(!content){
//            throw new Exception($arg1. " could not be found");
//        }
//        if(strcmp(content,$arg1) != 0){
//            throw new Exception($arg1. " does not match first article in article list");
//        }
        throw new PendingException();

    }

    /**
     * @When I press on :arg1 button
     */
    public function iPressOnButton($arg1)
    {
        $this->iPressTheButton($arg1);
    }

    /**
     * @Then I should download a plain text document that contains all of the article list information
     */
    public function iShouldDownloadAPlainTextDocumentThatContainsAllOfTheArticleListInformation()
    {
        sleep(5);
        if(!file_exists("~/Downloads/download"))
            throw new Exception("Plain text not downloaded");
    }

    /**
     * @Then I should download a pdf document that contains all of the article list information
     */
    public function iShouldDownloadAPdfDocumentThatContainsAllOfTheArticleListInformation()
    {
        throw new PendingException();
    }

    /**
     * @When I select the top :arg1 articles in the table
     */
    public function iSelectTheTopArticlesInTheTable($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see a wordcloud made from the articles
     */
    public function iShouldSeeAWordcloudMadeFromTheArticles()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see an alert to :arg1
     */
    public function iShouldSeeAnAlertTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the article list for :arg1
     */
    public function iShouldSeeTheArticleListFor($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see an article list for :arg1
     */
    public function iShouldSeeAnArticleListFor($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I press on the first :arg1 link
     */
    public function iPressOnTheFirstLink($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should open a new window
     */
    public function iShouldOpenANewWindow()
    {
        throw new PendingException();
    }

    /**
     * @Then the WC image should download
     */
    public function theWcImageShouldDownload()
    {
        throw new PendingException();
    }
}
