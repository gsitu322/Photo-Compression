#PNG Photo Compression
PNG image compression though PHP's GD Graphics Library and ImageMagicK.


The two libraries use function to reduce the number of colors in our PNG image which results in a new image with a reduced image size. Reduced image sizes help in both mobile and web applications where speed and bandwidth are priority. The faster our content loads the happier our customers and consumers will be. With this utility I can compress an image that has similar compression qualities as of TinyPNG.com.


#How It Works
TinyPNG.com uses quantization to reduce image sizes. Quantization take a set of values and reduce it down to a smaller subset. In the case of images, quantization takes a large set of colors to generate a new image with a smaller subset of colors.

PNG images come with color palettes that range from 24-bit, 32-bit, 8-bit, greyscale, and many other bit depths. These color palettes can contain 1 color all the way to millions of colors. For example, a 24 bit color palette can contain 16,777,216 different color combinations and a 32-bit color palette can contain 4,294,967,296 colors combinations. The result of a high quality image with many colors is an image with a large file size.

With TinyPNG.com we are able to generate an 8-bit image using 256 colors. With 256 colors we are still able to clearly see the whole image and its details with the added benefit of a greatly reduced file size.

This is great for our web and mobile applications because consumers will be able to enjoy images with little hiccup in loading and quality. Also this helps our servers tremendously in reducing the required bandwidth to load the images.

##ImageMagicK
In the same way, ImageMagicK works like TinyPNG. We can use the same quantization technique to reduce the size of the image. With ImageMagicK we can specify in its parameters a specific number of colors to reduce down to. I've set the maximum colors to 256 colors which generates an 8-bit image.

From my testing I can see it has generated an 8-bit image. The image size that is comparable to the size of TinyPNG.com, sometime even less. However with quality, I do see some artifacts and error when it comes to the quantization of gradients. Also when generating transparent PNG, the transparency is not preserved.

##GD Library
This library also generates an 8-bit image from GD's imagetruecolortopalette function. This also allows us to specify a number of colors to reduce down to. I've also set this one to 256 colors to generate an 8-bit image.

From my testing I can see it has generated an 8-bit image. The image size is also comparable to TinyPNG, sometimes even less. With quality I do see artifacts and it does a worst job than ImageMagicK on gradients, but it is still a good representation of a reduced image. And like ImageMagicK, when generating transparent PNG, the transparency is not preserved.

#Todos
- Add dither to ImageMagicK and GD Library to smooth out colors in gradients
- Add average number color for ImageMagicK quantize
- Clean up html
- Fix PNG with transparency and quantization and GD library
