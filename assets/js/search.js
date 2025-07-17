const searchInput = document.getElementById('searchInput');
const productsContainer = document.querySelector('.products-grid');
const emptyState = document.querySelector('.empty-state');
let debounceTimer;

searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);

    const query = searchInput.value.trim();

    debounceTimer = setTimeout(() => {
        const params = new URLSearchParams();
        if (query) params.set('search', query);

        fetch('products.php?' + params.toString() + '&ajax=1')
            .then(res => res.text())
            .then(html => {
                productsContainer.innerHTML = html;

                if (!productsContainer.innerHTML.trim()) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            });
    }, 300);
});
