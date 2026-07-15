import pypdf

reader = pypdf.PdfReader("catalog.pdf")
num_pages = len(reader.pages)

with open("scratch/toc3.txt", "w", encoding="utf-8") as f:
    for idx in range(35, num_pages):
        f.write(f"--- PAGE {idx+1} ---\n")
        text = reader.pages[idx].extract_text()
        f.write(text + "\n\n")

print("Pages 36 to 44 extracted to scratch/toc3.txt")
