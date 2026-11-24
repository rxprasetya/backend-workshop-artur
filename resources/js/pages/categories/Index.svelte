<script module lang="ts">
    import { index } from '@/routes/categories';

    export const layout = {
        breadcrumbs: [
            {
                title: 'Kategori',
                href: index(),
            },
        ],
    };
</script>

<script lang="ts">
    import { Link } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import { show } from '@/routes/categories';

    interface Category {
        id: string;
        name: string;
        description: string | null;
        created_at?: string;
        updated_at?: string;
    }

    let { categories = [] }: { categories: Category[] } = $props();
</script>

<AppHead title="Daftar Kategori" />

<div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight">Daftar Kategori Produk</h1>
        <p class="text-sm text-muted-foreground">
            Data kategori diambil langsung dari database PostgreSQL.
        </p>
    </div>

    {#if categories.length === 0}
        <div
            class="flex min-h-48 items-center justify-center rounded-xl border border-dashed border-sidebar-border p-8 text-center text-sm text-muted-foreground"
        >
            Belum ada kategori yang tersedia.
        </div>
    {:else}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {#each categories as category (category.id)}
                <div
                    class="flex flex-col justify-between rounded-xl border border-sidebar-border/70 bg-card p-5 text-card-foreground shadow-xs transition hover:border-sidebar-border dark:border-sidebar-border"
                >
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold tracking-tight text-foreground">
                                <Link
                                    href={show(category.id)}
                                    class="hover:underline hover:text-primary transition-colors"
                                >
                                    {category.name}
                                </Link>
                            </h2>
                            <span class="rounded-md bg-muted px-2 py-0.5 text-xs font-mono text-muted-foreground">
                                UUID
                            </span>
                        </div>
                        <p class="text-sm text-muted-foreground line-clamp-3">
                            {category.description || 'Tidak ada deskripsi.'}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-sidebar-border/50 flex items-center justify-between">
                        <span class="text-[11px] font-mono text-muted-foreground/80 break-all">
                            ID: {category.id}
                        </span>
                        <Link
                            href={show(category.id)}
                            class="text-xs font-medium text-primary hover:underline"
                        >
                            Lihat Detail →
                        </Link>
                    </div>
                </div>
            {/each}
        </div>
    {/if}
</div>
