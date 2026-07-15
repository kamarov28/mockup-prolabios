import os
from PIL import Image

brain_dir = r"C:\Users\thisissirhan\.gemini\antigravity\brain\bdf0b72a-91de-4ea3-9d61-0ad9716a59ef"
vendor_dir = r"c:\laragon\www\mockup-prolabios\public\images\vendor"

os.makedirs(vendor_dir, exist_ok=True)

logo_mappings = [
    {"src": "media__1784004028881.png", "dest": "bnf_korea.png"},
    {"src": "media__1784004034172.png", "dest": "leadfluid.png"},
    {"src": "media__1784004038725.png", "dest": "meizheng.png"},
    {"src": "media__1784004042326.png", "dest": "ksl_pulse.png"},
    {"src": "media__1784004046185.png", "dest": "solus_scientific.png"}
]

for mapping in logo_mappings:
    src_path = os.path.join(brain_dir, mapping["src"])
    dest_path = os.path.join(vendor_dir, mapping["dest"])
    
    if not os.path.exists(src_path):
        print(f"File not found: {src_path}")
        continue
        
    img = Image.open(src_path).convert("RGBA")
    width, height = img.size
    
    left_bound = int(width * 0.30)
    right_bound = int(width * 0.75)
    top_bound = int(height * 0.12)
    bottom_bound = int(height * 0.88)
    
    min_x, min_y = right_bound, bottom_bound
    max_x, max_y = left_bound, top_bound
    
    pixels = img.load()
    found_logo = False
    
    for y in range(top_bound, bottom_bound):
        for x in range(left_bound, right_bound):
            r, g, b, a = pixels[x, y]
            if r < 248 or g < 248 or b < 248:
                found_logo = True
                if x < min_x: min_x = x
                if x > max_x: max_x = x
                if y < min_y: min_y = y
                if y > max_y: max_y = y
                
    if found_logo:
        padding = 15
        crop_left = max(0, min_x - padding)
        crop_top = max(0, min_y - padding)
        crop_right = min(width, max_x + padding)
        crop_bottom = min(height, max_y + padding)
        
        cropped_img = img.crop((crop_left, crop_top, crop_right, crop_bottom))
        
        datas = cropped_img.getdata()
        newData = []
        for item in datas:
            if item[0] > 248 and item[1] > 248 and item[2] > 248:
                newData.append((255, 255, 255, 0))
            else:
                newData.append(item)
        cropped_img.putdata(newData)
        
        cropped_img.save(dest_path, "PNG")
        print(f"Extracted and saved: {mapping['dest']} (Size: {cropped_img.size})")
    else:
        print(f"Failed to find logo elements in {mapping['src']}")
