<?php

namespace App\Exports;

use App\Enums\ExportEntity;
use App\Models\ShopProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;

/**
 * @package App\Exports
 */
class ShopStockExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,
                                                            WithStyles, ShouldQueue
{
    use Exportable;

    private array $fields;

    public function __construct()
    {
        $this->fields = [
            'id',
            'title',
            'price',
            'stock_total',
            'purchased',
            'stock_balance',
        ];
    }

    /**
     * @param Collection $products
     *
     * @return array
     */
    public function prepareRows(Collection $products): array
    {
        return $products->map(function ($_product) {
            return [
                'id' => $_product->id,
                'title' => $_product->title,
                'price' => $_product->price,
                'stock_total' => $_product->stock_total,
                'purchased' => $_product->walletTransactions()->count(),
                'stock_balance' => $_product->stock,
            ];
        })->toArray();
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return ShopProduct::orderBy('created_at');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return array_map(static function ($_value) {
            $value = Str::replace('_', ' ', $_value);

            if ($value === "id") {
                return Str::upper($value);
            }

            return __(Str::ucfirst($value));
        }, $this->fields);
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $fields = [];
        foreach ($this->fields as $_field) {
            $fields[] = $row[$_field] ?? "";
        }

        return $fields;
    }

    /**
     * @param Worksheet $sheet
     *
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF808080'],
                    ]
                ],
            ],
            'A' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'B' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'C' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'D' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'E' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
        ];
    }

    /**
     * @param Throwable $exception
     */
    public function failed(Throwable $exception): void
    {
        logger("Export data [3]: entity " . ExportEntity::SHOP_STOCK . ", exporting failed: " . $exception->getMessage());
    }
}
