<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Airport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class AirportTest extends TestCase
{
    use RefreshDatabase;
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
    * is first letter is latin
    *
    * @return void
    */
    public function test_is_first_letter_is_latin()
    {
        $test = Airport::isFirstLetterLatin('London');
        $this->assertTrue($test);
    }

    /**
     * is first letter is сyrillic
     *
     * @return void
     */
    public function test_is_first_letter_is_сyrillic()
    {
        $test = Airport::isFirstLetterLatin('Лондон');
        $this->assertFalse($test);
    }

    /**
     * test query for model
     * !!! use fake database is phpunit.xml DB_CONNECTION,DB_DATABASE
     * @return void
     */
    public function test_query_for_model()
    {
        // Given we have airports in the database
        $airport1 = Airport::create([
            'iata' => 'LAX',
            'name_ru' => 'Лос-Анджелес',
            'name_en' => 'Los Angeles International',
            'country' => 'USA',
        ]);

        $airport2 = Airport::create([
            'iata' => 'JFK',
            'name_ru' => 'Нью-Йорк',
            'name_en' => 'John F. Kennedy International',
            'country' => 'USA',
        ]);

        // When we call get_my with a query that matches airport1
        $results = Airport::get_airport('Los');

        // Then we should only receive airport1 as a result
        $this->assertCount(1, $results); // Ensure only one result is returned
        $this->assertEquals('LAX', $results[0]['iata']); // Assert the correct airport is returned

    }
}

