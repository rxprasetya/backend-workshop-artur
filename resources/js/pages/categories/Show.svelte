<script module lang="ts">
    import { index } from '@/routes/categories';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Kategori',
                href: index(),
            },
            {
                title: 'Detail',
                href: '',
            },
        ],
    };
</script>

<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';

    interface CategoryDetail {
        id: string;
        name: string;
        description: string | null;
        created_at: string;
    }

    let { category }: { category: CategoryDetail } = $props();
</script>

<AppHead title={`Detail Kategori - ${category.name}`} />

<div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">
    <div class="flex items-center justify-between">
        <Link
            href={index()}
            class="inline-flex items-center gap-1.5 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors"
        >
            ← Semua kategori
        </Link>
    </div>

    <div
        class="rounded-xl border border-sidebar-border/70 bg-card p-6 text-card-foreground shadow-xs dark:border-sidebar-border space-y-6"
    >
        <div class="space-y-1">
            <span class="rounded-md bg-muted px-2 py-0.5 text-xs font-mono text-muted-foreground">
                Kategori Produk
            </span>
            <h1 class="text-3xl font-bold tracking-tight text-foreground mt-2">
                {category.name}
            </h1>
        </div>

        <div class="space-y-2">
            <h2 class="text-sm font-semibold text-muted-foreground uppercase tracking-wider">
                Deskripsi
            </h2>
            <p class="text-base text-foreground leading-relaxed">
                {category.description || 'Tidak ada deskripsi untuk kategori ini.'}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-sidebar-border/50 text-sm">
            <div>
                <span class="text-muted-foreground block text-xs">ID (UUID v7)</span>
                <span class="font-mono text-xs break-all">{category.id}</span>
            </div>
            <div>
                <span class="text-muted-foreground block text-xs">Dibuat Pada</span>
                <span>{new Date(category.created_at).toLocaleString('id-ID')}</span>
            </div>
        </div>
    </div>
</div>
