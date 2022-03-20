<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ApiCurrencyRequest;

class CurrencyController extends Controller
{

    public function currencyConverter(ApiCurrencyRequest $request)
    {
        //Request validator

        //Check valid key

        //Check in Currencies table if the currency exist and is not older then 24 hours
        try {
            $last_24_hors = Carbon::now("UTC")->subHours(24);
            $source_currency = Currency::select("rates", "date_of_change")
                ->where([
                    ["base", "=", strtoupper($request->source_currency)],
                    ["date_of_change", ">", $last_24_hors]
                ])
                ->first();
        } catch (\Exception $e) {
            $source_currency = false;
        }

        //If not exist in DB Ping the Fixer Api and get the response for wanted currency. Store or Update existing one in DB
        if (!$source_currency) {

            //NOTE:: I chose to solve this problem in this way because the FREE KEY allow us to get rates only for EUR and USD. With this solution, we can generate rates for each currency

            $key = env("FIXER_KEY");
            $response = Http::get('http://data.fixer.io/api/latest?access_key=' . $key);

            if ($response->successful()) {

                $arr = json_decode($response->body(), true);

                if (isset($arr["rates"]) && isset($arr["base"]) && $arr["base"] == "EUR") {
                    //Calculate the rate based on EUR
                    $rate = 1 / $arr["rates"][$request->source_currency];
                    //Change right currency name
                    $arr["base"] = strtoupper($request->source_currency);

                    if ($rate != 1) {
                        foreach ($arr["rates"] as $key => $value) {
                            $arr["rates"][$key] = $value * $rate;
                        }
                    }
                    $insert = Currency::updateOrCreate(
                        [
                            'base' => $arr["base"]
                        ],
                        ['rates' => json_encode($arr["rates"]), 'date_of_change' => Carbon::parse($arr["timestamp"])->timezone("UTC")]
                    );

                    //Generate response
                    $response = $this->generateSuccessResponse($request, $arr);

                } else {
                    //return error
                    return response()->json($this->generateErrorResponse("Something gets wrong please try again"), 404);

                }

            } else {

                return response()->json($this->generateErrorResponse("Something gets wrong please try again"), 404);

            }


        }
        else {

            $arr = $source_currency->toArray();
            $arr["rates"] = json_decode($arr["rates"], true);
            $response = $this->generateSuccessResponse($request, $arr);
        }
        //Return the response to the user
        return response()->json($response, 200);

    }

    public function generateSuccessResponse($request, $arr = [], $status = 200)
    {
        $response = [
            'status' => $status,
            'value' => $request->value,
            'source_currency_from' => $request->source_currency,
            'target_currency_to' => $request->target_currency,
            'result' => round($request->value * $arr['rates'][$request->target_currency],3),
        ];

        return $response;

    }

    public function generateErrorResponse($message, $status = 404)
    {
        $response = [
            'status' => $status,
            'message' => $message
        ];

        return $response;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        //
    }
}
