<?php

namespace App\Exports;

use App\Enums\ExportEntity;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;

/**
 * @package App\Exports
 */
class ShopOrdersExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,
                                                             WithColumnFormatting, WithStyles, ShouldQueue
{
    use Exportable;

    private array $fields;

    public function __construct()
    {
        $this->fields = [
            'id',
            'ordered_item',
            'price',
            'user',
            'email',
            'address',
            'time',
        ];
    }

    /**
     * @param Collection $orders
     *
     * @return array
     */
    public function prepareRows(Collection $orders): array
    {
        $rows = [];

        $orders->each(function ($_order) use (&$rows) {
            $row = [];
            $orderUser = $_order->user;

            $row['id'] = $_order->id;
            $row['user'] = $orderUser ? $orderUser->name : __("User deleted");
            $row['email'] = $orderUser ? $this->getContacts($orderUser) : null;
            $row['address'] = $_order->address;
            $row['time'] = Date::dateTimeToExcel($_order->created_at);

            foreach ($_order->items->all() as $_orderItem) {
                $orderItemsAmount = $_orderItem->amount;
                $orderItemDescription = $_orderItem->product->title;
                $orderItemPrice = (int)$_orderItem->price;

                for ($x = 1; $x <= $orderItemsAmount; $x++) {
                    $row['ordered_item'] = $orderItemDescription;
                    $row['price'] = $orderItemPrice;
                    $rows[] = $row;
                }
            }
        });

        return $rows;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return ShopOrder::with([
            'user',
            'items' => fn($query) => $query->with('product'),
//            'items' => static function ($query) {
//                $query->with('product');
//            },
        ])->orderBy('created_at');
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
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'G' => "dd/mm/yyyy h:mm",
        ];
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
            'G' => [
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
        logger("Export data [3]: entity " . ExportEntity::SHOP_ORDERS . ", exporting failed: " . $exception->getMessage());
    }

    /**
     * @param User $user
     *
     * @return string|null
     */
    private function getContacts(User $user): ?string
    {
        return $user->email ?: $user->phone;
    }
}
