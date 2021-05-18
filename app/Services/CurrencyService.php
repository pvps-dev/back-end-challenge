<?php

namespace Demo\Services;

use \Exception;

class CurrencyService {
	private $amount;
	private $price;
	private $from;
	private $to;

	private $currency_list = [
		'USD', 'EUR', 'BRL'
	];

	private $symbols = [
		'USD' => '$',
    	'EUR' => '€',
    	'BRL' => 'R$'
	];

	function __construct($amount, $price) {
		$this->amount = $amount;
		$this->price = $price;
	}

	/**
	 * Retorna o simbolo da moeda
	 *
	 * @param String $currency
	 * @return String
	 */
	private function getCurrencySymbol($currency) {
		return $this->symbols[$currency];
	}

	/**
	 * Valida formato da moeda
	 *
	 */
	private function validateCurrency()
	{
		// Se alguma das moedas nao forem USD, BRL, EUR retorna erro
		if (!in_array($this->from, $this->currency_list) || !in_array($this->to, $this->currency_list)) {
			throw new Exception('Moeda invalida!', 400);
		}

		// Se nenhuma das moedas forem BRL, retorna erro
		if ($this->from !== 'BRL' && $this->to !== 'BRL') {
			throw new Exception('Uma das moedas tem que ser BRL!', 400);
		}
	}

	/**
	 * Valida se os valores de 'amount' e 'price' sao validos
	 *
	 */
	private function validateValues()
	{
		// Verifica se tem os valores
		if (empty($this->amount) || empty($this->price)) {
			throw new Exception('Valor total e/ou preco invalido!', 400);
		}

		// Verifica se os valores sao numericos
		if (!is_numeric($this->amount) || !is_numeric($this->price)) {
			throw new Exception('Valor total e/ou preco precisa ser numerico!', 400);
		}

		// Verifica se os valores sao numeros positivos
		if ($this->amount < 0 || $this->price < 0) {
			throw new Exception('Valor total e/ou preco precisa ser um valor positivo!', 400);
		}
	}


	/**
	 * Executa o servico, verifica se todos os atributos necessarios para a conversao sao validos e entao
	 * realiza a conversao
	 *
	 */
	public function execute($from, $to)
	{
		$this->from = $from;
		$this->to = $to;

        try {
            $this->validateCurrency();
            $this->validateValues();
			$convertedValue = $this->convert();

			return [
				'convertedValue' => $convertedValue,
				'symbol' => $this->getCurrencySymbol($to)
			];
        } catch (Exception $ex) {
			throw $ex;
		}
	}


	/**
	 * Converte a moeda baseado no valor de/para
	 *
	 */
	public function convert()
	{
		// Se as moedas foram iguais nao faz nada, retorna o preco da cotacao
		if ($this->from == $this->to) {
			return $this->price;
		}

		// Se a moeda 'from' for BRL entao dividimos se nao, multiplicamos
		return $this->from === 'BRL'
			? round($this->amount / $this->price, 2)
			: round($this->amount * $this->price, 2);
	}
}
