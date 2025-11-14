// Product Detail Page - Alpine.js Component
document.addEventListener('alpine:init', () => {
    Alpine.data('productDetail', () => ({
        currentImage: window.productData.firstImage,
        currentImageIndex: 0,
        images: window.productData.images,
        selectedColor: null,
        selectedSize: null,
        quantity: 1,
        variants: window.productData.variants,

        nextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
            this.currentImage = this.images[this.currentImageIndex];
        },

        prevImage() {
            this.currentImageIndex = (this.currentImageIndex - 1 + this.images.length) % this.images.length;
            this.currentImage = this.images[this.currentImageIndex];
        },

        goToImage(index) {
            this.currentImageIndex = index;
            this.currentImage = this.images[index];
        },

        init() {
            // Keyboard navigation
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    this.prevImage();
                } else if (e.key === 'ArrowRight') {
                    this.nextImage();
                }
            });
        },

        async toggleWishlist() {
            const productId = window.productData.productId;

            try {
                const response = await fetch('/wishlist/toggle/' + productId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    if (data.added) {
                        Notifications.success('Produit ajouté à votre wishlist !');
                    } else {
                        Notifications.info('Produit retiré de votre wishlist');
                    }
                } else {
                    Notifications.error(data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                console.error('Erreur wishlist:', error);
                // Check if user is not authenticated
                if (error.message || error.toString().includes('401')) {
                    Notifications.warning('Veuillez vous connecter pour ajouter des produits à votre wishlist');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    Notifications.error('Erreur lors de l\'ajout à la wishlist');
                }
            }
        },

        get availableSizes() {
            if (!this.selectedColor) return [];
            const sizes = this.variants
                .filter(v => v.color === this.selectedColor)
                .map(v => parseInt(v.size))
                .sort((a, b) => a - b);
            return [...new Set(sizes)];
        },

        get currentStock() {
            if (!this.selectedSize || !this.selectedColor) return 0;
            const variant = this.variants.find(v =>
                v.size == this.selectedSize && v.color === this.selectedColor
            );
            return variant ? variant.stock : 0;
        },

        get stockStatus() {
            if (!this.selectedSize || !this.selectedColor) {
                return {
                    class: 'alert-apple-info',
                    icon: 'bi-info-circle',
                    message: 'Sélectionnez une taille et une couleur'
                };
            }
            const stock = this.currentStock;
            if (stock === 0) {
                return {
                    class: 'alert-apple-error',
                    icon: 'bi-x-circle',
                    message: 'Rupture de stock'
                };
            } else if (stock < 5) {
                return {
                    class: 'alert-apple-warning',
                    icon: 'bi-exclamation-triangle',
                    message: 'Plus que ' + stock + ' en stock !'
                };
            } else {
                return {
                    class: 'alert-apple-success',
                    icon: 'bi-check-circle',
                    message: 'En stock'
                };
            }
        },

        selectColor(color) {
            console.log('Couleur sélectionnée:', color);
            this.selectedColor = color;
            this.selectedSize = null;
            this.quantity = 1;
        },

        selectSize(size) {
            console.log('Taille sélectionnée:', size);
            this.selectedSize = size;
            this.quantity = 1;
        },

        async addToCart() {
            if (!this.selectedSize || !this.selectedColor) {
                Notifications.warning('Veuillez sélectionner une taille et une couleur');
                return;
            }
            if (this.currentStock === 0) {
                Notifications.error('Ce produit est en rupture de stock');
                return;
            }

            const variant = this.variants.find(v =>
                v.size == this.selectedSize && v.color === this.selectedColor
            );

            if (!variant) {
                Notifications.error('Variante introuvable');
                return;
            }

            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        variantId: variant.id,
                        quantity: this.quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    const msg = 'Produit ajouté au panier !<br><br>' +
                               '<strong>Taille:</strong> EU ' + this.selectedSize + '<br>' +
                               '<strong>Couleur:</strong> ' + this.selectedColor + '<br>' +
                               '<strong>Quantité:</strong> ' + this.quantity;

                    Notifications.success(msg, 5000);

                    // Ask to view cart after short delay
                    setTimeout(() => {
                        Notifications.confirm(
                            'Voulez-vous voir votre panier maintenant ?',
                            () => { window.location.href = '/cart'; },
                            () => { location.reload(); }
                        );
                    }, 1500);
                } else {
                    Notifications.error('Erreur: ' + data.message);
                }
            } catch (error) {
                Notifications.error('Erreur lors de l\'ajout au panier');
                console.error(error);
            }
        }
    }));
});
