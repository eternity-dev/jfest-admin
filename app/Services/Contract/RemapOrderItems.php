<?php

namespace App\Services\Contract;

use Illuminate\Support\Collection;

interface RemapOrderItems
{
    public function handle(Collection|null &$items): void;
}
