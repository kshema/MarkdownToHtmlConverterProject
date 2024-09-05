<?php

namespace Tests\Feature;
namespace App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;

class MarkdownToHtmlConverterTest extends TestCase
{
    /**
     * Feature test for markdown to html converter
     */
    public function test_that_markdown_is_converted_to_html(): void
    {
        $markdownText = <<<MT
# Header one

Hello there

How are you?
What's going on?

## Another Header

This is a paragraph [with an inline link](http://google.com). Neat, eh?

## This is a header [with a link](http://yahoo.com)
MT;

        $htmlConvertedText = "<h1>Header one</h1><p>Hello there</p><p>How are you?What's going on?</p><h2>Another Header</h2><p>This is a paragraph <a href=\"http://google.com\">with an inline link</a>. Neat, eh?</p><h2>This is a header <a href=\"http://yahoo.com\">with a link</a></h2>";

        $request = Request::create('/convert', 'POST',[
            'markdownText'     =>  $markdownText
        ]);
        $controller = new MarkdownToHtmlController();
        $response = $controller->convert($request);
        $this->assertEquals($response->htmlConvertedText, $htmlConvertedText);
    }
}
