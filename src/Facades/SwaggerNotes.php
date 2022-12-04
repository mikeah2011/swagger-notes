<?php

namespace Toolbox\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static object setRequest($request)
 * @method static object setResponse($response)
 * 
 * @method object setComments(array $tables = [], array $rules = [])
 * @method object setSummary(string $summary = '接口概述')
 * @method object setDescription(string $description = '接口描述')
 * @method object setOperationId(string $function = '接口標識')
 * @method object setTags(string $class = '接口標籤')
 * @method object setInfoDescription(string $infoDescription = '信息頭描述')
 * @method object setTitle(string $title = '接口服務')
 * @method object setVersion(string $version = '1.0.0')
 *
 * @method object generate()
 *
 * @see \Toolbox\SwaggerNotes
 */
class SwaggerNotes extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Toolbox\SwaggerNotes::class;
    }
}
