<?php

declare(strict_types=1);

namespace Toolbox;

/**
 * @description SwaggerPHP注釋的基本信息配置「動態設置」
 */
class SwaggerPHPInfo
{
    use DBTrait;

    /**
     * @var object 請求體，解析出 method pathInfo validated 等
     */
    protected $request;
    /**
     * @var object 其他請求體，解析出 rules以及validate 等
     */
    protected $otherRequest = NULL;
    /**
     * @var array  请求参数数据
     */
    protected $validated = [];
    /**
     * @var array   返回数据结构
     */
    protected $response = [];
    /**
     * @var array   入參字段規則
     */
    protected $columnsRules = [];
    /**
     * @var array   出入參備註信息
     */
    protected $columnsComments = [];
    /**
     * @var string  SwaggerPHP注釋的接口概述
     */
    protected $summary = '';
    /**
     * @var string  SwaggerPHP注釋的接口描述
     */
    protected $description = '';
    /**
     * @var string  SwaggerPHP注釋的信息頭描述
     */
    protected $infoDescription = '- kibana log_type:<br>- kkday-common-svc: request log<br> - kkday-common-svc_db: 查看 db 相關 log<br/>';
    /**
     * @var string  SwaggerPHP注釋的信息头标题
     */
    protected $title = '通用服務';
    /**
     * @var string  SwaggerPHP注釋的信息頭版本
     */
    protected $version = '1.0.0';
    /**
     * @var string API 请求地址
     */
    protected $url = '';
    /**
     * @var string API 請求方式
     */
    protected $method = 'GET';
    /**
     * @var string API 請求路徑
     */
    protected $pathInfo = '';
    /**
     * @var string  SwaggerPHP注釋的操作ID
     */
    protected $operationId = '';
    /**
     * @var string  SwaggerPHP注釋的標籤
     */
    protected $tags = 'API';

    /**
     * @description 設置請求體結構
     *
     * @param      $request
     * @param null $otherRequest
     *
     * @return $this
     */
    public function setRequest($request, $otherRequest = NULL): SwaggerPHPInfo
    {
        $this->request = $request;
        $this->otherRequest = $otherRequest;

        return $this;
    }

    /**
     * @description 設置返回體結構
     *
     * @param $response
     *
     * @return $this
     */
    public function setResponse($response): SwaggerPHPInfo
    {
        is_object($response) && $response = $response->original;
        $this->response = $response;

        return $this;
    }

    /**
     * @description 獲取出入參字段的備註信息「含是否必填」
     *
     * @param array $tables 指定表
     * @param array $rules  字段規則
     *
     * @return SwaggerPHPInfo
     */
    public function setComments(array $tables = [], array $rules = []): SwaggerPHPInfo
    {
        if ($this->otherRequest) {
            method_exists($this->otherRequest, 'validated') && $this->validated += $this->otherRequest->validated();
            method_exists($this->otherRequest, 'toArray') && $this->validated += $this->otherRequest->toArray();
            !$rules && method_exists($this->otherRequest, 'rules') && $rules = $this->otherRequest->rules();
        } else {
            method_exists($this->request, 'validated') && $this->validated += $this->request->validated();
        }

        empty($this->validated) && is_array($this->request) && $this->validated = $this->request;
        $this->columnsRules = $rules ?: $this->request->rules();
        $params = camel_snake($this->validated + $this->response, 'snake');
        $columns = [];
        array_walk_recursive($params, static function ($value, $key) use (&$columns) {
            in_array($key, $columns, true) && $columns[] = $key;
        });
        $this->columnsComments = camel_snake(array_column($this->getColumnsInfo($tables, $columns), NULL, 'name'), 'camel');

        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的接口概述
     *
     * @param string $summary
     *
     * @return $this
     */
    public function setSummary(string $summary): SwaggerPHPInfo
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的接口描述
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): SwaggerPHPInfo
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的信息头标题
     *
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title): SwaggerPHPInfo
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的信息頭版本
     *
     * @param $version
     *
     * @return SwaggerPHPInfo
     */
    public function setVersion($version): SwaggerPHPInfo
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的信息頭描述
     *
     * @param string $infoDescription
     *
     * @return $this
     */
    public function setInfoDescription(string $infoDescription): SwaggerPHPInfo
    {
        $this->infoDescription = $infoDescription;

        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的操作ID
     *
     * @param string $function
     *
     * @return $this
     */
    public function setOperationId(string $function): SwaggerPHPInfo
    {
        $this->operationId = $function;

        return $this;
    }

    /**
     * @description 設置SwaggerPHP注釋的標籤
     *
     * @param string $class
     *
     * @return $this
     */
    public function setTags(string $class): SwaggerPHPInfo
    {
        $this->tags = get_class_name($class);

        return $this;
    }

    /**
     * @description 設置 API 的相關信息
     *
     * @param array $apiInfo
     *
     * @return $this
     */
    public function setApiInfo(array $apiInfo = []): SwaggerPHPInfo
    {
        if (!empty($apiInfo['url'])) {
            $parseUrl = parse_url($apiInfo['url']);
            $this->url = $parseUrl['scheme'] . '://' . $parseUrl['host'];
            $this->pathInfo = $parseUrl['path'];
        }

        return $this->setSummary($apiInfo['summary'] ?? $this->summary)
            ->setDescription($apiInfo['description'] ?? $this->description)
            ->setOperationId($apiInfo['operation_id'] ?? $this->operationId)
            ->setTags($apiInfo['tags'] ?? $this->tags)
            ->setMethod($info['method'] ?? $this->method)
            ->setPathInfo($info['path_info'] ?? $this->pathInfo);
    }

    /**
     * @description 設置 INFO 的相關信息
     *
     * @param array $info
     *
     * @return $this
     */
    public function setInfo(array $info = []): SwaggerPHPInfo
    {
        return $this->setInfoDescription($info['info_description'] ?? $this->infoDescription)
            ->setTitle($info['title'] ?? $this->title)
            ->setVersion($info['version'] ?? $this->version)
            ->setUrl($info['url'] ?? $this->url);
    }

    /**
     * @description 设置 API 请求地址
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url = ''): SwaggerPHPInfo
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @description 设置 API 請求方式
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method = 'GET'): SwaggerPHPInfo
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @description 设置 API 請求路徑
     *
     * @param string $pathInfo
     *
     * @return $this
     */
    public function setPathInfo(string $pathInfo = ''): SwaggerPHPInfo
    {
        $this->pathInfo = $pathInfo;

        return $this;
    }

    public function __construct()
    {
        // 如果沒有開啟 debug 模式或者不在本地和 site/sit/develop/dev 測試環境，就直接出去好了
        if (
            !env('APP_DEBUG', false)
            || !in_array(env('APP_ENV', ''), ['testing', 'test', 'develop', 'dev', 'site', 'sit'])
        ) {
            return false;
        }
    }
}