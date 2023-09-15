<?php

declare(strict_types=1);

namespace Toolbox;

/**
 * Trait SwaggerNotesTrait
 */
trait SwaggerNotesTrait
{
    protected $tables = [];

    protected $summary = 'TODO 我是一個接口概述，請修改';

    protected $description = 'TODO 我是一個接口描述，請修改';

    protected $operation_id = 'TODO 我是一個接口 ID，請修改';

    protected $tags = 'TODO 我是一個接口分類，請修改';

    /**
     * @var array  出入參備註信息「不存在於 DB 欄位的字段」
     */
    protected $optionComments = [
        // 入參欄位 START
        'page' => '第 N 頁，從 1 開始',
        'per_page' => '每頁 M 數量，預設 10',
        'locale' => '語係類型：en-英語;ja-日語;ko-韓語;th-泰語;vi-越南語;zh-tw-繁中;zh-cn-簡中;zh-hk-繁中',
        // 入參欄位 ENDED

        // 出參欄位 START
        'metadata' => '元數據',
        'status' => '狀態代碼',
        'desc' => '狀態描述',
        'data' => '數據',

        // 分頁信息
        'pagination' => '分頁信息',
        'total' => '總條目數',
        'total_page' => '總頁數',
        'current_page' => '當前頁數',

        // 列表信息
        'list' => '列表信息',
        'user_crt_dt' => '用戶當地下單時間',
        'country_name' => '產品所處國家',
        'contact_country_name' => '聯絡人所處國家',

        'rows_affected' => '受影響行數',
        'modify_user' => '異動人員 uuid',
        'modify_date' => '異動時間',
        'create_user' => '創建人員 uuid',
        'create_date' => '創建時間',
        // 出參欄位 ENDED
    ];

    /**
     * @param $request
     * @param $response
     *
     * @return void
     */
    public function generateSwaggerDoc($request, $response): void
    {
        Facades\SwaggerNotes::setRequest($request)
                            ->setResponse($response)
                            ->setComments($this->tables, $request->rules(), $this->optionComments)
                            ->setApiInfo([
                                'summary' => $this->summary,
                                'description' => $this->description,
                                'operation_id' => $this->operation_id,
                                'tags' => $this->tags,
                            ])
                            ->setInfo([
                                'title' => 'Affiliate Service API',
                                'version' => 'v1 - v2',
                                'info_description' => 'KKDay KKPartners 內部專用',
                                'url' => 'https://svc-affiliate-35.sit.kkday.com',
                            ])
                            ->generate();
    }
}
