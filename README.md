### Laravel 開發工具箱之 `SwaggerNotes` 生成工具

> 註：該工具僅生成注釋內容，依賴 `swagger-php` 包才能生成 `.yaml` 接口文件

1. 本地開發環境安裝依賴包

     ```php
    composer require laravel-toolbox/swagger-notes --dev
    ```

2. 請在 `Controller` 類中對應的方法 `return` 前，加入如下代碼：

   ```php
   \Toolbox\Facades\SwaggerNotes::setRequest($request)
            ->setResponse($this->jsonRender(TransformHelper::camelSnakeCase($result, 'camel_case')))
            ->setComments(['affilliate_web', 'affilliate', 'member'], $request->rules($this->affiliateService), $this->optionComments)
            ->setApiInfo([
                'summary' => '查詢大聯盟會員資料',
                'description' => '含是否填寫個人資料、賬戶資料',
                'operation_id' => __FUNCTION__,
                'tags' => __CLASS__,
            ])
            ->generate();
   ```

3. 新增可支持擴展字段的備註信息，在調用類中新增&修改如下代碼即可：
   ```php
   public $optionComments = [
        'metadata' => '元數據',
        'data' => '數據',
        'desc' => '狀態描述',
        'infoType' => '資料類型：ALL-全部;PROFILE-個人資料;ADDRINFO-通訊資料;ACCINFO-帳戶資料;WEBINFO-網站資料;',
        'profile' => '個人資料',
        'addrInfo' => '通訊資料',
        'accInfo' => '帳戶資料',
        'webInfo' => '網站資料',
        'brandName' => '白牌名稱',
        'brandSetting' => '白牌設置',
        'imageUrl' => '圖片連結',
        'brandUrl' => '白牌連結',
        'footerCopyright' => '底部授權信息',
        'citys' => '所含城市',
        'prods' => '所含省份',
        'profileFinished' => '個人資料是否完成的標識',
        'accInfoFinished' => '賬戶資料是否完成的標識',
        'isApproved' => '聯盟審核是否通過的標識',
        'isActive' => '聯盟賬戶是否活躍的標識',
    ];
   ```   

4. 默認生成路徑在`swagger/SwaggerNotes`目錄下，層級結構如下：

   ```shell
   swagger
   ├── Swagger                        # 生成的注釋目錄
   │     ├── Affiliate                # 生成的接口目錄
   │     │      └── affiliateView.php # 生成的接口注釋文件
   │     └── swagger.php              # 生成的注釋頭部信息文件
   ├── swagger-constants.php
   ├── swagger-info.php
   ├── swagger.yaml
   └── swagger_doc.yaml               # 生成的接口文件
   ```

5. 附表

   | 方法          | 釋義                                                         | 可选 | 備註                                                         |
   | ------------- | ------------------------------------------------------------ | ---- | ------------------------------------------------------------ |
   | `setRequest`  | 設置請求體，可解析出當前接口的`method`、`parameter`、`url`、`pathInfo`等 | ✔️    | 第二個形參可选，支持被改造的Request請求體，如：`new GetAffiliateParameter($request)` |
   | `setResponse` | 設置返回體，可直接給返回的數組結構                           |      |                                                              |
   | `setComments` | 設置字段的備註、規則等                                       | ✔️    | 接口字段涉及的表集合；驗證規則，不設置則取`->rules()`        |
   | `setApiInfo`  | 設置 API_INFO 的相關參數                                     |      |                                                              |
   | summary       | 設置當前接口文檔的`summary`概述信息                          | ✔️    | 不設置則取`->name()`                                         |
   | description   | 設置當前接口文檔的`description`描述信息                      | ✔️    |                                                              |
   | operation_id  | 設置當前接口文檔的操作`ID`標識                               |      | 建議`__FUNCTION__`                                           |
   | tags          | 設置當前接口文檔的標籤分類                                   |      | 建議`__CLASS__`                                              |
   | `generate`    | 生成文檔                                                     |      | 僅本地、測試環境或開啟 debug 模式時生效                      |
