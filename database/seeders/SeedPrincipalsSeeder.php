<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedPrincipalsSeeder extends Seeder
{
    public function run(): void
    {
        $principals = [
            ['name' => 'Liofilchem', 'address' => 'Italy', 'logo' => '/images/vendor/liofilchem.png', 'status' => 'online'],
            ['name' => 'Bioendo', 'address' => 'China', 'logo' => '/images/vendor/Bioendo-labs.png', 'status' => 'online'],
            ['name' => 'Terragene', 'address' => 'Argentina', 'logo' => '/images/vendor/terragene.png', 'status' => 'online'],
            ['name' => 'Biotool', 'address' => 'Switzerland', 'logo' => '/images/vendor/biotool.png', 'status' => 'online'],
            ['name' => 'IFM Quality Services', 'address' => 'Australia', 'logo' => '/images/vendor/ifm.png', 'status' => 'online'],
            ['name' => 'BNF Korea', 'address' => 'South Korea', 'logo' => '/images/vendor/bnf_korea.png', 'status' => 'online'],
            ['name' => 'Leadfluid', 'address' => 'China', 'logo' => '/images/vendor/leadfluid.png', 'status' => 'online'],
            ['name' => 'Meizheng Group', 'address' => 'China', 'logo' => '/images/vendor/meizheng.png', 'status' => 'online'],
            ['name' => 'KSL Pulse Scientific', 'address' => 'India', 'logo' => '/images/vendor/ksl_pulse.png', 'status' => 'online'],
            ['name' => 'Diamidex', 'address' => 'France', 'logo' => '/images/vendor/diamidex.png', 'status' => 'online'],
            ['name' => 'Lumeley', 'address' => 'United States', 'logo' => '/images/vendor/lumeley.png', 'status' => 'online'],
            ['name' => 'Ratel Systems', 'address' => 'South Korea', 'logo' => '/images/vendor/ratel.png', 'status' => 'online'],
            ['name' => 'Solus Scientific', 'address' => 'United Kingdom', 'logo' => '/images/vendor/solus_scientific.png', 'status' => 'online'],
            ['name' => 'Vecverse', 'address' => 'Japan', 'logo' => '/images/vendor/vecverse.png', 'status' => 'online'],
            ['name' => 'Vision Med', 'address' => 'Germany', 'logo' => '/images/vendor/vision_med.png', 'status' => 'online'],
        ];

        foreach ($principals as $p) {
            DB::table('principals')->updateOrInsert(
                ['name' => $p['name']],
                [
                    'address' => $p['address'],
                    'logo' => $p['logo'],
                    'status' => $p['status'],
                    'updated_at' => now(),
                ]
            );
        }

        // Map existing products to their relevant principals
        $liofilchem = DB::table('principals')->where('name', 'Liofilchem')->value('id');
        $bioendo = DB::table('principals')->where('name', 'Bioendo')->value('id');
        $terragene = DB::table('principals')->where('name', 'Terragene')->value('id');
        $biotool = DB::table('principals')->where('name', 'Biotool')->value('id');
        $ifm = DB::table('principals')->where('name', 'IFM Quality Services')->value('id');
        $bnf = DB::table('principals')->where('name', 'BNF Korea')->value('id');
        $leadfluid = DB::table('principals')->where('name', 'Leadfluid')->value('id');
        $meizheng = DB::table('principals')->where('name', 'Meizheng Group')->value('id');

        if ($liofilchem) {
            DB::table('products')
                ->where('category', 'like', '%culture-media%')
                ->orWhere('sub_category', 'like', '%Culture Media%')
                ->orWhere('title', 'like', '%Agar%')
                ->orWhere('title', 'like', '%Broth%')
                ->update([
                    'principal_id' => $liofilchem,
                    'datasheet_url' => 'https://www.liofilchem.com/datasheet-preview.pdf',
                ]);
        }

        if ($bioendo) {
            DB::table('products')
                ->where('sub_category', 'like', '%endotoxin%')
                ->orWhere('title', 'like', '%Endotoxin%')
                ->orWhere('title', 'like', '%Microplate%')
                ->update([
                    'principal_id' => $bioendo,
                    'datasheet_url' => 'https://www.bioendo.com/download/bioendo-datasheet.pdf',
                ]);
        }

        if ($terragene) {
            DB::table('products')
                ->where('sub_category', 'like', '%Biological Indicator%')
                ->orWhere('title', 'like', '%Indicator%')
                ->update([
                    'principal_id' => $terragene,
                    'datasheet_url' => 'https://terragene.com/technical-sheets/indicator-spec.pdf',
                ]);
        }

        if ($ifm) {
            DB::table('products')
                ->where('category', 'like', '%reference-standard%')
                ->orWhere('sub_category', 'like', '%standards%')
                ->update([
                    'principal_id' => $ifm,
                    'datasheet_url' => 'https://ifm.net.au/standards/certificate-spec.pdf',
                ]);
        }

        if ($leadfluid) {
            DB::table('products')
                ->where('sub_category', 'like', '%liquid-handling%')
                ->update([
                    'principal_id' => $leadfluid,
                    'datasheet_url' => 'https://www.leadfluid.com/specs/leadfluid-pumps.pdf',
                ]);
        }

        if ($bnf) {
            DB::table('products')
                ->where('sub_category', 'like', '%microbiological-instrument%')
                ->orWhere('sub_category', 'like', '%Cabinet%')
                ->update([
                    'principal_id' => $bnf,
                    'datasheet_url' => 'https://www.bnfkorea.net/brochures/spec-sheet.pdf',
                ]);
        }

        if ($meizheng) {
            DB::table('products')
                ->where('sub_category', 'like', '%Food Safety%')
                ->update([
                    'principal_id' => $meizheng,
                    'datasheet_url' => 'https://www.meizhenggroup.com/docs/datasheet.pdf',
                ]);
        }

        if ($biotool) {
            DB::table('products')
                ->where('sub_category', 'like', '%agar-filler%')
                ->update([
                    'principal_id' => $biotool,
                    'datasheet_url' => 'https://www.biotoolswiss.com/datasheets/profimatic.pdf',
                ]);
        }

        // Leave any remaining without datasheet_url to verify fallback request mechanism
        $this->command->info('Seed data lengkap Prinsipal & pemetaan produk berhasil.');
    }
}
