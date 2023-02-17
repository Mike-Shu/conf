<?php

namespace App\Exports;

use App\Enums\ExportEntity;
use App\Enums\UserGender;
use App\Enums\WalletReasonSystem;
use App\Models\User;
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
class UsersExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize,
                                                        WithStyles, ShouldQueue
{
    use Exportable;

    private array $fields;

    public function __construct()
    {
        $this->fields = [
            'id',
            'name',
            'gender',
            'email',
//            'phone_number',
            'points_total',
            'points_balance',
        ];
    }

    /**
     * @param Collection $users
     *
     * @return array
     */
    public function prepareRows(Collection $users): array
    {
        return $users->map(function ($_user) {
            $row = [];

            $row['id'] = $_user->id;
            $row['name'] = $_user->name;
            $row['gender'] = Str::lower(UserGender::getDescription($_user->gender));
            $row['email'] = $_user->email;
//            $row['phone_number'] = formatPhoneNumber($_user->phone);

            $row['points_total'] = (string)$_user->transactions()
                ->where(function ($query) {
                    return $query->where('meta->reason', '!=', WalletReasonSystem::PURCHASE)
                        ->orWhereNull('meta->reason');
                })->sum('amount');

            $row['points_balance'] = $_user->balance;

            return $row;
        })->toArray();
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return User::orderBy('id');
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
        return [];
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
        logger("Export data [3]: entity " . ExportEntity::USERS . ", exporting failed: " . $exception->getMessage());
    }
}
