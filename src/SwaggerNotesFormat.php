<?php

declare(strict_types=1);

namespace Toolbox;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @description SwaggerPHP注釋的格式化處理類
 */
class SwaggerNotesFormat extends SwaggerPHPInfo
{
    /**
     * @description 「生成|刷新」SwaggerPHP注釋Info信息
     *
     *
     * @return string
     */
    protected function formatInfo(): string
    {
        method_exists($this->request, 'getSchemeAndHttpHost') && $this->url = $this->request->getSchemeAndHttpHost();

        return <<<EOF
<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="$this->version",
 *     title="$this->title",
 *     description="$this->infoDescription",
 * )
 * @OA\Server(url="$this->url")
 */
EOF;
    }

    /**
     * @description 構建SwaggerPHP注释内容
     */
    protected function formatNotes(): string
    {
        method_exists($this->request, 'method') && $this->method = $this->request->method();
        $method = Str::title($this->method);
        method_exists($this->request, 'getPathInfo') && $this->pathInfo = $this->request->getPathInfo();
        empty($this->summary) && method_exists($this->request, 'route') && $this->summary = $this->request->route()->getAction('as', '');

        return <<<EOF
<?php

use OpenApi\Annotations as OA;

/**
 * @OA\\$method(
 *     path="$this->pathInfo",
 *     summary="$this->summary",
 *     description="$this->description",
 *     operationId="$this->operationId",
 *     tags={"$this->tags"},
{$this->formatRequestBody()}
{$this->formatResponse()}
 * )
 */
EOF;
    }

    /**
     * @description 格式化請求體
     *
     * @return string
     */
    protected function formatRequestBody(): string
    {
        $requestBody = <<<EOF
EOF;
        if (in_array($this->method, ['GET', 'HEAD'], true)) { // GET HEAD 只能用Parameter
            $requestBody .= trim($this->formatParameter(), PHP_EOL);
        } else { // POST 、 PUT 接口 均使用 RequestBody

            $requiredStr = '"' . implode('","', array_keys(array_filter($this->columnsRules, static function ($rule) {
                    is_string($rule) && $rule = explode('|', $rule);
                    return Arr::first($rule) === 'required';
                }))) . '"';
            $requestBodyProperty = trim($this->formatProperty($this->validated), PHP_EOL);
            $requestBody .= <<<EOF
 *     @OA\RequestBody(description="請求Body體",
 *         @OA\JsonContent(type="object", required={{$requiredStr}},
$requestBodyProperty
 *         )
 *     ),
EOF;
        }

        return $requestBody;
    }

    /**
     * @description 格式化url路徑上的入參參數
     *
     * @return string
     */
    protected function formatParameter(): string
    {
        $parameter = <<<EOF
EOF;

        foreach ($this->validated as $field => $value) {
            $rules = $this->columnsRules[$field] ?? [''];
            is_string($rules) && $rules = explode('|', $rules);
            $string = explode(',', Arr::last(explode(':', (string)Arr::last($rules))));
            $enums = '';
            if (count($string) > 1) {
                $enums = implode(',', $string);
                $enums = PHP_EOL . <<<EOF
 *             enum={{$enums}},
EOF;
            }
            $required = Arr::first($rules) === 'required' ? 'true' : 'false';
            $description = $this->columnsComments[$field] ?? '';
            $type = get_type($value);

            if (is_string($value) && str_contains($value, '*/')) {
                $value = str_replace('*/', '*\/', $value);
            }

            $values = <<<EOF
*             default="$value",$enums
EOF;

            if (is_array($value)) {
                $values = <<<EOF
 *             @OA\Items(type="string"),
EOF;
            }

            $parameter .= <<<EOF
 *     @OA\Parameter(
 *         name="$field",
 *         in="query",
 *         description="$description",
 *         required=$required,
 *         @OA\Schema(
 *             type="$type",
 $values
 *         )
 *     ),
 
 *         @OA\Schema(
 *             type="array",


EOF;
        }

        return $parameter;
    }

    /**
     * @description 格式化屬性內容
     *
     * @param array $data
     * @param string $space
     *
     * @return string
     */
    protected function formatProperty(array $data = [], string $space = ''): string
    {
        $property = <<<EOF
EOF;

        $propertyItems = 'Property';
        foreach ($data as $field => $value) {
            if ($propertyItems === 'Items') {
                continue;
            }
            // 如果field為整型，說明是列表，propertyItems使用Items，並把field置空
            is_int($field) && ($propertyItems = 'Items') && $field = '';
            // 獲取value的類型類型是否為數組或者對象
            $objectArr = in_array($type = get_type($value), ['object', 'array']);
            $objectValue = !$value && $field ? 'null' : '';
            is_bool($value) && $value = $value ? 'true' : 'false'; // 如果value為佈爾型，賦值為字符串
            !$value && $value = 'null';                            // 如果value為假，賦值為null字符串

            if (is_string($value) && str_contains($value, '*/')) {
                $value = str_replace('*/', '*\/', $value);
            }

            // 遍歷組裝propertiesItems的元素，只有$v存在且為真時，需要組裝進來
            $propertiesItems = [];
            $arr = [
                'property' => $field,
                'type' => $type,
                'description' => $this->columnsComments[$field] ?? '',
                'default' => $objectArr ? $objectValue : $value,
            ];
            foreach ($arr as $k => $v) {
                $v && $propertiesItems[] = $k . '="' . $v . '"';
            }
            // 打散成字符串
            $propertiesItems = implode(', ', $propertiesItems);
            $property .= <<<EOF
 *             $space@OA\\$propertyItems($propertiesItems),

EOF;
            if ($value !== 'null' && $objectArr) { // 值不為null字符串，且是个对象或者数组，就有子集
                // ),PHP_EOL ==> ,PHP_EOL
                $property = rtrim($property, '),' . PHP_EOL) . ',' . PHP_EOL;
                $spaces = $space . '    ';
                // 递归拼接子集
                if (method_exists($value, 'toArray')) {
                    $value = $value->toArray();
                }
                $properties = trim($this->formatProperty($value, $spaces), PHP_EOL);
                $property .= <<<EOF
$properties
 *         $spaces),

EOF;
            }
        }

        return $property;
    }

    /**
     * @description 格式化返回結構
     *
     * @return string
     */
    protected function formatResponse(): string
    {
        $responseProperty = trim($this->formatProperty($this->response), PHP_EOL);

        return <<<EOF
 *     @OA\Response(response=200, description="接口返回OK",
 *         @OA\JsonContent(type="object",
$responseProperty
 *         )
 *     ),
EOF;
    }
}
