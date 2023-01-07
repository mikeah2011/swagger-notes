<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class GenerateTest extends TestCase
{
    public function testGenerate()
    {
        $request = [
            'memberUuid' => '0a5a0390-7e74-40c5-a52d-e520e5bb92ab',
            'infoType' => 'ALL',
        ];
        $response = [
            "metadata" => [
                "status" => "0000",
                "desc" => "Success",
            ],
            "data" => [
                "profile" => [
                    "profileType" => 2,
                    "companyName" => "666",
                    "companyNameEn" => "666",
                    "companyRepresentativeEn" => "666",
                    "companyType" => "2",
                    "companyCountryCd" => "AU",
                    "staffNum" => "1000",
                    "companyAddress" => "123",
                    "nationality" => "中國",
                    "identityType" => 1,
                    "identityNumber" => "E73950085",
                    "identityName" => "Michael",
                    "identityNameEn" => "Michael",
                    "residentialAddress" => "上海市普陀區梅林北路曹楊二村北梅園82號",
                    "telArea" => "+86",
                    "tel" => "18801963698",
                    "lastName" => "Michael",
                    "firstName" => "Ma",
                ],
                "accInfo" => [
                    "bankName" => "招商銀行",
                    "bankCode" => "2381",
                    "bankBranchName" => "上海分行",
                    "bankBranchCode" => "051",
                    "accHoldName" => "Michael",
                    "acc" => "1",
                    "bankCountryCd" => "ZH",
                    "bankSpecialCode" => "",
                    "swift" => "",
                ],
                "addrInfo" => [
                    "addressType" => null,
                    "addr1" => null,
                    "addr2" => null,
                    "countryCd" => null,
                    "state" => null,
                    "city" => null,
                    "area" => null,
                    "zipTxt" => null,
                ],
                "webInfo" => [
                    [
                        "memberUuid" => "0a5a0390-7e74-40c5-a52d-e520e5bb92ab",
                        "cid" => 11011,
                        "siteName" => "haloooooo",
                        "webUrl" => "https://kkpartners.sit.kkday.com/",
                        "webCategory" => "0001",
                        "estMonthlyVisitors" => "01",
                        "estMonthlyBookings" => "01",
                        "status" => "Approved",
                        "rejectReason" => null,
                        "isBrand" => "Y",
                        "brandName" => "kkpartners_one",
                        "brandSetting" => [
                            "imageUrl" => "https://img.sit.kkday.com/image/get/s1.kkday.com/kkpartners_brand_11011/20210220020858_q1mBm/jpg",
                            "brandUrl" => "https://kkpartners.kkday.com",
                            "footerCopyright" => "",
                            "citys" => [],
                            "prods" => [],
                        ],
                    ],
                    [
                        "memberUuid" => "0a5a0390-7e74-40c5-a52d-e520e5bb92ab",
                        "cid" => 11002,
                        "siteName" => "lanlanlanhi",
                        "webUrl" => "https://kkpartners.sit.kkday.com/",
                        "webCategory" => "0013",
                        "estMonthlyVisitors" => "01",
                        "estMonthlyBookings" => null,
                        "status" => "Approved",
                        "rejectReason" => null,
                        "isBrand" => "Y",
                        "brandName" => "lanlanlann",
                        "brandSetting" => [
                            "imageUrl" => "https://kkpartners.sit.kkday.com/img/logo_white.198fa062.svg",
                            "brandUrl" => "https://kkpartners.sit.kkday.com/",
                            "footerCopyright" => "",
                            "citys" => [],
                            "prods" => [],
                        ],
                    ],
                    [
                        "memberUuid" => "0a5a0390-7e74-40c5-a52d-e520e5bb92ab",
                        "cid" => 11035,
                        "siteName" => "ryan",
                        "webUrl" => "https://kkpartners.sit.kkday.com/",
                        "webCategory" => "0001",
                        "estMonthlyVisitors" => "01",
                        "estMonthlyBookings" => null,
                        "status" => "Approved",
                        "rejectReason" => null,
                        "isBrand" => "N",
                        "brandName" => null,
                        "brandSetting" => null,
                    ],
                    [
                        "memberUuid" => "0a5a0390-7e74-40c5-a52d-e520e5bb92ab",
                        "cid" => 11013,
                        "siteName" => "haloooooo0220",
                        "webUrl" => "https://kkpartners.sit.kkday.com/",
                        "webCategory" => "0001",
                        "estMonthlyVisitors" => "02",
                        "estMonthlyBookings" => null,
                        "status" => "Approved",
                        "rejectReason" => null,
                        "isBrand" => "Y",
                        "brandName" => "kkpartners_onee",
                        "brandSetting" => [
                            "imageUrl" => "https://img.sit.kkday.com/image/get/s1.kkday.com/kkpartners_brand_11013/20220217103431_ZY9RN/png",
                            "brandUrl" => "https://kkpartners1.kkday.com",
                            "footerCopyright" => "",
                            "citys" => [],
                            "prods" => [],
                        ],
                    ],
                ],
                "profileFinished" => true,
                "accInfoFinished" => true,
            ],
        ];
        $rules = [
            'memberUuid' => ['required', 'string'],
            'infoType' => ['required', 'in:ALL,PROFILE,ADDRINFO,ACCINFO,WEBINFO']
        ];
        $tables = ['affilliate_web', 'affilliate', 'member'];
        $apiInfo = [
            'url' => 'https://api-kkpartners.sit.kkday.com/api/v1/kkday/affiliate/profile',
            'method' => 'GET',
            'summary' => '查詢大聯盟會員資料',
            'description' => '含是否填寫個人資料、賬戶資料',
            'operation_id' => 'affiliateView',
            'tags' => 'App\Http\Controllers\Api\v1\kkpartner\AffiliateTransferController',
        ];
        \Toolbox\Facades\SwaggerNotes::setRequest($request)
            ->setResponse($response)
            ->setComments($tables, $rules)
            ->setApiInfo($apiInfo)
            ->generate();
    }
}