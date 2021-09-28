<?php

namespace DocDoc\RgsApiClient;

use DocDoc\RgsApiClient\Exception\BaseRgsException;
use DocDoc\RgsApiClient\Exception\InternalErrorRgsException;
use Psr\Http\Message\ResponseInterface;
use DocDoc\RgsApiClient\Exception\BadRequestRgsException;

class StatisticsRgsClient extends AbstractRgsClient
{
    /**
     * Получить статистику по продукту мониторинга из РГС
     *
     * @param int $productId
     *
     * @return ResponseInterface
     * @throws InternalErrorRgsException
     * @throws BaseRgsException
     */
    public function getStatisticsByProduct(int $productId): ResponseInterface
    {
        $url = '/api/v1/statistics/' . $productId;
        $request = $this->buildRequest('GET', $url, '');

        return $this->send($request);
    }

	/**
	 * Получить статистику пациентов по продукту мониторинга из РГС и датам
	 *
	 * @param int $productId
	 * @param string|null $period
	 * @param string|null $fromDate
	 * @param string|null $toDate
	 *
	 * @return ResponseInterface
	 * @throws BaseRgsException
	 * @throws BadRequestRgsException
	 * @throws InternalErrorRgsException
	 */
	public function getPatientsStatisticsByProductAndDates(
		int     $productId,
		?string $period,
		?string $fromDate,
		?string $toDate
	): ResponseInterface
	{
		$url = '/api/v1/statistics/' . $productId . '/patients';

		$query = \array_filter([
			'period' => $period,
			'fromDate' => $fromDate,
			'toDate' => $toDate,
		]);

		$url .= '?' . \http_build_query($query);

		$request = $this->buildRequest('GET', $url, '');

		return $this->send($request);
	}
}