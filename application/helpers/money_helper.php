<?php

function formatMoney($amount, $flag = false, $currencyInitial = 'Rs. ')
{
	$amount = number_format(round($amount,2), 2);
	return ($flag) ? $currencyInitial.$amount : $amount;
}
