<?php

namespace App\Helpers;

class WhatsApp
{
    public static function shareLink(string $phone, string $message): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '92'.substr($phone, 1);
        } elseif (substr($phone, 0, 1) !== '92') {
            $phone = '92'.$phone;
        }

        return 'https://wa.me/'.$phone.'?text='.urlencode($message);
    }

    public static function propertyInquiryMessage($property, $settings): string
    {
        $currency = $settings['currency'] ?? 'PKR';
        $message = "Hello! I am interested in:\n";
        $message .= "Property: {$property->property_code} - {$property->title}\n";
        $message .= "Price: {$currency} ".number_format($property->price, 0)."\n";
        $message .= "Location: {$property->city}".($property->sector_town ? ", {$property->sector_town}" : '')."\n";
        if ($property->bedrooms) {
            $message .= "Bedrooms: {$property->bedrooms}\n";
        }
        if ($property->plot_size) {
            $message .= "Size: {$property->plot_size} {$property->plot_size_unit}\n";
        }
        $message .= "\nPlease share more details.";

        return $message;
    }

    public static function dealUpdateMessage($deal): string
    {
        $message = "Update on Deal {$deal->deal_number}:\n";
        $message .= "Property: {$deal->property?->title}\n";
        $message .= 'Status: '.str_replace('_', ' ', ucfirst($deal->status))."\n";
        $message .= 'Amount: '.number_format($deal->sale_price ?? 0, 0);

        return $message;
    }
}
