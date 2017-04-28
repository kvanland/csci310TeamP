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
        if($arg1 == "downloadButton")
            sleep(30);
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
     * @When I hover the :arg1 button
     */
    public function iHoverTheButton($arg1)
    {
        if($arg1 == "downloadButton")
            sleep(30);
        $session = $this->getSession();
        $page = $session->getPage();
        $button = $page->findById($arg1);
        if (!$button)
            throw new Exception($arg1 . " not found");
        else 
            $button->mouseOver();
        sleep(1);
    }

    /**
     * @Then I should see a Word Cloud based on algorithm
     */
    public function iShouldSeeAWordCloudBasedOnAlgorithm()
    {
        sleep(40); // wait for wordcloud generation to finish 
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
        sleep(5);
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
        sleep(20);
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
        sleep(40); // wait for wordcloud generation to finish 
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
        sleep(40); // let word cloud generation finish
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
        sleep(10);
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
        sleep(10);
        $session = $this->getSession();
        $page = $session->getPage();
        $articleDiv = $page->findById("ArticlePage");
        $expectedAbstract; if($arg1 == "Implementations of Coherent Software-Defined Dual-Polarized Radars")  {
            $expectedAbstract = "Implementations of Coherent Software-Defined Dual-Polarized Radars  Abstract: Software-defined radio (SDR) platforms represent a compelling solution for low-cost, flexible, dual-polarized radar systems. However, the phase coherency requirements of a dual-polarized radar system between the transmit ports and between the receive ports as well as between transmission and reception, are difficult to attain in popular SDRs. In this paper, we provide high-level overviews of SDR radar systems, dual-polarization radars, and system phase calibration procedures found in literature. The method adopted to achieve coherency involves a manual calibration procedure, which is applied to four dual-polarized radar system configurations designed around commercial off-the-shelf SDR platforms. The implemented, calibrated designs were exercised in a laboratory setting to determine the coherence performance of the SDR-based radar architectures in characterizing a fixed target's full-polarization scattering matrix.  Download Article in PDF format";

        }  else if( $arg1 == "Automatic Speaker Identification from Interpersonal Synchrony of Body Motion Behavioral Patterns in Multi-Person Videos") {
                $expectedAbstract = "Automatic Speaker Identification from Interpersonal Synchrony of Body Motion Behavioral Patterns in Multi-Person Videos  Abstract: Interpersonal synchrony, i.e. the temporal coordination of persons during social interactions, was traditionally studied by developmental psychologists. It now holds an important role in fields such as social signal processing, usually treated as a dyadic issue. In this paper, we focus on the behavioral patterns from body motion to identify subtle social interactions in the context of multi-person discussion panels, typically involving more than two interacting individuals. We propose a computer-vision based approach for automatic speaker identification that takes advantage of body motion interpersonal synchrony between participants. The approach characterizes human body motion with a novel feature descriptor based on the pixel change history of multiple body regions, which is then used to classify the motor behavioral patterns of the participants into speaking/non-speaking. Our approach was evaluated on a challenging dataset of video segments from discussion panel scenes collected from YouTube. Results are very promising and suggest that interpersonal synchrony of motion behavior is indeed indicative of speaker/listener roles.";
        }
        if(count($articleDiv->findAll('css', 'span')) == 0) {
            throw new Exception("Word not highlighted in abstract");
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
         sleep(10);
        $session = $this->getSession();
        $page = $session->getPage();
        $songListDiv = $page->findById("ArticleList");
        $table_rows = $songListDiv->findAll('css', 'tr');
        foreach($table_rows as $row){
            $table_data = $row->findAll('css', 'td');
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
         sleep(10);

        $session = $this->getSession();
            $page = $session->getPage();
            $songListDiv = $page->findById("ArticleList");
            $table_body = $songListDiv->find('css', 'tbody');
            $table_rows = $table_body->findAll('css', 'tr');
            foreach($table_rows as $row){
                $table_data = $row->findAll('css', 'td');
                $par = $table_data[3]->find('css', 'p');
                if($par->getText() != $arg1) {
                    throw new Exception("Failure displaying conference List");
                }
                
            }
        
    }

    /**
     * @When I select the word :arg1
     */
    public function iSelectTheWord($arg1)
    {
        sleep(40);
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
        sleep(10);

        sleep(30);
        $session = $this->getSession();
        $page = $session->getPage();
        $word = $page->find('named', array('content', $arg1));
        if(!$word){
            throw new Exception("Word " + $arg1 + " could not be found");
        }else{
            $word->click();
        }
        /*
        $session = $this->getSession();
        $page = $session->getPage();
        $songListDiv = $page->findById("ArticleList");
        $table_rows = $songListDiv->findAll('css', 'tr');
        foreach($table_rows as $row){
            $table_data = $row->findAll('css', 'td');
            $list = $table_data[1]->find('css', 'ul');
            $authors = $list->findAll('css', 'li');
            foreach ($authors as $author) {
                if($author->getText() == $arg1) {
                    $author->click();
                }
            }
        } */
    }

    /**
     * @Then I should see a Word Cloud based on Adelman
     */
    public function iShouldSeeAWordCloudBasedOnAdelman()
    {
        sleep(40); // wait for wordcloud generation to finish 
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
        sleep(5);
        $session = $this->getSession();
        $page = $session->getPage();
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
        sleep(5);
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
        sleep(30); // wait for wordcloud generation to finish 
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
        sleep(5);
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
        sleep(5);
        $valid = false;
        $session = $this->getSession();
        $page = $session->getPage();
        $articleListDiv = $page->findById("ArticleList");
        $table_rows = $articleListDiv->findAll('css', 'tr');
        foreach($table_rows as $row){
            $table_data = $row->findAll('css', 'td');
            $article = $table_data[0]->find('css', 'p');
            if($article->getText() == $arg1) {
              $valid = true;
            }
        }
        if(!valid) throw new Exception("The First Article in the Article List is not " . $arg1);

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
        $session = $this->getSession();
        $windowNames = $session->getWindowNames();
        if (sizeof($windowNames) < 2) {
            throw new Exception("No pdf document was opened in a new tab");
        }
    }

    /**
     * @Then I should download a pdf document that contains all of the article list information
     */
    public function iShouldDownloadAPdfDocumentThatContainsAllOfTheArticleListInformation()
    {
       
        sleep(5);
        $session = $this->getSession();
        $windowNames = $session->getWindowNames();
        if (sizeof($windowNames) < 2) {
            throw new Exception("No pdf document was opened in a new tab");
        }
    }

    /**
     * @When I select the top :arg1 articles in the table
     */
    public function iSelectTheTopArticlesInTheTable($arg1)
    {
        $number = intval($arg1);
        $session = $this->getSession();
        $page = $session->getPage();
        $articleListDiv = $page->findById("ArticleList");
        $table_body = $songListDiv->find('css'. 'tbody');
        $table_rows = $table_body->findAll('css', 'tr');
        $counter = 0;
        foreach($table_rows as $row){
            if($counter > $number){
                break;
            }
            $table_data = $row->findAll('css', 'td');
            $list = $table_data[6]->find('css', 'input');
            $list.click();
        }
    }

    /**
     * @Then I should see a wordcloud made from the :arg1 articles
     */
    public function iShouldSeeAWordcloudMadeFromTheArticles($arg1)
    {   

        $number = intval($arg1);
        if($number == 2) {
            sleep(40); // wait for wordcloud generation to finish 
            $session = $this->getSession();
            $page = $session->getPage();
            $word = $page->find('named', array('content', "imaging"));
            if(!$word){
                throw new Exception("Word " + "imaging" + " could not be found");
            }
            $word = $page->find('named', array('content', "image"));
            if(!$word){
                throw new Exception("Word " + "image" + " could not be found");
            }
            $word = $page->find('named', array('content', "microwave"));
            if(!$word){
                throw new Exception("Word " + "microwave" + " could not be found");
            }
            $word = $page->find('named', array('content', "European"));
            if(!$word){
                throw new Exception("Word " + "European" + " could not be found");
            }
            $word = $page->find('named', array('content', "rotation"));
            if(!$word){
                throw new Exception("Word " + "rotation" + " could not be found");
            }
        } else if ($number == 1) {
            sleep(40); // wait for wordcloud generation to finish 
            $session = $this->getSession();
            $page = $session->getPage();
            $word = $page->find('named', array('content', "data"));
            if(!$word){
                throw new Exception("Word " + "data" + " could not be found");
            }
            $word = $page->find('named', array('content', "language"));
            if(!$word){
                throw new Exception("Word " + "language" + " could not be found");
            }
            $word = $page->find('named', array('content', "syntax"));
            if(!$word){
                throw new Exception("Word " + "syntax" + " could not be found");
            }
            $word = $page->find('named', array('content', "procedure"));
            if(!$word){
                throw new Exception("Word " + "procedure" + " could not be found");
            }
            $word = $page->find('named', array('content', "evaluation"));
            if(!$word){
                throw new Exception("Word " + "evaluation" + " could not be found");
            }
        }
    }

    /**
     * @Then I should see an alert to :arg1
     */
    public function iShouldSeeAnAlertTo($arg1)
    {
        if($arg1 != $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text()) {
            throw new Exception("No Alert");
        }
    }

    /**
     * @Then I should see an article list for :arg1
     */
    public function iShouldSeeAnArticleListFor($arg1)
    {
        sleep(5);
         if($arg1 == "imaging") {
            $session = $this->getSession();
            $page = $session->getPage();
            $songListDiv = $page->findById("ArticleList");
            $table_body = $songListDiv->find('css', 'tbody');
            $row = $table_body->find('css', 'tr');
            $table_data = $row->findAll('css', 'td');
                $par = $table_data[0]->find('css', 'p');
                if($par->getText() != "On the Feasibility of Breast Cancer Imaging Systems at Millimeter-Waves Frequencies") {
                    echo $par->getText();
                    throw new Exception("Failure displaying article List for Test 1");
                }
                
            
        } else if($arg1 == "lunar") {

            $session = $this->getSession();
        $page = $session->getPage();
        $songListDiv = $page->findById("ArticleList");
        $table_body = $songListDiv->find('css', 'tbody');
        $table_rows = $table_body->findAll('css', 'tr');
        if(count($table_rows) != 1) {
            throw new Exception("Not correct # articles in article List");
        }
        }
    }

    /**
     * @When I press on the first :arg1 link
     */
    public function iPressOnTheFirstLink($arg1)
    {
        sleep(5);
        if($arg1 == "bibtexLink") {
            $session = $this->getSession();
            $page = $session->getPage();
            $songListDiv = $page->findById("ArticleList");
            $table_body = $songListDiv->find('css' ,'tbody');
            $table_rows = $table_body->findAll('css', 'tr');
            $cells = $table_rows[0]->findAll('css', 'td');
            $bib_cell = $cells[5];
            $bib_cell->click();
        
        } else if($arg1 == "downloadLink") {

            $session = $this->getSession();
            $page = $session->getPage();
            $songListDiv = $page->findById("ArticleList");
            $table_body = $songListDiv->find('css' ,'tbody');
            $table_rows = $table_body->findAll('css', 'tr');
            $cells = $table_rows[0]->findAll('css', 'td');
            $bib_cell = $cells[4];
            $bib_cell->click();

        }
    }

    /**
     * @Then I should open a new window
     */
    public function iShouldOpenANewWindow()
    {
        
        sleep(5);
        $session = $this->getSession();
        $windowNames = $session->getWindowNames();
        if (sizeof($windowNames) < 2) {
            throw new Exception("No pdf document was opened in a new tab");
        }
    }

    /**
     * @Then the WC image should download
     */
    public function theWcImageShouldDownload()
    {
        
        sleep(5);
        $session = $this->getSession();
        $windowNames = $session->getWindowNames();
        if (sizeof($windowNames) < 2) {
            throw new Exception("No pdf document was opened in a new tab");
        }
    }
}
