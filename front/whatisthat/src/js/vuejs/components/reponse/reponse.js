var demo = new Vue({
    el: '#main',
    data: {
        // The layout mode, possible values are "grid" or "list".
        layout: 'grid',

        articles: [{
            "title": "REPONSEComponent",
            "url": "https://cdn.shopify.com/s/files/1/0361/8133/products/product-cropping-test-001_1024x1024_cropped.png?v=1440430124",
            "image": {
                "large":"https://cdn.shopify.com/s/files/1/0361/8133/products/product-cropping-test-001_1024x1024_cropped.png?v=1440430124",
                "small": "https://cdn.shopify.com/s/files/1/0361/8133/products/product-cropping-test-001_1024x1024_cropped.png?v=1440430124"
                //to be replaced with smaller square
                
            }
       
       
        }]

    }
});