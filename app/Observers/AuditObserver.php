<?php

namespace App\Observers;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function __construct(
        protected AuditService $audit
    ) {}

    public function created(Model $model): void
    {
        $this->audit->logModelEvent($model, 'I');
    }

    public function updated(Model $model): void
    {
        if ($model->wasChanged()) {
            $this->audit->logModelEvent($model, 'U');
        }
    }

    public function deleted(Model $model): void
    {
        $this->audit->logModelEvent($model, 'D');
    }
}
