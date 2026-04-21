<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicinePresentation;
use App\Models\Presentation;
use Illuminate\Database\Seeder;

class PharmacyMedicineCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'category' => 'Analgesicos',
                'category_description' => 'Medicamentos para el dolor y la fiebre.',
                'medicine' => 'Acetaminofen 500 mg',
                'medicine_description' => 'Analgesico y antipiretico de uso frecuente.',
                'presentation' => 'Tabletas',
                'presentation_description' => 'Presentacion solida en tabletas.',
                'sale_price' => 4.50,
                'minimum_stock' => 15,
            ],
            [
                'category' => 'Analgesicos',
                'category_description' => 'Medicamentos para el dolor y la fiebre.',
                'medicine' => 'Ibuprofeno 400 mg',
                'medicine_description' => 'Antiinflamatorio no esteroideo en capsulas.',
                'presentation' => 'Capsulas',
                'presentation_description' => 'Presentacion oral en capsulas.',
                'sale_price' => 6.25,
                'minimum_stock' => 12,
            ],
            [
                'category' => 'Antibioticos',
                'category_description' => 'Medicamentos para infecciones bacterianas.',
                'medicine' => 'Amoxicilina 250 mg/5 ml',
                'medicine_description' => 'Antibiotico de amplio espectro.',
                'presentation' => 'Suspension oral',
                'presentation_description' => 'Presentacion liquida para via oral.',
                'sale_price' => 28.00,
                'minimum_stock' => 8,
            ],
            [
                'category' => 'Antialergicos',
                'category_description' => 'Medicamentos para control de alergias.',
                'medicine' => 'Loratadina 5 mg/5 ml',
                'medicine_description' => 'Antihistaminico en jarabe.',
                'presentation' => 'Jarabe',
                'presentation_description' => 'Presentacion liquida tipo jarabe.',
                'sale_price' => 18.75,
                'minimum_stock' => 10,
            ],
            [
                'category' => 'Respiratorios',
                'category_description' => 'Medicamentos para apoyo respiratorio.',
                'medicine' => 'Salbutamol 100 mcg',
                'medicine_description' => 'Broncodilatador de accion rapida.',
                'presentation' => 'Inhalador',
                'presentation_description' => 'Presentacion en aerosol inhalado.',
                'sale_price' => 65.00,
                'minimum_stock' => 6,
            ],
            [
                'category' => 'Dermatologicos',
                'category_description' => 'Medicamentos para uso topico en piel.',
                'medicine' => 'Clotrimazol 1 %',
                'medicine_description' => 'Antifungico topico de uso frecuente.',
                'presentation' => 'Crema',
                'presentation_description' => 'Presentacion topica tipo crema.',
                'sale_price' => 22.50,
                'minimum_stock' => 9,
            ],
            [
                'category' => 'Vitaminas',
                'category_description' => 'Suplementos y apoyo nutricional.',
                'medicine' => 'Vitamina C 1 g',
                'medicine_description' => 'Suplemento vitaminico en sobres.',
                'presentation' => 'Sobres',
                'presentation_description' => 'Presentacion en sobres monodosis.',
                'sale_price' => 3.75,
                'minimum_stock' => 20,
            ],
            [
                'category' => 'Antibioticos',
                'category_description' => 'Medicamentos para infecciones bacterianas.',
                'medicine' => 'Ceftriaxona 1 g',
                'medicine_description' => 'Antibiotico de uso intramuscular o intravenoso.',
                'presentation' => 'Inyectable',
                'presentation_description' => 'Presentacion inyectable esteril.',
                'sale_price' => 19.00,
                'minimum_stock' => 10,
            ],
            [
                'category' => 'Oftalmologicos',
                'category_description' => 'Medicamentos para tratamiento ocular.',
                'medicine' => 'Gentamicina 0.3 %',
                'medicine_description' => 'Antibiotico oftalmico en gotas.',
                'presentation' => 'Gotas oftalmicas',
                'presentation_description' => 'Presentacion en gotas para uso ocular.',
                'sale_price' => 17.25,
                'minimum_stock' => 7,
            ],
            [
                'category' => 'Cardiovasculares',
                'category_description' => 'Medicamentos de apoyo cardiovascular.',
                'medicine' => 'Furosemida 20 mg/2 ml',
                'medicine_description' => 'Diuretico de accion rapida.',
                'presentation' => 'Ampollas',
                'presentation_description' => 'Presentacion en ampollas individuales.',
                'sale_price' => 9.80,
                'minimum_stock' => 10,
            ],
        ];

        foreach ($items as $item) {
            $category = MedicineCategory::query()->firstOrCreate(
                ['nombre' => $item['category']],
                ['descripcion' => $item['category_description']]
            );

            $presentation = Presentation::query()->firstOrCreate(
                ['nombre' => $item['presentation']],
                ['descripcion' => $item['presentation_description']]
            );

            $medicine = Medicine::query()->firstOrCreate(
                [
                    'id_categoria' => $category->id_categoria,
                    'nombre' => $item['medicine'],
                ],
                [
                    'descripcion' => $item['medicine_description'],
                    'estado' => true,
                ]
            );

            $medicine->descripcion = $item['medicine_description'];
            $medicine->estado = true;
            $medicine->save();

            MedicinePresentation::query()->updateOrCreate(
                [
                    'id_medicamento' => $medicine->id_medicamento,
                    'id_presentacion' => $presentation->id_presentacion,
                ],
                [
                    'precio_venta' => $item['sale_price'],
                    'stock_minimo' => $item['minimum_stock'],
                    'estado' => true,
                ]
            );
        }
    }
}
