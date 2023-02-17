<?php

namespace App\Services;

use App\Enums\WalletReasonMain;
use App\Enums\WalletReasonSystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class AllWalletReasonService
{
    /**
     * @param array $onlyReasons
     * @return string[]
     */
    public function asSelectArray(array $onlyReasons = []): array
    {
        $reasons = $this->getWalletReasonCollection();

        if ($onlyReasons) {
            $reasons = $reasons->only($onlyReasons);
        }

        return $reasons->sort()->toArray();
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->getWalletReasonCollection()->keys()->toArray();
    }

    /**
     * @param string|null $key
     * @return string
     */
    public function getDescription(?string $key): string
    {
        $reasons = $this->getWalletReasonCollection();

        if ($key) {
            return $reasons->has($key)
                ? $reasons->get($key)
                : $key;
        }

        return (string)$key;
    }

    /**
     * @return Collection
     */
    private function getWalletReasonCollection(): Collection
    {
        return collect(Arr::collapse([
            WalletReasonMain::asSelectArray(),
            WalletReasonSystem::asSelectArray(),
        ]));
    }
}
