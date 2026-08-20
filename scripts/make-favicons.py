#!/usr/bin/env python3
"""Generates the favicon set from resources/images/purrquery.webp.

Run: python3 scripts/make-favicons.py

The source is the full lockup: an icon on a coral circle, the wordmark and a
tagline underneath. Favicons are generated from the icon alone, cropped from
this file at fixed coordinates rather than from the already-cropped header
badge (resources/images/purrquerylogo.webp), so this script has one source of
truth and both assets stay in sync if the master artwork ever changes.

The crop box below was measured off the artwork by scanning for the coral
circle's edges, not guessed. If the source logo changes, re-measure it rather
than eyeballing new numbers: a few pixels off-center is obvious at 512px and
invisible at 16px, which is exactly the size mistakes hide at.
"""
from PIL import Image, ImageChops, ImageDraw

SOURCE = "resources/images/purrquery.webp"
OUT = "public"

# The coral circle's bounding box in the 1254x1254 source: left, top, width,
# height. A few pixels of margin are included on top so the cat's ears, which
# overshoot the circle slightly by design, are not clipped by the circular
# mask applied below.
CIRCLE_BOX = (300, 10, 668, 668)

logo = Image.open(SOURCE).convert("RGBA")
icon = logo.crop((
    CIRCLE_BOX[0], CIRCLE_BOX[1],
    CIRCLE_BOX[0] + CIRCLE_BOX[2], CIRCLE_BOX[1] + CIRCLE_BOX[3],
))


def circular(img: Image.Image) -> Image.Image:
    """Clip to the inscribed circle so the corners stay transparent.

    The source PNG/WebP already carries its own alpha channel: the artwork is
    a cat and a circle on a transparent canvas, not an opaque square. Calling
    putalpha() with only the ellipse mask replaces that channel outright, so
    every originally-transparent pixel inside the ellipse - the antialiased
    edge around the cat's silhouette, mostly - turns solid at whatever colour
    happened to sit underneath, which for this source is black. Multiplying
    the ellipse mask against the existing alpha keeps a pixel invisible if
    either mask says it should be.
    """
    ellipse = Image.new("L", img.size, 0)
    ImageDraw.Draw(ellipse).ellipse((0, 0, img.width - 1, img.height - 1), fill=255)
    original_alpha = img.getchannel("A")
    combined = ImageChops.multiply(ellipse, original_alpha)
    out = img.copy()
    out.putalpha(combined)
    return out


def on_white(img: Image.Image, size: int, pad: float = 0.08) -> Image.Image:
    """Flatten onto white. iOS and Android ignore transparency and would
    otherwise composite these against black."""
    canvas = Image.new("RGBA", (size, size), (255, 255, 255, 255))
    inner = size - int(size * pad * 2)
    scaled = img.resize((inner, inner), Image.LANCZOS)
    canvas.paste(scaled, (int(size * pad), int(size * pad)), scaled)
    return canvas.convert("RGB")


# --- small icons: circularly clipped, transparent corners -----------------
disc = circular(icon)

# One .ico carrying every size Windows and the browsers ask for.
disc.resize((64, 64), Image.LANCZOS).save(
    f"{OUT}/favicon.ico", sizes=[(16, 16), (32, 32), (48, 48), (64, 64)]
)

# PNG fallbacks that modern browsers prefer over the .ico.
for size in (32, 96):
    disc.resize((size, size), Image.LANCZOS).save(f"{OUT}/favicon-{size}.png", optimize=True)

# --- large icons: flattened onto white, iOS/Android home screens ----------
on_white(icon, 180).save(f"{OUT}/apple-touch-icon.png", optimize=True)
for size in (192, 512):
    on_white(icon, size).save(f"{OUT}/icon-{size}.png", optimize=True)

print("written:")
for name in ("favicon.ico", "favicon-32.png", "favicon-96.png",
             "apple-touch-icon.png", "icon-192.png", "icon-512.png"):
    import os
    print(f"  {name:22} {os.path.getsize(f'{OUT}/{name}') / 1024:6.1f} KB")
