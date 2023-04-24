# Lightbox
By default, when `lightbox` entry is included all links pointing to an image (jpg,jpeg,png,gif) is open in a lightbox. 
Additionally, all YouTube links are open in a lightbox.

## Gallery
You can build a gallery lightbox by adding a `data-gallery` attribute to a link. Gallery will be build with images 
that has the same value in that attribute.

### Example
In a following example by clicking on **Product 1** or **Product 2** a gallery consisting of 2 images is open. 
```html
<a href="image1.jpg" data-gallery="product">Product 1</a>
<a href="image2.jpg" data-gallery="product">Product 2</a>
<a href="sample.jpg">Sample</a>
```
