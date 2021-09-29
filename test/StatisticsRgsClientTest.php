<?php

namespace DocDoc\RgsApiClient\test;

use DocDoc\RgsApiClient\CategoryRgsClient;
use DocDoc\RgsApiClient\Dto\RgsApiParamsInterface;
use DocDoc\RgsApiClient\Exception\BaseRgsException;
use DocDoc\RgsApiClient\Exception\InternalErrorRgsException;
use DocDoc\RgsApiClient\StatisticsRgsClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @coversDefaultClass \DocDoc\RgsApiClient\StatisticsRgsClient
 */
class StatisticsRgsClientTest extends TestCase
{
    /** @var StatisticsRgsClient */
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $rgsApiParams = $this->createMock(RgsApiParamsInterface::class);
        // Mock server Apiary
        $rgsApiParams->method('getHost')->willReturn('https://private-63631f-chronicmonitor.apiary-mock.com/');
        $rgsApiParams->method('getPartnerId')->willReturn(241);

        $this->client = new StatisticsRgsClient(
            new Client(),
            $rgsApiParams,
            $this->createMock(LoggerInterface::class)
        );
    }

    /**
     * @dataProvider getStatisticsByProductDataProvider
     *
     * @param $extendedJson
     * @covers ::getPatient
     *
     * @throws InternalErrorRgsException
     * @throws BaseRgsException
     */
    public function testGetStatisticsByProduct(string $extendedJson): void
    {
        $expectedResponseDataObject = json_decode($extendedJson, true);
        $response = $this->client->getStatisticsByProduct(100);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_decode($response->getBody()->getContents(), true), $expectedResponseDataObject);
    }

    public function getStatisticsByProductDataProvider(): array
    {
        return [ [
            '{
                "totalPatientsCount": 22,
                "activePatientsCount": 16,
                "monitoringDays": [
                    "пн",
                    "вт",
                    "ср",
                    "чт",
                    "пт",
                    "сб",
                    "вс"
                ],
                "patientsWithAbnormalCount": 1,
                "patientsWithMetricsCount": 2,
                "rangesTable": {
                    "rows": {
                        "1": {
                            "metricId": 1,
                            "metricName": "SYS (верхнее)",
                            "measurements": "мм.рт.ст.",
                            "patientCountByRanges": {
                                "1": {
                                    "ranges": "120-",
                                    "patients": [
                                        270
                                    ],
                                    "patientsCount": 1
                                },
                                "2": {
                                    "ranges": "120-139",
                                    "patients": [],
                                    "patientsCount": 0
                                },
                                "3": {
                                    "ranges": "140-159",
                                    "patients": [],
                                    "patientsCount": 0
                                },
                                "4": {
                                    "ranges": "160-179",
                                    "patients": [],
                                    "patientsCount": 0
                                },
                                "5": {
                                    "ranges": "180-199",
                                    "patients": [],
                                    "patientsCount": 0
                                },
                                "6": {
                                    "ranges": "-200",
                                    "patients": [],
                                    "patientsCount": 0
                                }
                            }
                        },
                        "2": {
                            "metricId": 2,
                            "metricName": "DIA (нижнее)",
                            "measurements": "мм.рт.ст.",
                            "patientCountByRanges": {
                                "7": {
                                    "ranges": "90-",
                                    "patients": [
                                        270
                                    ],
                                    "patientsCount": 1
                                },
                                "8": {
                                    "ranges": "-90",
                                    "patients": [],
                                    "patientsCount": 0
                                }
                            }
                        },
                        "3": {
                            "metricId": 3,
                            "metricName": "Пульс",
                            "measurements": "уд/мин",
                            "patientCountByRanges": {
                                "9": {
                                    "ranges": "50-",
                                    "patients": [],
                                    "patientsCount": 0
                                },
                                "10": {
                                    "ranges": "50-100",
                                    "patients": [
                                        270
                                    ],
                                    "patientsCount": 1
                                },
                                "11": {
                                    "ranges": "-100",
                                    "patients": [],
                                    "patientsCount": 0
                                }
                            }
                        }
                    },
                    "patientWithMetricsCount": 3
                }
            }'
        ]];
    }

	/**
	 * @dataProvider getPatientsStatisticsByProductAndDatesProvider
	 *
	 * @param $extendedJson
	 * @covers ::getPatient
	 *
	 * @throws InternalErrorRgsException
	 * @throws BaseRgsException
	 */
	public function testGetPatientsStatisticsByProductAndDates(string $extendedJson): void
	{
		$expectedResponseDataObject = json_decode($extendedJson, true);
		$response = $this->client->getPatientsStatisticsByProductAndDates(100, 'all', null, null);

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals(json_decode($response->getBody()->getContents(), true), $expectedResponseDataObject);
	}

	public function getPatientsStatisticsByProductAndDatesProvider(): array
	{
		return [[
			'{
				"allPatients":4,
				"countAnswerCallRobot":0,
				"countDataTransferSberHealth":98
			}',
		]];
	}
}