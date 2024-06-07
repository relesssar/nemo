<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class NemoController extends Controller
{
    /**
     * Get airport list by query string
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function airport (Request $request) {
        $q= $request->query('q');

        $result = Airport::get_airport($q);

        return response()->json($result, 200);
    }

    /**
     * Download airports data from source
     * @param $url
     * @return bool|string
     */
    static public function get_airports ($url='https://raw.githubusercontent.com/NemoTravel/nemo.travel.geodata/master/airports.json')
    {
        // Initialize a cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Set the URL to send the request to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Allow redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set a timeout limit

        try {
            // Execute the cURL request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            // Get the HTTP response code
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Check for HTTP errors
            if ($httpStatusCode >= 400) {
                throw new Exception("HTTP request failed with status code $httpStatusCode.");
            }

            // Close cURL session
            curl_close($ch);

            return $response;
        } catch (Exception $e) {
            // If an exception is caught, close the cURL session and rethrow the exception
            curl_close($ch);
            throw $e;
        }
    }
}
