<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarkdownToHtmlController extends Controller
{
    public function index()
    {
        return view('markdown-to-html.index');
    }

    public function convert(Request $request): object
    {
        $markdownTextRequest = $request->markdownText;
        // Converting text to array by new line
        $markdownTextArray = explode("\n", trim($markdownTextRequest));
        foreach ($markdownTextArray as $key => $markdownText) {
            // Calling convert methods for each line if it is not blank line
            if (trim($markdownText)) {
                $markdownText = $this->convertLink($markdownText);
                $markdownText = $this->convertHeader($markdownText);
                $markdownText = $this->convertParagraph($markdownText);
                $markdownTextArray[$key] = $markdownText;
            } else {
                // Appending some random text "<PS>" as a place holder for paragraph separator
                $markdownTextArray[$key] = "<PS>";
            }
        }

        $htmlConvertedText = implode($markdownTextArray);
        // Removing extra p tags added for each line
        $htmlConvertedText = preg_replace('/<\/p>\s?<p>/', '', $htmlConvertedText);
        // Removing place holder for paragraph separator
        $htmlConvertedText = preg_replace('/<PS>/', '', $htmlConvertedText);
    
        return view('markdown-to-html.convert', array(
            'htmlConvertedText' => $htmlConvertedText,
            'markdownText' => $markdownTextRequest
        ));
    }

    private function convertParagraph(string $markdownText): string
    {
        // Converting to paragraph if it is not a header
        if (strpos($markdownText, '<h') === false) {
            $markdownText = sprintf('<p>%s</p>', $markdownText);
        }

        return $markdownText;
    }

    private function convertHeader(string $markdownText): string
    {
        // Finding header level
        $startPos = strpos($markdownText, '#');
        $endPos = strrpos($markdownText, '#');
        $level =  $endPos - $startPos + 1;
        
        if ($startPos !== false && $level <= 6) {
            $heading = substr($markdownText,  $endPos + 1);
            $header = sprintf('<h%d>%s</h%d>', $level, trim($heading), $level);
            $markdownText = substr_replace($markdownText, $header, $startPos);
        }

        return $markdownText;
    }

    private function convertLink(string $markdownText): string
    {
        $linkText = '';
        $text = '';

        // Extracting Link Text which is inside '[]'
        $linkTextStartPos = strpos($markdownText, '[');
        $linkTextEndPos = strpos($markdownText, '](');
        if ($linkTextStartPos && $linkTextEndPos) {
            $textLength =  $linkTextEndPos - ($linkTextStartPos + 1);
            $text = substr($markdownText,  $linkTextStartPos + 1, $textLength);
        }

        // Extracting Link which is inside '()'
        $lastLinkPos = strrpos($markdownText, ')', $linkTextEndPos);
        if ($lastLinkPos) {
            $linklength =  $lastLinkPos - ($linkTextEndPos + 2);
            $linkText = substr($markdownText,  $linkTextEndPos + 2, $linklength);
        }

        // Converting to html link
        if ($text && $linkText) {
            $link = sprintf('<a href="%s">%s</a>', $linkText, $text);
            $markdownText = substr_replace($markdownText, $link, $linkTextStartPos, $linklength + $textLength + 4);
        }

        return $markdownText;
    }
}
