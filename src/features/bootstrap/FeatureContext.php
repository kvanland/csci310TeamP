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
    }

    /**
     * @Then I should see a Word Cloud based on algorithm
     */
    public function iShouldSeeAWordCloudBasedOnAlgorithm()
    {
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
        $progress_bar = $page->findById("status");
        $attribute = $progress_bar->getAttribute("style");
        $original_width = $attribute->getCssValue("width");
        if ($original_width == "100%") {
            return;
        }
        sleep(5000);
        $new_width = $attribute->getCssValue("width");
        if ($original_width == $new_width) {
            throw new Exception("Progress bar doesn't gradually increase");
        }
    }

    /**
     * @Then I should see a Word Cloud
     */
    public function iShouldSeeAWordCloud()
    {
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
}








