### Laravel開發工具箱之`SwaggerNotes`生成工具

> 註：該工具僅生成注釋內容，依賴`swagger-php`包才能生成`.yaml`接口文件

1. 本地開發環境安裝依賴包

     ```php
     composer require laravel-toolbox/swagger-notes --dev
     ```

     

2. 請在 `Controller` 類中對應的方法 `return` 前，加入如下代碼：

   ```php
   \Toolbox\Facades\SwaggerNotes::setRequest($request, null)
       ->setResponse($this->jsonRender($result))
       ->setComments(['affilliate_web', 'affilliate', 'member'], $request->rules($this->affiliateService))
       ->setSummary('我是當前接口的概述')
       ->setDescription('請在這裡修改當前接口的描述信息')
       ->setTitle('全局性接口文檔的標題，一次性設置後無需更改')
       ->setInfoDescription('同上，全局性接口文檔的描述')
       ->setVersion('同上，全局性接口文檔的版本')
       ->setOperationId(__FUNCTION__)
       ->setTags(__CLASS__)
       ->generate();
   ```

   

3. 默認生成路徑在`swagger/SwaggerNotes`目錄下，層級結構如下：

   ```shell
   swagger
   ├── SwaggerNotes              # 生成的注釋目錄
   │   ├── AffiliateTransfer     # 生成的接口目錄
   │   │   └── affiliateView.php # 生成的接口注釋文件
   │   └── swagger.php           # 生成的注釋頭部信息文件
   ├── swagger-constants.php
   ├── swagger-info.php
   ├── swagger.yaml				 
   └── swagger_doc.yaml          # 生成的接口文件
   ```

   

4. 附表

   | 方法                 | 釋義                                                         | 可选 | 備註                                                         |
   | -------------------- | ------------------------------------------------------------ | ---- | ------------------------------------------------------------ |
   | `setRequest`         | 設置請求體，可解析出當前接口的`method`、`parameter`、`url`、`pathInfo`等 | ✔️    | 第二個形參可选，支持被改造的Request請求體，如：`new GetAffiliateParameter($request)` |
   | `setResponse`        | 設置返回體，可直接給返回的數組結構                           |      |                                                              |
   | `setComments`        | 設置字段的備註、規則等                                       | ✔️    | 接口字段涉及的表集合；驗證規則，不設置則取`->rules()`        |
   | `setSummary`         | 設置當前接口文檔的`summary`概述信息                          | ✔️    | 不設置則取`->name()`                                         |
   | `setDescription`     | 設置當前接口文檔的`description`描述信息                      | ✔️    |                                                              |
   | `setOperationId`     | 設置當前接口文檔的操作`ID`標識                               |      | 建議`__FUNCTION__`                                           |
   | `setTags`            | 設置當前接口文檔的標籤分類                                   |      | 建議`__CLASS__`                                              |
   | `setTitle`           | 設置全局接口文檔的標題                                       | ✔️    |                                                              |
   | `setInfoDescription` | 設置全局接口文檔的描述信息                                   | ✔️    |                                                              |
   | `setVersion`         | 設置全局接口文檔的版本信息                                   | ✔️    |                                                              |

5. swagger-Notes生成效果

   ```php
   <?php
   
   use OpenApi\Annotations as OA;
   
   /**
    * @OA\Get(
    *     path="/api/v1/kkpartner/view",
    *     summary="查詢大聯盟會員資料",
    *     description="含是否填寫個人資料、賬戶資料",
    *     operationId="affiliateView",
    *     tags={"AffiliateTransfer"},
    *     @OA\Parameter(
    *         name="infoType",
    *         in="query",
    *         description="",
    *         required=true,
    *         @OA\Schema(
    *             type="string",
    *             default="ALL",
    *             enum={"ALL","PROFILE","ADDRINFO","ACCINFO","WEBINFO"},
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="memberUuid",
    *         in="query",
    *         description="會員編號",
    *         required=true,
    *         @OA\Schema(
    *             type="string",
    *             default="0a5a0390-7e74-40c5-a52d-e520e5bb92ab",
    *         )
    *     ),
    *     @OA\Response(response=200, description="接口返回OK",
    *         @OA\JsonContent(type="object",
    *             @OA\Property(property="metadata", type="object",
    *                 @OA\Property(property="status", type="string", default="0000"),
    *                 @OA\Property(property="desc", type="string", default="Success"),
    *             ),
    *             @OA\Property(property="data", type="object",
    *                 @OA\Property(property="profile", type="object",
    *                     @OA\Property(property="profileType", type="integer", default="2"),
    *                     @OA\Property(property="companyName", type="string", default="上海酷熊旅遊咨詢有限公司"),
    *                     @OA\Property(property="companyNameEn", type="string", default="Shanghai Cool Bear Travel Consulting Co., Ltd."),
    *                     @OA\Property(property="companyRepresentativeEn", type="string", default="Allen"),
    *                     @OA\Property(property="companyType", type="string", default="旅遊"),
    *                     @OA\Property(property="companyCountryCd", type="string", default="中國"),
    *                     @OA\Property(property="staffNum", type="string", default="100-500"),
    *                     @OA\Property(property="companyAddress", type="string", default="上海市長寧區長寧路999號4樓東側A023室"),
    *                     @OA\Property(property="nationality", type="string", default="中國"),
    *                     @OA\Property(property="identityType", type="string", default="2"),
    *                     @OA\Property(property="identityNumber", type="string", default="11111"),
    *                     @OA\Property(property="identityName", type="string", default="Michael"),
    *                     @OA\Property(property="identityNameEn", type="string", default="Michael"),
    *                     @OA\Property(property="residentialAddress", type="string", default="上海市普陀區梅林北路曹楊二村北梅園82號"),
    *                     @OA\Property(property="telArea", type="string", default="+86"),
    *                     @OA\Property(property="tel", type="string", default="18801963698"),
    *                     @OA\Property(property="lastName", type="string", default="Michael"),
    *                     @OA\Property(property="firstName", type="string", default="Ma"),
    *                 ),
    *                 @OA\Property(property="accInfo", type="object",
    *                     @OA\Property(property="bankName", type="string", default="招商銀行"),
    *                     @OA\Property(property="bankCode", type="string", default="2381"),
    *                     @OA\Property(property="bankBranchName", type="string", default="上海分行"),
    *                     @OA\Property(property="bankBranchCode", type="string", default="051"),
    *                     @OA\Property(property="accHoldName", type="string", default="Michael"),
    *                     @OA\Property(property="acc", type="string", default="1"),
    *                     @OA\Property(property="bankCountryCd", type="string", default="ZH"),
    *                     @OA\Property(property="bankSpecialCode", type="string", default="null"),
    *                     @OA\Property(property="swift", type="string", default="null"),
    *                 ),
    *                 @OA\Property(property="addrInfo", type="object",
    *                     @OA\Property(property="addressType", type="string", default="null"),
    *                     @OA\Property(property="addr1", type="string", default="null"),
    *                     @OA\Property(property="addr2", type="string", default="null"),
    *                     @OA\Property(property="countryCd", type="string", default="null"),
    *                     @OA\Property(property="state", type="string", default="null"),
    *                     @OA\Property(property="city", type="string", default="null"),
    *                     @OA\Property(property="area", type="string", default="null"),
    *                     @OA\Property(property="zipTxt", type="string", default="null"),
    *                 ),
    *                 @OA\Property(property="webInfo", type="array",
    *                     @OA\Items(type="object",
    *                         @OA\Property(property="memberUuid", type="string", default="0a5a0390-7e74-40c5-a52d-e520e5bb92ab"),
    *                         @OA\Property(property="cid", type="integer", default="11011"),
    *                         @OA\Property(property="siteName", type="string", default="haloooooo"),
    *                         @OA\Property(property="webUrl", type="string", default="https://kkpartners.sit.kkday.com/"),
    *                         @OA\Property(property="webCategory", type="string", default="0001"),
    *                         @OA\Property(property="estMonthlyVisitors", type="string", default="01"),
    *                         @OA\Property(property="estMonthlyBookings", type="string", default="01"),
    *                         @OA\Property(property="status", type="string", default="Approved"),
    *                         @OA\Property(property="rejectReason", type="string", default="null"),
    *                         @OA\Property(property="isBrand", type="string", default="Y"),
    *                         @OA\Property(property="brandName", type="string", default="kkpartners_one"),
    *                         @OA\Property(property="brandSetting", type="object",
    *                             @OA\Property(property="imageUrl", type="string", default="https://img.sit.kkday.com/image/get/s1.kkday.com/kkpartners_brand_11011/20210220020858_q1mBm/jpg"),
    *                             @OA\Property(property="brandUrl", type="string", default="https://kkpartners.kkday.com"),
    *                             @OA\Property(property="footerCopyright", type="string", default="null"),
    *                             @OA\Property(property="citys", type="object", default="null"),
    *                             @OA\Property(property="prods", type="object", default="null"),
    *                         ),
    *                     ),
    *                 ),
    *                 @OA\Property(property="profileFinished", type="boolean", default="true"),
    *                 @OA\Property(property="accInfoFinished", type="boolean", default="true"),
    *             ),
    *         )
    *     ),
    * )
    */
   ```

   

6. swagger-yaml生成效果

   ```yaml
   openapi: 3.0.0
   info:
     title: KKPartner大聯盟接口
     description: 接口搬遷
     version: 1.0.0
   servers:
     -
       url: 'http://affiliate.test'
   paths:
     /api/v1/kkpartner/view:
       get:
         tags:
           - AffiliateTransfer
         summary: 查詢大聯盟會員資料
         description: 含是否填寫個人資料、賬戶資料
         operationId: affiliateView
         parameters:
           -
             name: infoType
             in: query
             description: ''
             required: true
             schema:
               type: string
               default: ALL
               enum:
                 - ALL
                 - PROFILE
                 - ADDRINFO
                 - ACCINFO
                 - WEBINFO
           -
             name: memberUuid
             in: query
             description: 會員編號
             required: true
             schema:
               type: string
               default: 0a5a0390-7e74-40c5-a52d-e520e5bb92ab
         responses:
           '200':
             description: 接口返回OK
             content:
               application/json:
                 schema:
                   properties:
                     metadata: { properties: { status: { type: string, default: '0000' }, desc: { type: string, default: Success } }, type: object }
                     data: { properties: { profile: { properties: { profileType: { type: integer, default: '2' }, companyName: { type: string, default: 上海酷熊旅遊咨詢有限公司 }, companyNameEn: { type: string, default: 'Shanghai Cool Bear Travel Consulting Co., Ltd.' }, companyRepresentativeEn: { type: string, default: Allen }, companyType: { type: string, default: 旅遊 }, companyCountryCd: { type: string, default: 中國 }, staffNum: { type: string, default: 100-500 }, companyAddress: { type: string, default: 上海市長寧區長寧路999號4樓東側A023室 }, nationality: { type: string, default: 中國 }, identityType: { type: string, default: '2' }, identityNumber: { type: string, default: '11111' }, identityName: { type: string, default: Michael }, identityNameEn: { type: string, default: Michael }, residentialAddress: { type: string, default: 上海市普陀區梅林北路曹楊二村北梅園82號 }, telArea: { type: string, default: '+86' }, tel: { type: string, default: '18801963698' }, lastName: { type: string, default: Michael }, firstName: { type: string, default: Ma } }, type: object }, accInfo: { properties: { bankName: { type: string, default: 招商銀行 }, bankCode: { type: string, default: '2381' }, bankBranchName: { type: string, default: 上海分行 }, bankBranchCode: { type: string, default: '051' }, accHoldName: { type: string, default: Michael }, acc: { type: string, default: '1' }, bankCountryCd: { type: string, default: ZH }, bankSpecialCode: { type: string, default: 'null' }, swift: { type: string, default: 'null' } }, type: object }, addrInfo: { properties: { addressType: { type: string, default: 'null' }, addr1: { type: string, default: 'null' }, addr2: { type: string, default: 'null' }, countryCd: { type: string, default: 'null' }, state: { type: string, default: 'null' }, city: { type: string, default: 'null' }, area: { type: string, default: 'null' }, zipTxt: { type: string, default: 'null' } }, type: object }, webInfo: { type: array, items: { properties: { memberUuid: { type: string, default: 0a5a0390-7e74-40c5-a52d-e520e5bb92ab }, cid: { type: integer, default: '11011' }, siteName: { type: string, default: haloooooo }, webUrl: { type: string, default: 'https://kkpartners.sit.kkday.com/' }, webCategory: { type: string, default: '0001' }, estMonthlyVisitors: { type: string, default: '01' }, estMonthlyBookings: { type: string, default: '01' }, status: { type: string, default: Approved }, rejectReason: { type: string, default: 'null' }, isBrand: { type: string, default: 'Y' }, brandName: { type: string, default: kkpartners_one }, brandSetting: { properties: { imageUrl: { type: string, default: 'https://img.sit.kkday.com/image/get/s1.kkday.com/kkpartners_brand_11011/20210220020858_q1mBm/jpg' }, brandUrl: { type: string, default: 'https://kkpartners.kkday.com' }, footerCopyright: { type: string, default: 'null' }, citys: { type: object, default: 'null' }, prods: { type: object, default: 'null' } }, type: object } }, type: object } }, profileFinished: { type: boolean, default: 'true' }, accInfoFinished: { type: boolean, default: 'true' } }, type: object }
                   type: object
   
   ```

7. swagger-api接口文檔效果

   PhpStorm推薦使用[Swagger](https://plugins.jetbrains.com/plugin/8347-swagger)预览
   ![image-20221204125335329](https://cdn.jsdelivr.net/gh/mikeah2011/oss@main/uPic/image-20221204125335329.png)

   WebEditor推薦使用[Swagger Editor](https://editor-next.swagger.io)预览
   ![image-20221204125858109](https://cdn.jsdelivr.net/gh/mikeah2011/oss@main/uPic/image-20221204125858109.png)