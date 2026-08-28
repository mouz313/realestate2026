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
    public string $uid;

    public function __construct()
    {
        $this->uid = 'sb' . uniqid();
        $r = request();

        $this->groups = [
            [
                'label' => null,
                'items' => [
                    [
                        'route' => dashboard_route(),
                        'icon' => 'ti-home',
                        'label' => 'Dashboard',
                        'active' => $r->routeIs(dashboard_route()),
                    ],
                ],
            ],
            [
                'label' => 'Sales & CRM',
                'items' => [
                    ['route' => 'clients.index', 'icon' => 'ti-users', 'label' => 'Clients', 'active' => $r->routeIs('clients.*'), 'permission' => 'view_clients'],
                    ['route' => 'call-logs.index', 'icon' => 'ti-phone-call', 'label' => 'Leads', 'active' => $r->routeIs('call-logs.*'), 'permission' => 'view_clients'],
                    ['route' => 'quotations.index', 'icon' => 'ti-file-description', 'label' => 'Quotations', 'active' => $r->routeIs('quotations.*'), 'permission' => 'view_quotations'],
                    ['route' => 'invoices.index', 'icon' => 'ti-file-invoice', 'label' => 'Invoices', 'active' => $r->routeIs('invoices.*'), 'permission' => 'view_invoices'],
                    ['route' => 'payments.index', 'icon' => 'ti-currency-dollar', 'label' => 'Payments', 'active' => $r->routeIs('payments.*'), 'permission' => 'view_all_payments'],
                ],
            ],
            [
                'label' => 'Real Estate',
                'items' => [
                    ['route' => 'properties.index', 'icon' => 'ti-building', 'label' => 'Properties', 'active' => $r->routeIs('properties.*') && ! $r->routeIs('properties.available'), 'permission' => 'view_properties'],
                    ['route' => 'properties.available', 'icon' => 'ti-building-community', 'label' => 'Available', 'active' => $r->routeIs('properties.available'), 'permission' => 'view_properties'],
                    ['route' => 'deals.index', 'icon' => 'ti-businessplan', 'label' => 'Deals', 'active' => $r->routeIs('deals.*'), 'permission' => 'view_deals'],
                    ['route' => 'tokens.index', 'icon' => 'ti-coin', 'label' => 'Tokens', 'active' => $r->routeIs('tokens.*'), 'permission' => 'view_deals'],
                    ['route' => 'property-visits.index', 'icon' => 'ti-calendar-event', 'label' => 'Visits', 'active' => $r->routeIs('property-visits.*'), 'permission' => 'view_visits'],
                ],
            ],
            [
                'label' => 'Rentals',
                'items' => [
                    ['route' => 'rental-records.index', 'icon' => 'ti-home-check', 'label' => 'Rented Records', 'active' => $r->routeIs('rental-records.*'), 'permission' => 'view_deals'],
                ],
            ],
            [
                'label' => 'Team & Payouts',
                'items' => [
                    ['route' => 'team.index', 'icon' => 'ti-users-group', 'label' => 'Team', 'active' => $r->routeIs('team.*') || $r->routeIs('agents.*'), 'permission' => 'view_team'],
                    ['route' => 'commissions.index', 'icon' => 'ti-percentage', 'label' => 'Commissions', 'active' => $r->routeIs('commissions.*'), 'permission' => 'view_all_commissions'],
                    ['route' => 'agent-payouts.index', 'icon' => 'ti-cash', 'label' => 'Agent Payouts', 'active' => $r->routeIs('agent-payouts.*'), 'permission' => 'view_payouts'],
                ],
            ],
            [
                'label' => null,
                'items' => [
                    ['route' => 'reports.index', 'icon' => 'ti-report', 'label' => 'Reports', 'active' => $r->routeIs('reports.*'), 'permission' => 'view_reports'],
                    ['route' => 'notifications.index', 'icon' => 'ti-bell', 'label' => 'Notifications', 'active' => $r->routeIs('notifications.*'), 'badge' => auth()->user()?->unreadNotifications()->count()],
                ],
            ],
            [
                'label' => 'Administration',
                'permission' => 'admin',
                'items' => [
                    ['route' => 'cities.index', 'icon' => 'ti-building-community', 'label' => 'Cities', 'active' => $r->routeIs('cities.*')],
                    ['route' => 'expenses.index', 'icon' => 'ti-receipt', 'label' => 'Expenses', 'active' => $r->routeIs('expenses.*')],
                    ['route' => 'roles.index', 'icon' => 'ti-shield', 'label' => 'Roles', 'active' => $r->routeIs('roles.*')],
                    ['route' => 'permissions.index', 'icon' => 'ti-key', 'label' => 'Permissions', 'active' => $r->routeIs('permissions.*')],
                    ['route' => 'activity-log', 'icon' => 'ti-history', 'label' => 'Activity Log', 'active' => $r->routeIs('activity-log')],
                    ['route' => 'settings.items', 'icon' => 'ti-template', 'label' => 'Item Templates', 'active' => $r->routeIs('item-templates.*') || $r->routeIs('settings.items')],
                    ['route' => 'settings.index', 'icon' => 'ti-settings', 'label' => 'Settings', 'active' => $r->routeIs('settings.*') && ! $r->routeIs('settings.items')],
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
