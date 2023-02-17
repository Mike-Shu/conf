<?php

namespace App\Services;

use App\Enums\ExportEntity;
use App\Enums\ExportType;
use App\Exports\ShopOrdersExport;
use App\Exports\ShopStockExport;
use App\Exports\UsersExport;
use App\Jobs\ExportDataCompletedJob;
use App\Models\Export;
use App\Models\Tenant;

/**
 * Export data to Excel file.
 *
 * @package App\Services
 */
class ExportDataService
{
    /**
     * @param string $entity
     */
    public function batchExport(string $entity): void
    {
        switch ($entity) {
            case ExportEntity::USERS:
                logger("Export data [1]: dispatch 'batchExportUsersList'");
                $this->batchExportUsersList(tenant());
                break;
            case ExportEntity::SHOP_STOCK:
                logger("Export data [1]: dispatch 'batchExportShopStockItems'");
                $this->batchExportShopStockItems(tenant());
                break;
            case ExportEntity::SHOP_ORDERS:
                logger("Export data [1]: dispatch 'batchExportShopOrderItems'");
                $this->batchExportShopOrderItems(tenant());
                break;
        }
    }

    /**
     * Batch for exports tenant users.
     *
     * @param Tenant $tenant
     */
    public function batchExportUsersList(Tenant $tenant): void
    {
        $this->processBatch($tenant->id, ExportEntity::USERS, new UsersExport());
    }

    /**
     * Batch for exports shop stock items.
     *
     * @param Tenant $tenant
     */
    public function batchExportShopStockItems(Tenant $tenant): void
    {
        $this->processBatch($tenant->id, ExportEntity::SHOP_STOCK, new ShopStockExport());
    }

    /**
     * Batch for exports shop order items.
     *
     * @param Tenant $tenant
     */
    public function batchExportShopOrderItems(Tenant $tenant): void
    {
        $this->processBatch($tenant->id, ExportEntity::SHOP_ORDERS, new ShopOrdersExport());
    }

    /**
     * @param string $tenantId
     * @param string $entity
     * @param $exportBatch
     */
    private function processBatch(string $tenantId, string $entity, $exportBatch): void
    {
        $dateTime = now()->format('Y-m-d_H-i-s');
        $filename = md5($dateTime) . "_{$entity}_{$dateTime}.xlsx";
        $filePath = config('export.data.folder') . "/" . $filename;

        // Add the entity to the export log.
        $exportLog = Export::create([
            'entity' => $entity,
            'filename' => $filename,
            'type' => ExportType::DATA,
        ]);
        logger("Export data [2]: entity '{$entity}', added entity to export log");

        // Save the received data to a file and run a task that completes the export.
        $exportBatch->queue($filePath)->chain([
            new ExportDataCompletedJob([
                'entity' => $entity,
                'tenant_id' => $tenantId,
                'export_id' => $exportLog->id,
            ]),
        ]);
        logger("Export data [3]: entity '{$entity}', started saving data to file");
    }
}
