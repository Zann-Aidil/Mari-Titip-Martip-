import sys
try:
    from PIL import Image
except ImportError:
    print("Pillow not installed. Please install it using: pip install Pillow")
    sys.exit(1)

src_path = 'public/images/Logo Brand Martip.png'
try:
    img = Image.open(src_path)
except Exception as e:
    print(f"Failed to open image: {e}")
    sys.exit(1)

width, height = img.size
print(f"Dimensions: {width}x{height}")

# Top logo: from y=0 to height*0.58
top_box = (0, 0, width, int(height * 0.58))
top_img = img.crop(top_box)
top_img.save('public/images/logo-website.png')
print("Saved logo-website.png")

# Favicon: second icon at the bottom
icon_x = int(width * 0.35)
icon_y = int(height * 0.60)
icon_w = int(width * 0.20)
icon_h = int(height * 0.38)
favicon_box = (icon_x, icon_y, icon_x + icon_w, icon_y + icon_h)
favicon_img = img.crop(favicon_box)
favicon_img.save('public/images/favicon.png')
print("Saved favicon.png")
