<?php
  $I = new AcceptanceTester($scenario);
  $I->wantTo('Search Browserstack on Google');
  $I->amOnPage('/');
  $I->see('Google');
  $I->fillField('q', 'BrowserStack');
  $I->click('btnG');
  $I->makeScreenshot('web_page');
  $I->see('BrowserStack');
?>