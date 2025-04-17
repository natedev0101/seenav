<?php

namespace App\Services;

use JBBCode\Parser;

class BBCodeService
{
    protected $parser;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->parser->addBBCode("b", "<strong>{param}</strong>");
        $this->parser->addBBCode("i", "<em>{param}</em>");
        $this->parser->addBBCode("u", "<u>{param}</u>");
        $this->parser->addBBCode("url", '<a href="{option}" class="text-blue-400 hover:text-blue-300">{param}</a>', true);
        $this->parser->addBBCode("img", '<img src="{param}" alt="Image" class="max-w-full h-auto rounded-lg">');
        $this->parser->addBBCode("quote", '<blockquote class="border-l-4 border-gray-600 pl-4 my-2 text-gray-400 italic">{param}</blockquote>');
    }

    public function parse($text)
    {
        return $this->parser->parse($text)->getAsHTML();
    }
}
