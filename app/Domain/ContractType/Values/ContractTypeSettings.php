<?php

declare(strict_types=1);

namespace App\Domain\ContractType\Values;

final class ContractTypeSettings
{
    private $value;

    public function __construct(object|array $settings)
    {
        if (is_object($settings)) {
            $settings = (array) $settings;
        }

        $this->value = $settings;
    }

    public function value(): object|array
    {
        return $this->value;
    }

    public function healthEntityAffiliation(): bool
    {
        return $this->value['health_entity_affiliation'] ?? false;
    }

    public function pensionFundAffiliation(): bool
    {
        return $this->value['pension_fund_affiliation'] ?? false;
    }

    public function compensationFundAffiliations(): bool
    {
        return $this->value['compensation_fund_affiliation'] ?? false;
    }

    public function riskManagerAffiliation(): bool
    {
        return $this->value['risk_manager_affiliation'] ?? false;
    }

    public function bankCertificate(): bool
    {
        return $this->value['bank_certificate'] ?? false;
    }

    public function fiscalDocumentCertificate(): bool
    {
        return $this->value['fiscal_document_certificate'] ?? false;
    }

    public function requiresEndDate(): bool
    {
        return $this->value['requires_end_date'] ?? false;
    }
}
