<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Sidebar with grouped, role-aware nav items.
 *
 * Render via <x-sidebar />
 *
 * Each group has: label, optional Urdu, items (icon, label, urdu, route, active_pattern, permission).
 */
class Sidebar extends Component
{
    public array $groups;

    public function __construct()
    {
        $r = request();

        $this->groups = [
            [
                'label' => null,
                'items' => [
                    [
                        'route' => dashboard_route(),
                        'icon' => 'ti-home',
                        'label' => 'Dashboard',
                        'urdu' => 'ڈیش بورڈ',
                        'active' => $r->routeIs(dashboard_route()),
                    ],
                ],
            ],
            [
                'label' => 'Sales & CRM',
                'urdu' => 'سیلز',
                'items' => [
                    ['route' => 'clients.index', 'icon' => 'ti-users', 'label' => 'Clients', 'urdu' => 'گاہک', 'active' => $r->routeIs('clients.*'), 'permission' => 'view_clients'],
                    ['route' => 'call-logs.index', 'icon' => 'ti-phone-call', 'label' => 'Leads', 'urdu' => 'لیڈز', 'active' => $r->routeIs('call-logs.*'), 'permission' => 'view_clients'],
                    ['route' => 'quotations.index', 'icon' => 'ti-file-description', 'label' => 'Quotations', 'urdu' => 'کوٹیشن', 'active' => $r->routeIs('quotations.*'), 'permission' => 'view_quotations'],
                    ['route' => 'invoices.index', 'icon' => 'ti-file-invoice', 'label' => 'Invoices', 'urdu' => 'انوائس', 'active' => $r->routeIs('invoices.*'), 'permission' => 'view_invoices'],
                    ['route' => 'payments.index', 'icon' => 'ti-currency-dollar', 'label' => 'Payments', 'urdu' => 'ادائیگی', 'active' => $r->routeIs('payments.*'), 'permission' => 'view_all_payments'],
                ],
            ],
            [
                'label' => 'Real Estate',
                'urdu' => 'جائیداد',
                'items' => [
                    ['route' => 'properties.index', 'icon' => 'ti-building', 'label' => 'Properties', 'urdu' => 'پراپرٹیز', 'active' => $r->routeIs('properties.*') && ! $r->routeIs('properties.available'), 'permission' => 'view_properties'],
                    ['route' => 'properties.available', 'icon' => 'ti-building-community', 'label' => 'Available', 'urdu' => 'دستیاب', 'active' => $r->routeIs('properties.available'), 'permission' => 'view_properties'],
                    ['route' => 'deals.index', 'icon' => 'ti-handshake', 'label' => 'Deals', 'urdu' => 'ڈیلز', 'active' => $r->routeIs('deals.*'), 'permission' => 'view_deals'],
                    ['route' => 'tokens.index', 'icon' => 'ti-coin', 'label' => 'Tokens', 'urdu' => 'بیعانہ', 'active' => $r->routeIs('tokens.*'), 'permission' => 'view_deals'],
                    ['route' => 'property-visits.index', 'icon' => 'ti-calendar-event', 'label' => 'Visits', 'urdu' => 'وزٹ', 'active' => $r->routeIs('property-visits.*'), 'permission' => 'view_visits'],
                ],
            ],
            [
                'label' => 'Rentals',
                'urdu' => 'کرائے',
                'items' => [
                    ['route' => 'rental-records.index', 'icon' => 'ti-home-check', 'label' => 'Rented Records', 'urdu' => 'کرائے ریکارڈ', 'active' => $r->routeIs('rental-records.*'), 'permission' => 'view_deals'],
                ],
            ],
            [
                'label' => 'Team & Payouts',
                'urdu' => 'ٹیم',
                'items' => [
                    ['route' => 'team.index', 'icon' => 'ti-users-group', 'label' => 'Team', 'urdu' => 'ٹیم', 'active' => $r->routeIs('team.*') || $r->routeIs('agents.*'), 'permission' => 'view_team'],
                    ['route' => 'commissions.index', 'icon' => 'ti-percentage', 'label' => 'Commissions', 'urdu' => 'کمیشن', 'active' => $r->routeIs('commissions.*'), 'permission' => 'view_all_commissions'],
                    ['route' => 'agent-payouts.index', 'icon' => 'ti-cash', 'label' => 'Agent Payouts', 'urdu' => 'ادائیگی', 'active' => $r->routeIs('agent-payouts.*'), 'permission' => 'view_payouts'],
                ],
            ],
            [
                'label' => null,
                'items' => [
                    ['route' => 'reports.index', 'icon' => 'ti-report', 'label' => 'Reports', 'urdu' => 'رپورٹس', 'active' => $r->routeIs('reports.*'), 'permission' => 'view_reports'],
                    ['route' => 'notifications.index', 'icon' => 'ti-bell', 'label' => 'Notifications', 'urdu' => 'اطلاعات', 'active' => $r->routeIs('notifications.*'), 'badge' => auth()->user()?->unreadNotifications()->count()],
                ],
            ],
            [
                'label' => 'Administration',
                'urdu' => 'انتظامیہ',
                'permission' => 'admin',
                'items' => [
                    ['route' => 'cities.index', 'icon' => 'ti-building-community', 'label' => 'Cities', 'urdu' => 'شہر', 'active' => $r->routeIs('cities.*')],
                    ['route' => 'expenses.index', 'icon' => 'ti-receipt', 'label' => 'Expenses', 'urdu' => 'اخراجات', 'active' => $r->routeIs('expenses.*')],
                    ['route' => 'roles.index', 'icon' => 'ti-shield', 'label' => 'Roles', 'urdu' => 'کردار', 'active' => $r->routeIs('roles.*')],
                    ['route' => 'permissions.index', 'icon' => 'ti-key', 'label' => 'Permissions', 'urdu' => 'اجازتیں', 'active' => $r->routeIs('permissions.*')],
                    ['route' => 'activity-log', 'icon' => 'ti-history', 'label' => 'Activity Log', 'urdu' => 'لاگ', 'active' => $r->routeIs('activity-log')],
                    ['route' => 'settings.items', 'icon' => 'ti-template', 'label' => 'Item Templates', 'urdu' => 'ٹیمپلیٹس', 'active' => $r->routeIs('item-templates.*') || $r->routeIs('settings.items')],
                    ['route' => 'settings.index', 'icon' => 'ti-settings', 'label' => 'Settings', 'urdu' => 'ترتیبات', 'active' => $r->routeIs('settings.*') && ! $r->routeIs('settings.items')],
                ],
            ],
        ];
    }

    public function shouldRender(): bool
    {
        return true;
    }

    public function render(): View
    {
        return view('components.sidebar');
    }
}
