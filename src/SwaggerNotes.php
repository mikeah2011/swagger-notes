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
        // 當環境是 APP_ENV prod 或者 stage 時不生成文件
        if (in_array(env('APP_ENV', ''), ['prod', 'stage'], true)) {
            return;
        }
        $baseDir = base_path('swagger');
        $swaggerNotesDir = $baseDir . '/' . get_class_name(__CLASS__);
        generate_file($swaggerNotesDir, 'swagger.php', $this->formatInfo());
        // 直接覆蓋更新
        generate_file($swaggerNotesDir . '/' . $this->tags, $this->operationId . '.php', $this->formatNotes());
        generate_file($baseDir, 'swagger_doc.yaml', Generator::scan([$swaggerNotesDir])->toYaml());
    }
}
