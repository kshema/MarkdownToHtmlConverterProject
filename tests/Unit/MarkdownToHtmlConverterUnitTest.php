<?php

namespace Tests\Unit;
namespace App\Http\Controllers;

use Tests\TestCase;
use Illuminate\Http\Request;

class MarkdownToHtmlConverterUnitTest extends TestCase
{
    private function convertMarkDownToHtml(string $markdownText, string $htmlConvertedText)
    {
        $request = Request::create('/convert', 'POST',[
            'markdownText'     =>  $markdownText
        ]);
        $controller = new MarkdownToHtmlController();
        $response = $controller->convert($request);
        $this->assertEquals($response->htmlConvertedText, $htmlConvertedText);
    }

    public function test_that_markdown_header_is_converted_to_html(): void
    {
        $this->convertMarkDownToHtml('# Header one', '<h1>Header one</h1>');
    }

    public function test_that_markdown_header_is_not_converted_to_html_for_invalid_input(): void
    {
        $this->convertMarkDownToHtml('######## Header one', '<p>######## Header one</p>');
    }

    public function test_that_markdown_paragraph_is_converted_to_html(): void
    {
        $this->convertMarkDownToHtml('Hello there', '<p>Hello there</p>');
    }

    public function test_that_markdown_paragraph_is_converted_to_html_for_multiple_lines(): void
    {
        $markdownText = <<<MT
Hello there 
How are you? 
What's going on?
MT;

        $this->convertMarkDownToHtml($markdownText, '<p>Hello there How are you? What\'s going on?</p>');
    }

    public function test_that_markdown_link_is_converted_to_html_inside_paragraph(): void
    {
        $this->convertMarkDownToHtml('Test link [Mailchimp](https://www.mailchimp.com) for testing', '<p>Test link <a href="https://www.mailchimp.com">Mailchimp</a> for testing</p>');
    }

    public function test_that_markdown_link_is_converted_to_html_inside_header(): void
    {
        $this->convertMarkDownToHtml('### This is a header [with a link](http://yahoo.com)', '<h3>This is a header <a href="http://yahoo.com">with a link</a></h3>');
    }
}
