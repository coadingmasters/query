#!/usr/bin/env python3
"""Generates the favicon set from resources/images/purrquerylogo.webp.

Run: python3 scripts/make-favicons.py

Two crops are used, because one image cannot serve both ends of the size
range. The full badge has "PURRQUERY" and "HAPPY CATS - HAPPY LIFE" set
around its rim; at 16px that ring is noise, so the small icons use only the
inner disc — the white cat on purple, which still reads as the brand at a
favicon's size. The large icons keep the whole badge, where the rim is
legible and looks like the seal it is.
"""
from PIL import Image, ImageDraw

SOURCE = "resources/images/purrquerylogo.webp"
OUT = "public"

# Where the inner disc sits in the source, measured off the artwork rather
# than guessed: the thin white ring lands at these fractions of the width.
DISC_START, DISC_END = 0.198, 0.801

logo = Image.open(SOURCE).convert("RGBA")
w, h = logo.size


def circular(img: Image.Image) -> Image.Image:
    """Clip to the inscribed circle so the corners stay transparent."""
    mask = Image.new("L", img.size, 0)
    ImageDraw.Draw(mask).ellipse((0, 0, img.width - 1, img.height - 1), fill=255)
    out = img.copy()
    out.putalpha(mask)
    return out


def on_white(img: Image.Image, size: int, pad: float = 0.06) -> Image.Image:
    """Flatten onto white. iOS and Android ignore transparency and would
    otherwise composite these against black."""
    canvas = Image.new("RGBA", (size, size), (255, 255, 255, 255))
    inner = size - int(size * pad * 2)
    scaled = img.resize((inner, inner), Image.LANCZOS)
    canvas.paste(scaled, (int(size * pad), int(size * pad)), scaled)
    return canvas.convert("RGB")


# --- small icons: the inner disc only -------------------------------------
box = (int(w * DISC_START), int(h * DISC_START), int(w * DISC_END), int(h * DISC_END))
disc = circular(logo.crop(box))

# One .ico carrying every size Windows and the browsers ask for.
disc.resize((64, 64), Image.LANCZOS).save(
    f"{OUT}/favicon.ico", sizes=[(16, 16), (32, 32), (48, 48), (64, 64)]
)

# PNG fallbacks that modern browsers prefer over the .ico.
for size in (32, 96):
    disc.resize((size, size), Image.LANCZOS).save(f"{OUT}/favicon-{size}.png", optimize=True)

# --- large icons: the whole badge -----------------------------------------
on_white(logo, 180).save(f"{OUT}/apple-touch-icon.png", optimize=True)
for size in (192, 512):
    on_white(logo, size).save(f"{OUT}/icon-{size}.png", optimize=True)

print("written:")
for name in ("favicon.ico", "favicon-32.png", "favicon-96.png",
             "apple-touch-icon.png", "icon-192.png", "icon-512.png"):
    import os
    print(f"  {name:22} {os.path.getsize(f'{OUT}/{name}') / 1024:6.1f} KB")
