<?php

namespace Tests\Unit;

use App\Models\Review;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{
    public static function validRatingProvider(): array
    {
        return [
            [1],
            [2],
            [3],
            [4],
            [5]
        ];
    }

    public static function invalidRatingProvider(): array
    {
        return [
            [-1],
            [0],
            [6],
            [10],
            ['abc'],
            [null]
        ];
    }

    #[Test]
    #[DataProvider('validRatingProvider')]
    public function rating_is_valid_if_value_is_between_one_and_five(int $validValue): void
    {
        $review = new Review();
        $review->rating = $validValue;

        $this->assertEquals($validValue, $review->rating);
        $this->assertGreaterThanOrEqual(1, $review->rating);
        $this->assertLessThanOrEqual(5, $review->rating);
    }

    #[Test]
    #[DataProvider('invalidRatingProvider')]
    public function rating_is_null_if_given_value_is_invalid(mixed $invalidValue): void
    {
        $review = new Review();
        $review->rating = $invalidValue;

        $this->assertNull($review->rating);
    }
}
