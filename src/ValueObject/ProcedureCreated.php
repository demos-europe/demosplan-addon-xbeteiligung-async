<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;


class ProcedureCreated extends ValueObject
{
    protected string $procedureId;

    protected string $planId;

    protected string $vorgangsId;

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function setProcedureId(string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }

    public function getPlanId(): string
    {
        return $this->planId;
    }

    public function setPlanId(string $planId): void
    {
        $this->planId = $planId;
    }
}
