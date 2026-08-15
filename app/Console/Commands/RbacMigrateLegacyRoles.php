<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class RbacMigrateLegacyRoles extends Command
{
    protected $signature = 'rbac:migrate-legacy-roles {--dry-run : Show what would change without writing}';

    protected $description = 'Convert legacy users.role strings into RBAC role assignments on the roles table.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $users = User::with('agent')->get();

        $this->info("Found {$users->count()} users to process.");

        $converted = 0;
        $skipped = 0;

        foreach ($users as $user) {
            $originalRole = $user->role;
            $targetRole = $this->resolveTargetRole($user);

            if ($targetRole === null) {
                $this->warn("  [skip] user #{$user->id} ({$user->email}) — cannot determine target role");
                $skipped++;

                continue;
            }

            $role = Role::where('slug', $targetRole)
                ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $user->company_id))
                ->orderByRaw('company_id IS NULL')
                ->first();

            if (! $role) {
                $this->warn("  [skip] user #{$user->id} ({$user->email}) — role '{$targetRole}' not found for company");
                $skipped++;

                continue;
            }

            if ($dryRun) {
                $this->line("  [dry-run] user #{$user->id} ({$user->email}) {$originalRole} -> assign role '{$targetRole}'");
            } else {
                $user->roles()->syncWithoutDetaching([$role->id]);
                $user->role = $targetRole;
                $user->saveQuietly();
                $this->line("  [ok] user #{$user->id} ({$user->email}) {$originalRole} -> role '{$targetRole}'");
            }

            $converted++;
        }

        $this->backfillOwnership($dryRun);

        $this->newLine();
        $this->info("Done. Converted: {$converted}, Skipped: {$skipped}.");

        return Command::SUCCESS;
    }

    protected function backfillOwnership(bool $dryRun): void
    {
        $agentUsers = User::whereHas('roles', fn ($q) => $q->where('slug', 'agent'))
            ->whereNotNull('agent_id')
            ->orderBy('id')
            ->get(['id', 'agent_id', 'company_id']);

        $agentUserByCompany = $agentUsers->groupBy('company_id');

        $orphanedClients = Client::whereNull('created_by')->get();

        if ($orphanedClients->isNotEmpty()) {
            $assigned = 0;

            foreach ($orphanedClients as $client) {
                $agentUser = $agentUserByCompany[$client->company_id]->first() ?? $agentUsers->first();

                if (! $agentUser) {
                    continue;
                }

                if ($dryRun) {
                    $this->line("  [dry-run] client #{$client->id} -> created_by user #{$agentUser->id}");
                } else {
                    $client->created_by = $agentUser->id;
                    $client->saveQuietly();
                }

                $assigned++;
            }

            $this->info("Backfilled created_by on {$assigned} client(s).");
        } else {
            $this->info('No orphaned clients to backfill.');
        }
    }

    protected function resolveTargetRole(User $user): ?string
    {
        return match ($user->role) {
            'super_admin' => 'admin',
            'admin' => $this->ensureSingleOwner($user) ? 'owner' : 'admin',
            'agent' => 'agent',
            'staff' => 'staff',
            default => null,
        };
    }

    protected function ensureSingleOwner(User $user): bool
    {
        $ownerCount = Role::where('slug', 'owner')
            ->where(fn ($q) => $q->whereNull('company_id')->orWhere('company_id', $user->company_id))
            ->whereHas('users')
            ->count();

        return $ownerCount === 0;
    }
}
