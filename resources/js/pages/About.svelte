<script module lang="ts">
    export const layout = {
        breadcrumbs: [
            {
                title: 'Tentang',
                href: '/tentang',
            },
        ],
    };
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';

    let {
        appName,
        laravelVersion,
        features,
    }: {
        appName: string;
        laravelVersion: string;
        features: string[];
    } = $props();
</script>

<AppHead title="Tentang" />

<div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
    <div
        class="rounded-xl border border-sidebar-border/70 p-6 bg-card text-card-foreground shadow-xs dark:border-sidebar-border"
    >
        <h1 class="text-2xl font-bold tracking-tight">{appName}</h1>
        <p class="mt-1 text-sm text-muted-foreground">
            Laravel v{laravelVersion}
        </p>

        <div class="mt-6">
            <h2 class="text-base font-semibold">Fitur Utama</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-muted-foreground">
                {#each features as feature}
                    <li>{feature}</li>
                {/each}
            </ul>
        </div>
    </div>
</div>
