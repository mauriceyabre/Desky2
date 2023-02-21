<?php
/**
 * CreateReceivedDocumentResponseTest
 *
 * PHP version 7.3
 *
 * @category Class
 * @package  FattureInCloud
 * @author   Fatture In Cloud API team
 * @link     https://fattureincloud.it
 */

/**
 * Fatture in Cloud API v2 - API Reference
 *
 * ## Request informations In every request description you will be able to find some additional informations about context, permissions and supported functionality:  | Parameter | Description | |-----------|-------------| | 👥 Context | Indicate the subject of the request. Can be `company`, `user` or `accountant`.  | | 🔒 Required scope | If present, indicates the required scope to fulfill the request. | | 🔍 Filtering | If present, indicates which fields support the filtering feature. | | ↕️ Sorting | If present, indicates which fields support the sorting feature. | | 📄 Paginated results | If present, indicate that the results are paginated. | | 🎩 Customized responses supported | If present, indicate that you can use `field` or `fieldset` to customize the response body. |  For example the request `GET /entities/{entityRole}` have tis informations: \\ 👥 Company context \\ 🔒 Required scope: `entity.clients:r` or `entity.suppliers:r` (depending on `entityRole`) \\ 🔍 Filtering: `id`, `name` \\ ↕️ Sorting: `id`, `name` \\ 📄 Paginated results \\ 🎩 Customized responses supported  Keep in mind that if you are making **company realted requests**, you will need to specify the company id in the requests: ``` GET /c/{company_id}/issued_documents ```
 *
 * The version of the OpenAPI document: 2.0.6
 * Contact: info@fattureincloud.it
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.3.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Please update the test case below to test the model.
 */

namespace FattureInCloud\Test\Model;

use PHPUnit\Framework\TestCase;
use \FattureInCloud\ObjectSerializer;

/**
 * CreateReceivedDocumentResponseTest Class Doc Comment
 *
 * @category    Class
 * @description Document created.
 * @package     FattureInCloud
 * @author   Fatture In Cloud API team
 * @link     https://fattureincloud.it
 */
class CreateReceivedDocumentResponseTest extends TestCase
{
    public $array = [];
    public $object;

    /**
     * Setup before running any test case
     */
    public static function setUpBeforeClass(): void
    {
    }

    /**
     * Setup before running each test case
     */
    public function setUp(): void
    {
        $json = '{
            "data": {
                "id": 12345,
                "type": "expense",
                "description": "Soggiorno di lavoro",
                "amortization": 1,
                "rc_center": "",
                "invoice_number": "",
                "is_marked": false,
                "is_detailed": false,
                "e_invoice": false,
                "entity": {
                  "id": 111,
                  "name": "Hotel Rubino Palace"
                },
                "date": "2021-08-15",
                "next_due_date": "2021-08-15",
                "currency": {
                  "id": "EUR",
                  "exchange_rate": "1.00000",
                  "symbol": "€"
                },
                "amount_net": 592,
                "amount_vat": 0,
                "amount_gross": 592,
                "amount_withholding_tax": 0,
                "amount_other_withholding_tax": 0,
                "tax_deductibility": 50,
                "vat_deductibility": 100,
                "payments_list": [
                  {
                    "amount": 592,
                    "due_date": "2021-08-15",
                    "paid_date": "2021-08-15",
                    "id": 777,
                    "payment_terms": {
                      "days": 0,
                      "type": "standard"
                    },
                    "status": "paid",
                    "payment_account": {
                      "id": 222,
                      "name": "Contanti",
                      "virtual": false
                    }
                  }
                ],
                "attachment_url": "spesa_ger5i783t45hu6ti.pdf"
            }
        }';

        $this->array = json_decode($json, true);

        $this->object = ObjectSerializer::deserialize($json, '\FattureInCloud\Model\CreateReceivedDocumentResponse');
    }

    /**
     * Clean up after running each test case
     */
    public function tearDown(): void
    {
    }

    /**
     * Clean up after running all test cases
     */
    public static function tearDownAfterClass(): void
    {
    }

    /**
     * Test "CreateReceivedDocumentResponse"
     */
    public function testCreateReceivedDocumentResponse()
    {
        foreach ($this->array as $key => $value) {
            Testcase::assertArrayHasKey($key, $this->object);
        }
    }

    /**
     * Test attribute "data"
     */
    public function testPropertyData()
    {
        foreach ($this->array['data'] as $key => $value) {
            Testcase::assertArrayHasKey($key, $this->object['data']);
        }
    }
}
