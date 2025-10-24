console.log("Category Filter Activated");


let category_btns = document.querySelectorAll('.category');


let products = document.querySelectorAll('article.product');


category_btns.forEach(btn => {
    btn.onclick = function() {
        
        let selectedId = btn.id;

        category_btns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

    //    كل المنتجات
        if (selectedId == '0') {
            products.forEach(prod => prod.style.display = 'block');
        }
        
        else {
            products.forEach(prod => {
                if (prod.id == selectedId) {
                    prod.style.display = 'block';
                } else {
                    prod.style.display = 'none';
                }
            });
        }
    };
});
