export interface SeoOptions {
    title?: string | Ref<string>;
    description?: string | Ref<string>;
    ogTitle?: string | Ref<string>;
    ogDescription?: string | Ref<string>;
    twitterCard?: string | Ref<string>;
    ogImage?: string | Ref<string>;
    canonical?: string | Ref<string>;
}

export function useSeo(seoOptions: SeoOptions) {
    const config = useRuntimeConfig();
    const appName = config.public.appName || "Nature's Spring";
    const defaultDescription = 'Water For Filipino';
    const defaultOgImage = '/images/image-placeholder.png';
    // Base URL used for generating canonical URL if none is provided.
    const baseUrl = config.public.appUrl || 'http://localhost:3000';
    const route = useRoute();

    // Merge provided SEO values with global defaults.
    const meta = computed(() => ({
        title: seoOptions.title ? unref(seoOptions.title) : appName,
        description: seoOptions.description ? unref(seoOptions.description) : defaultDescription,
        ogTitle: seoOptions.ogTitle
            ? unref(seoOptions.ogTitle)
            : seoOptions.title
              ? unref(seoOptions.title)
              : appName,
        ogDescription: seoOptions.ogDescription
            ? unref(seoOptions.ogDescription)
            : seoOptions.description
              ? unref(seoOptions.description)
              : defaultDescription,
        twitterCard: seoOptions.twitterCard ? unref(seoOptions.twitterCard) : 'summary_large_image',
        ogImage: seoOptions.ogImage ? unref(seoOptions.ogImage) : defaultOgImage,
    }));

    // Set SEO meta tags using Nuxt's useSeoMeta
    useSeoMeta(meta.value);

    // Compute canonical URL: if provided, use it; otherwise, combine baseUrl and current route.
    const canonicalUrl = computed(() => {
        if (seoOptions.canonical) {
            return unref(seoOptions.canonical);
        }
        return `${baseUrl}${route.fullPath}`;
    });

    // Set the canonical link using useHead.
    useHead({
        link: [
            {
                rel: 'canonical',
                href: canonicalUrl.value,
            },
        ],
    });

    return { meta, canonicalUrl };
}
