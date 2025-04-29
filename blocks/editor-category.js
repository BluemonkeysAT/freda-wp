// Optional: reorder so yours is on top
wp.domReady(() => {
    wp.blocks.setCategories([
        {
            slug: 'freda-category',
            title: '🎯 Freda Widgets',
            icon: 'smiley',
        },
        ...wp.blocks.getCategories().filter(cat => cat.slug !== 'freda-category')
    ]);
});