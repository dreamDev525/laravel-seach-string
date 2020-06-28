<?php

namespace Lorisleiva\LaravelSearchString\Tests;

use Lorisleiva\LaravelSearchString\Visitors\AttachRulesVisitor;
use Lorisleiva\LaravelSearchString\Visitors\InlineDumpVisitor;
use Lorisleiva\LaravelSearchString\Visitors\RemoveKeywordsVisitor;

/**
 * @see RemoveKeywordsVisitor
 */
class VisitorRemoveKeywordsTest extends VisitorTest
{
    public function visitors($manager, $builder, $model)
    {
        return [
            new AttachRulesVisitor($manager),
            new RemoveKeywordsVisitor(),
            new InlineDumpVisitor(),
        ];
    }

    public function success()
    {
        return [
            // It transforms keyword queries into empty symbols.
            ['foo:bar', '/^foo$/', 'EMPTY'],
            ['foo in (1,2,3)', '/f/', 'EMPTY'],

            // It leaves queries that do not match intact.
            ['foo:1,2', '/^baz$/', 'LIST(foo in [1, 2])'],
            ['foo:"Hello world"', 'f', 'QUERY(foo = Hello world)'],
        ];
    }

    /**
     * @test
     * @dataProvider success
     * @param $input
     * @param $rule
     * @param $expected
     */
    public function visitor_remove_keywords_success($input, $rule, $expected)
    {
        $model = $this->getModelWithKeywords(['banana_keyword' => $rule]);
        $this->assertAstFor($input, $expected, $model);
    }
}
