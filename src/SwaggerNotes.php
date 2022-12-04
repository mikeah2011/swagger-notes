<?php

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
        $baseDir = base_path('swagger');
        $swaggerNotesDir = $baseDir . '/' . get_class_name(__CLASS__);
        generate_file($swaggerNotesDir, 'swagger.php', $this->formatInfo());
        generate_file($swaggerNotesDir . '/' . $this->tags, $this->operationId . '.php', $this->formatNotes());
        generate_file($baseDir, 'swagger_doc.yaml', Generator::scan([$swaggerNotesDir])->toYaml());
    }
}