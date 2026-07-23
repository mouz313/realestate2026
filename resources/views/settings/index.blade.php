@extends('layouts.admin')

@section('title', 'Settings <span class="urdu">(ترتیبات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Settings <span class="urdu">(ترتیبات)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3><i class="ti ti-settings me-1"></i> Settings <span class="urdu">(ترتیبات)</span></h3>
        <div class="page-header-sub">Configure your business, branding, and module settings <span class="urdu">(اپنے کاروبار، برانڈنگ اور ماڈیول کی ترتیبات مرتب کریں)</span></div>
    </div>
</div>

<form action="{{ route('settings.index') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="business-tab" data-bs-toggle="tab" data-bs-target="#business" type="button" role="tab">
                <i class="ti ti-building-store"></i> Business <span class="urdu">(کاروبار)</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                <i class="ti ti-palette"></i> Branding <span class="urdu">(برانڈنگ)</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                <i class="ti ti-mail"></i> Email <span class="urdu">(ای میل)</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                <i class="ti ti-currency-dollar"></i> Payment <span class="urdu">(ادائیگی)</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="re-tab" data-bs-toggle="tab" data-bs-target="#realestate" type="button" role="tab">
                <i class="ti ti-building"></i> Real Estate <span class="urdu">(رئیل اسٹیٹ)</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms" type="button" role="tab">
                <i class="ti ti-message"></i> SMS <span class="urdu">(ایس ایم ایس)</span>
            </button>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Business Tab --}}
        <div class="tab-pane fade show active" id="business" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-building-store me-1"></i> Business Information <span class="urdu">(کاروباری معلومات)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Business Name <span class="urdu">(کاروبار کا نام)</span></label>
                                <input type="text" class="form-control" name="business_name" value="{{ $settings['business_name'] ?? config('app.name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Business Email <span class="urdu">(کاروباری ای میل)</span></label>
                                <input type="email" class="form-control" name="business_email" value="{{ $settings['business_email'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Business Phone <span class="urdu">(کاروباری فون)</span></label>
                                <input type="text" class="form-control" name="business_phone" value="{{ $settings['business_phone'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Business Address <span class="urdu">(کاروباری پتہ)</span></label>
                                <textarea class="form-control" name="business_address" rows="3">{{ $settings['business_address'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tax Label <span class="urdu">(ٹیکس لیبل)</span> <span class="text-secondary">(e.g. GST, VAT, PNTN)</span></label>
                                <input type="text" class="form-control" name="tax_label" value="{{ $settings['tax_label'] ?? 'GST' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tax Rate (%) <span class="urdu">(ٹیکس کی شرح)</span></label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" name="tax_rate" value="{{ $settings['tax_rate'] ?? '0' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Currency <span class="urdu">(کرنسی)</span></label>
                                <input type="text" class="form-control" name="currency" value="{{ $settings['currency'] ?? 'PKR' }}" maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Branding Tab --}}
        <div class="tab-pane fade" id="branding" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-palette me-1"></i> Branding <span class="urdu">(برانڈنگ)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Logo <span class="urdu">(لوگو)</span></label>
                                <div class="brand-logo-preview mb-2">
                                    @if(!empty($settings['brand_logo']))
                                        <img src="{{ Storage::url($settings['brand_logo']) }}" alt="Logo" class="img-fluid border rounded" style="max-height:80px;">
                                    @else
                                        <div class="text-secondary small">No logo uploaded <span class="urdu">(کوئی لوگو اپ لوڈ نہیں)</span></div>
                                    @endif
                                </div>
                                <input type="file" class="form-control" name="brand_logo" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Favicon <span class="urdu">(فیویکن)</span></label>
                                <div class="brand-logo-preview mb-2">
                                    @if(!empty($settings['brand_favicon']))
                                        <img src="{{ Storage::url($settings['brand_favicon']) }}" alt="Favicon" class="border rounded" style="max-height:32px;">
                                    @else
                                        <div class="text-secondary small">No favicon uploaded <span class="urdu">(کوئی فیویکن اپ لوڈ نہیں)</span></div>
                                    @endif
                                </div>
                                <input type="file" class="form-control" name="brand_favicon" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Email Tab --}}
        <div class="tab-pane fade" id="email" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-mail me-1"></i> Email Configuration <span class="urdu">(ای میل کنفیگریشن)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mail Driver <span class="urdu">(میل ڈرائیور)</span></label>
                                <select class="form-select" name="mail_driver">
                                    <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($settings['mail_driver'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($settings['mail_driver'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="log" {{ ($settings['mail_driver'] ?? '') === 'log' ? 'selected' : '' }}>Log (testing) <span class="urdu">(لاگ - جانچ)</span></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Host <span class="urdu">(ایس ایم ٹی پی ہوسٹ)</span></label>
                                <input type="text" class="form-control" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" placeholder="e.g. smtp.gmail.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Port <span class="urdu">(ایس ایم ٹی پی پورٹ)</span></label>
                                <input type="number" class="form-control" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" placeholder="587">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Encryption <span class="urdu">(انکرپشن)</span></label>
                                <select class="form-select" name="mail_encryption">
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ ($settings['mail_encryption'] ?? '') === '' ? 'selected' : '' }}>None <span class="urdu">(کوئی نہیں)</span></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SMTP Username <span class="urdu">(ایس ایم ٹی پی صارف نام)</span></label>
                                <input type="text" class="form-control" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Password <span class="urdu">(ایس ایم ٹی پی پاس ورڈ)</span></label>
                                <input type="password" class="form-control" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">From Address <span class="urdu">(بھیجنے والا پتہ)</span></label>
                                <input type="email" class="form-control" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">From Name <span class="urdu">(بھیجنے والے کا نام)</span></label>
                                <input type="text" class="form-control" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? '' }}" placeholder="{{ $settings['business_name'] ?? config('app.name') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Tab --}}
        <div class="tab-pane fade" id="payment" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-currency-dollar me-1"></i> Payment Settings <span class="urdu">(ادائیگی کی ترتیبات)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payment Terms (days) <span class="urdu">(ادائیگی کی شرائط - دن)</span></label>
                                <input type="number" class="form-control" name="payment_terms" value="{{ $settings['payment_terms'] ?? '30' }}" min="0" max="365">
                                <div class="form-text">Default due date offset when creating invoices. <span class="urdu">(انوائس بناتے وقت طے شدہ واجب الادا تاریخ)</span></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Accepted Payment Methods <span class="urdu">(قبول شدہ ادائیگی کے طریقے)</span></label>
                                <input type="text" class="form-control" name="payment_methods" value="{{ $settings['payment_methods'] ?? '' }}" placeholder="e.g. Cash, Bank Transfer, Cheque, Card">
                                <div class="form-text">Comma-separated list. <span class="urdu">(کوما سے الگ کردہ فہرست)</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bank Name <span class="urdu">(بینک کا نام)</span></label>
                                <input type="text" class="form-control" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bank Account Title / Number <span class="urdu">(بینک اکاؤنٹ ٹائٹل / نمبر)</span></label>
                                <input type="text" class="form-control" name="bank_account" value="{{ $settings['bank_account'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">IBAN <span class="urdu">(آئی بی اے این)</span></label>
                                <input type="text" class="form-control" name="bank_iban" value="{{ $settings['bank_iban'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SWIFT / BIC <span class="urdu">(سوئفٹ / بک)</span></label>
                                <input type="text" class="form-control" name="bank_swift" value="{{ $settings['bank_swift'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Real Estate Tab --}}
        <div class="tab-pane fade" id="realestate" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-building me-1"></i> Real Estate Settings <span class="urdu">(رئیل اسٹیٹ کی ترتیبات)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Default Commission Rate (%) <span class="urdu">(طے شدہ کمیشن کی شرح)</span></label>
                                <input type="number" step="0.01" class="form-control" name="default_commission_rate" value="{{ $settings['default_commission_rate'] ?? '2.5' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Default Agent Commission Share (%) <span class="urdu">(طے شدہ ایجنٹ کمیشن حصہ)</span></label>
                                <input type="number" step="0.01" class="form-control" name="default_agent_commission_share" value="{{ $settings['default_agent_commission_share'] ?? '50' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Token / Bayana Percentage (%) <span class="urdu">(ٹوکن / بیعانہ فیصد)</span></label>
                                <input type="number" step="0.01" class="form-control" name="token_percentage" value="{{ $settings['token_percentage'] ?? '10' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Listing Expiry (days) <span class="urdu">(لسٹنگ میعاد ختم - دن)</span></label>
                                <input type="number" class="form-control" name="listing_expiry_days" value="{{ $settings['listing_expiry_days'] ?? '90' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Default City <span class="urdu">(طے شدہ شہر)</span></label>
                                <input type="text" class="form-control" name="default_city" value="{{ $settings['default_city'] ?? 'Lahore' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Property Viewing Duration (mins) <span class="urdu">(جائیداد دیکھنے کا دورانیہ - منٹ)</span></label>
                                <input type="number" class="form-control" name="property_viewing_duration" value="{{ $settings['property_viewing_duration'] ?? '30' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rental Commission (months) <span class="urdu">(کرایہ کمیشن - مہینے)</span></label>
                                <input type="number" step="0.1" class="form-control" name="rental_commission_months" value="{{ $settings['rental_commission_months'] ?? '1' }}">
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="enable_cnic_verification" value="1" id="cnicCheck" {{ !empty($settings['enable_cnic_verification']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cnicCheck">Enable CNIC Verification <span class="urdu">(شناختی کارڈ کی تصدیق فعال کریں)</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SMS Tab --}}
        <div class="tab-pane fade" id="sms" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-message me-1"></i> SMS Configuration <span class="urdu">(ایس ایم ایس کنفیگریشن)</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SMS Provider <span class="urdu">(ایس ایم ایس فراہم کنندہ)</span></label>
                                <select class="form-select" name="sms_provider">
                                    <option value="log" {{ ($settings['sms_provider'] ?? 'log') === 'log' ? 'selected' : '' }}>Log only (testing) <span class="urdu">(صرف لاگ - جانچ)</span></option>
                                    <option value="connectix" {{ ($settings['sms_provider'] ?? '') === 'connectix' ? 'selected' : '' }}>Connectix</option>
                                    <option value="twilio" {{ ($settings['sms_provider'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMS Username <span class="urdu">(ایس ایم ایس صارف نام)</span></label>
                                <input type="text" class="form-control" name="sms_username" value="{{ $settings['sms_username'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SMS Password <span class="urdu">(ایس ایم ایس پاس ورڈ)</span></label>
                                <input type="password" class="form-control" name="sms_password" value="{{ $settings['sms_password'] ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMS Sender ID <span class="urdu">(ایس ایم ایس بھیجنے والے کی آئی ڈی)</span></label>
                                <input type="text" class="form-control" name="sms_sender" value="{{ $settings['sms_sender'] ?? 'Agency' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Save All Settings <span class="urdu">(تمام ترتیبات محفوظ کریں)</span></button>
    </div>
</form>
@endsection