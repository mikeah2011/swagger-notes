<?php

declare(strict_types=1);

namespace Toolbox;

use OpenApi\Generator;

class SwaggerNotes extends SwaggerNotesFormat
{
    /**
     * @description 「生成|刷新」SwaggerPHP注釋內容以及SwaggerYaml文件
     *
     */
    public function generate(): void
    {
        if (
            !env('APP_DEBUG', false)
            || !in_array(env('APP_ENV', ''), ['local', 'testing', 'test', 'develop', 'dev', 'site', 'sit'])
        ) {
            return;
        }
        $baseDir = base_path('swagger');
        $swaggerNotesDir = $baseDir . '/' . get_class_name(__CLASS__);
        // 任意一個有值，就需要更新一下info文件
        generate_file($swaggerNotesDir, 'swagger.php', $this->formatInfo());
        // 直接覆蓋更新
        generate_file($swaggerNotesDir . '/' . $this->tags, $this->operationId . '.php', $this->formatNotes());
        generate_file($baseDir, 'swagger_doc.yaml', Generator::scan([$swaggerNotesDir])->toYaml());
    }
}