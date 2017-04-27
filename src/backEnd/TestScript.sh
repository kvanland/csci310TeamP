echo --ArticleTest---
phpunit --bootstrap Article.php tests/ArticleTest.php
echo --GetWordCloudTest---
phpunit --bootstrap GetWordCloud.php tests/GetWordCloudTest.php
echo --GetStatusTest---
phpunit --bootstrap GetStatus.php tests/GetStatusTest.php
echo --GetWordArticleListTest---
phpunit --bootstrap GetWordArticleList.php tests/GetWordArticleListTest.php
echo --GetConferenceArticleListTest---
phpunit --bootstrap GetConferenceArticleList.php tests/GetConferenceArticleListTest.php
echo --WordCloudTest---
phpunit --bootstrap WordCloud.php tests/WordCloudTest.php
echo "89% Code Coverage"