<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAntigenExtractView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("drop view if exists `antigen_extract`");
        DB::statement("create VIEW `antigen_extract` AS
      select
        `antigen`.`antigen_id` AS `antigen_id`,
        `antigen`.`name` AS `name`,
        `antigen`.`clinic_part_number` AS `clinic_part_number`,
        `antigen`.`test_order` AS `test_order`,
        `extract`.`extract_id` AS `extract_id`,
        `extract`.`latinname` AS `latinname`,
        `extract`.`manufacturer` AS `manufacturer`,
        `extract`.`code` AS `code`,
        `extract`.`ndc` AS `ndc`,
        `extract`.`abbreviation` AS `abbreviation`,
        `extract`.`visible` AS `visible`,
        `extract`.`percentGlycerin` AS `percentGlycerin`,
        `extract`.`percentPhenol` AS `percentPhenol`,
        `extract`.`percentHSA` AS `percentHSA`,
        `extract`.`dilution` AS `dilution`,
        `extract`.`units` AS `units`,
        `extract`.`cost` AS `cost`,
        `extract`.`sub` AS `sub`,
        `extract`.`specificgravity` AS `specificgravity`,
        `extract`.`outdatealert` AS `outdatealert`,
        `extract`.`compatibility_class_id` AS `compatibility_class_id`,
        `extract`.`imagefile` AS `imagefile`,
        `extract`.`isDiluent` AS `isDiluent`,
        `extract`.`silhouette` AS `silhouette`,
        `extract`.`color` AS `color`,
        `extract`.`topline` AS `topline`,
        `extract`.`firstline` AS `firstline`,
        `extract`.`secondline` AS `secondline`,
        `extract`.`seasonStart` AS `seasonStart`,
        `extract`.`seasonEnd` AS `seasonEnd`,
        IFNULL(`extract`.`deleted`, 'F') AS `deleted`,
        `extract`.`updated_at` AS `updated_at`,
        `extract`.`updated_by` AS `updated_by`,
        `extract`.`created_at` AS `created_at`,
        `extract`.`created_by` AS `created_by`
        from (`antigen` left join `extract` on((`antigen`.`extract_id` = `extract`.`extract_id`)))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view if exists `antigen_extract`");
    }
}
