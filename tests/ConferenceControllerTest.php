<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConferenceControllerTest extends WebTestCase
{
    private UrlGeneratorInterface $urlGenerator;

    private KernelBrowser $client;


    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->urlGenerator = static::getContainer()->get(UrlGeneratorInterface::class);
    }

    public function testConferencePage() : void
    {
        $crawler = $this->client->request('GET', '/');
        static::assertCount(2, $crawler->filter('h4'));
        $this->client->clickLink('View');
        static::assertPageTitleContains('Amsterdam');
        static::assertResponseIsSuccessful();
        static::assertSelectorTextContains('h2', 'Amsterdam 2019');
        static::assertSelectorExists('div:contains("There are 1 comments")');
    }

    public function testCommentSubmission() : void
    {
        $this->client->request('GET', '/conference/amsterdam-2019');
        $this->client->submitForm('Submit', [
            'comment[author]' => 'Fabien',
            'comment[text]' => 'Some feedback from an automated functional test',
            'comment[email]' => 'me@automat.ed',
            'comment[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);
        static::assertResponseRedirects();
        $this->client->followRedirect();
        static::assertSelectorExists('div:contains("There are 2 comments")');
    }
}
