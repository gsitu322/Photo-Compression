#Photo Compression
PNG Compression though GD Library, ImageMagick, and TinyPng.com


The three libraries work by reducing the number of colors in our PNG image. This results in creating a new image with a reduced image size. Reduced image sizes help in both mobile and web applications where speed and bandwidth is priority. The faster our content loads the happier our customers and consumers will be. With this utility I can compress an image that has similar compression qualities as of TinyPNG.com.


#How It Works
TinyPNG uses quantization to reduce image sizes by reducing the number of colors in an image. Most PNG images can come with color palettes that are 24-bit, 32-bit, greyscale, and other color spaces. These color palettes could have 1 color all the way to millions of colors. For example, a 24 bit color palette could contain 16,777,216 different color combinations. As you can see this could significantly increase the image size if there were more colors to an image.

With TinyPNG we are able to reduce our image to an 8-bit image. Resulting in an image with 256 colors instead of millions.

This results in an image with reduced size and little to no impact on quality. This is great for our web and mobile applications because consumers will be able to enjoy images with little hiccup in loading and quality. Also this would help our servers tremendously in reducing the required bandwidth to load large images.

##ImageMagicK
In the same way, ImageMagick can use quantization to reduce the size of the image. With ImageMagick we can specify in its parameters a specific number of colors to reduce down to. In the code it's been set to 256 colors. This is a representation of an 8-bit image.

##GD Library
This library also creates an 8-bit image from GD's function imagetruecolortopalette. This also allows us to specify a number of colors to reduce down to.


#Todos
- Add dither to ImageMagick to smooth out colors in gradients
- Add average number color for ImageMagick quantize
- Add functionally to GD Library
- Clean up html
- Fix PNG with transparency and quantization and GD library
