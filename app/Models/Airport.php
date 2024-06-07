<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Airport extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'iata',
        'name_en',
        'name_ru',
        'country'
    ];
    use HasFactory;


    /**
     * Check if first letter latin or cyrylic
     * @param $string
     * @return bool
     */
    static function isFirstLetterLatin($string) {
        // Extract the first character of the string
        $firstChar = mb_substr($string, 0, 1);

        // Use regular expression to check if the first character is a Latin letter (A-Z or a-z)
        if (preg_match('/[A-Za-z]/', $firstChar)) {
            return true; // The first letter is a Latin letter
        } else {
            return false; // The first letter is not a Latin letter
        }
    }

    /**
     * Return list of airport on giver query string
     * @param $query
     * @return mixed
     */
    static public function get_airport($query)
    {
        // if first character is latin
        if (self::isFirstLetterLatin($query)){
            if (strlen($query) == 3){
                $cacheKey = 'airport.' . $query;
                $result = Cache::remember($cacheKey, now()->addHour(24),
                    function() use ($query) {
                        $result = Airport::select('iata','name_ru','name_en','country')
                            ->where('name_en','LIKE',$query.'%')
                            ->orwhere('iata','LIKE',$query.'%')
                            ->get()
                            ->toArray();
                        return $result;
                    });
            } else {
                $cacheKey = 'airport.' . $query;
                $result = Cache::remember($cacheKey, now()->addHour(24),
                    function() use ($query) {
                        $result = Airport::select('iata','name_ru','name_en','country')
                            ->where('name_en','LIKE',$query.'%')
                            ->orwhere('iata','LIKE',$query.'%')
                            ->orderBy('name_en', 'ASC')
                            ->get()
                            ->toArray();
                        return $result;
                    });
            }
            return $result;
        } else { // if first character is сyrillics

            $cacheKey = 'airport.' . $query;
            $result = Cache::remember($cacheKey, now()->addHour(24),
                function() use ($query) {
                    $result = Airport::select('iata','name_ru','name_en','country')
                        ->where('name_ru','LIKE',$query.'%')
                        ->orderBy('name_ru', 'ASC')
                        ->get()
                        ->toArray();
                    return $result;
                });
            return $result;
        }
    }
}
