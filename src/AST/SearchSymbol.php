<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Visitors\Visitor;

class SearchSymbol extends Symbol
{
    use CanHaveRule;
    use CanBeNegated;

    /** @var string */
    public $content;

    function __construct(string $content)
    {
        $this->content = $content;
    }

    public function accept(Visitor $visitor)
    {
        return $visitor->visitSearch($this);
    }
}
