<?php

namespace App\Models\enums;

enum PaymentMethodEnum: string {
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
}