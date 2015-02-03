#PNG Photo Compression
PNG image compression though PHP's GD Graphics Library and ImageMagick.


The three libraries work by reducing the number of colors in our PNG image. This results in a new image with a reduced image size. Reduced image sizes help in both mobile and web applications where speed and bandwidth are priority. The faster our content loads the happier our customers and consumers will be. With this utility I can compress an image that has similar compression qualities as of TinyPNG.com.


#How It Works
TinyPNG uses quantization to reduce image sizes. Quantization is to take a set of values and reduce it down to a smaller subset. In the case of images, quantization takes a large set of colors to create a new image with the smaller subset of colors.

PNG images can come with color palettes that are 24-bit, 32-bit, greyscale, and many other bit depths. These color palettes could contain 1 color all the way to millions of colors. For example, a 24 bit color palette could contain 16,777,216 different color combinations and a 32-bit color palette can contain 4,294,967,296 colors combinations. The result of a high quality image with many colors is an image with a large file size.

With TinyPNG we are able to generate an 8-bit image using 256 colors. With 256 colors we are still able to clearly see the whole image and its details. We also have the added benefit of a greatly reduced file size.

This is great for our web and mobile applications because consumers will be able to enjoy images with little hiccup in loading and quality. Also this helps our servers tremendously in reducing the required bandwidth to load the images.

##ImageMagicK
In the same way, ImageMagick works like TinyPng. We can use the same quantization technique to reduce the size of the image. With ImageMagick we can specify in its parameters a specific number of colors to reduce down to. In the code it's been set to 256 colors, which results in generating an 8-bit image.

From my testing I can see it has generated an 8-bit image. The image size that is comparable to the size of TinyPNG.com, sometime even less. However with quality, I do see some artifacts and error when it comes to the quantization of gradients. Also when generating transparent PNG, the transparency is not preserved.

##GD Library
This library also generates an 8-bit image from GD's imagetruecolortopalette function. This also allows us to specify a number of colors to reduce down to.

From my testing I can see it has generated an 8-bit image. The image size is also comparable to TinyPNG, sometimes even less. With quality I do see artifacts and it does a worst job than ImageMagicK on gradients, but it is still a good representation of a reduced image. And like ImageMagicK, when generating transparent PNG, the transparency is not preserved.

#Todos
- Add dither to ImageMagick to smooth out colors in gradients
- Add average number color for ImageMagick quantize
- Add functionally to GD Library
- Clean up html
- Fix PNG with transparency and quantization and GD library
