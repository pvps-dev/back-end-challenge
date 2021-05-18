<?php

namespace Demo\Controllers;

require('./app/helpers.php');
require('./app/Services/CurrencyService.php');

use Demo\Services\CurrencyService;
use Exception;


class ApiController {

    /**
     * @return string|null
     */
    public function exchange($from = '', $to = '')
    {
		// amount = total to convert
		// price = current price
		$params = $_GET;
		$amount = (float) @$params['amount'] ?: null;
		$price = (float) @$params['price'] ?: null;

		$currencyService = new CurrencyService($amount, $price);

        try {
            $response = $currencyService->execute($from, $to);

			return response()->json($response);
        } catch (Exception $ex) {
			return response()->httpCode(400)->json([
				'error' => $ex->getMessage(),
				'code'  => $ex->getCode(),
			]);
		}
    }
}
