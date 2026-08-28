<?php
$files = glob('c:\laragon\www\liveeditor\database\migrations\*_add_cheat_count_to_submissions_table.php');
if (count($files) > 0) {
    $file = $files[0];
    $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint \$table) {
            \$table->integer('cheat_count')->default(0)->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint \$table) {
            \$table->dropColumn('cheat_count');
        });
    }
};
PHP;
    file_put_contents($file, $content);
    echo "Migration populated.\n";
}
?>